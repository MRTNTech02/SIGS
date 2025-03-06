<?php 
  session_start();
  include ("../server_connection/db_connect.php");

  if (!isset($_SESSION["id_number"]) || !isset($_SESSION["user_password"])) {
      header("location: index.php");
      exit();
  }

  $user_id = $_SESSION["user_id"];

  $sql = "SELECT * FROM users_tbl WHERE user_id=:user_id";
  try {
      $result = $conn->prepare($sql);
      $result->execute(['user_id' => $user_id]);

      if ($result->rowCount() > 0) {
          $data = $result->fetch(PDO::FETCH_ASSOC);
          $registrar_name = trim("{$data['u_fname']} {$data['u_lname']} {$data['u_suffix']}");
          $id_number = $data['id_number'];
      }
  } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
  }

  if (isset($_GET['logout'])) {
      session_destroy();
      header("location: index.php");
      exit();
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Information</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php
        include '../Assets/components/RegistrarNavbar.php';
    ?>
    <div class="d-flex">
        <?php
            include '../Assets/components/RegistrarSidebar.php';
        ?>

            <!-- Main Content -->
            <div class="content p-4 flex-grow-1">
                <h4 class="text-muted">Dashboard</h4>

                <div class="row">
                    <!-- Student Count -->
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="box text-center p-4 border border-success rounded">
                            <div class="text-success fw-bold">Students</div>
                            <span class="h4 text-dark">
                                <?php 
                                    $sql = "SELECT COUNT(*) AS countStudents FROM students_tbl WHERE s_status = 'Active'";
                                    try {
                                        $result = $conn->prepare($sql);
                                        $result->execute();
                                        $row = $result->fetch(PDO::FETCH_ASSOC); 
                                        echo $row ? $row['countStudents'] : '0';
                                    } catch (Exception $e) {
                                        echo "Error: " . $e->getMessage();
                                    }
                                ?>
                            </span> 
                        </div>
                    </div>

                    <!-- Faculty Count -->
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="box text-center p-4 border border-success rounded">
                            <div class="text-success fw-bold">Faculty</div>
                            <span class="h4 text-dark">
                                <?php 
                                    $sql = "SELECT COUNT(*) AS countFaculty FROM users_tbl WHERE role = 'Faculty' AND user_status = 'Active'";
                                    try {
                                        $result = $conn->prepare($sql);
                                        $result->execute();
                                        $row = $result->fetch(PDO::FETCH_ASSOC); 
                                        echo $row ? $row['countFaculty'] : '0';
                                    } catch (Exception $e) {
                                        echo "Error: " . $e->getMessage();
                                    }
                                ?>
                            </span>
                        </div>
                    </div>

                    <!-- Registrar Count -->
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="box text-center p-4 border border-success rounded">
                            <div class="text-success fw-bold">Registrar</div>
                            <span class="h4 text-dark">
                                <?php 
                                    $sql = "SELECT COUNT(*) AS countRegistrar FROM users_tbl WHERE role = 'Registrar' AND user_status = 'Active'";
                                    try {
                                        $result = $conn->prepare($sql);
                                        $result->execute();
                                        $row = $result->fetch(PDO::FETCH_ASSOC); 
                                        echo $row ? $row['countRegistrar'] : '0';
                                    } catch (Exception $e) {
                                        echo "Error: " . $e->getMessage();
                                    }
                                ?>
                            </span> 
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Reported Bugs -->
                    <div class="col-lg-6 mb-3">
                        <div class="box-2 p-3 border border-success rounded">
                            <div class="text-success fw-bold mb-2">Reported Bugs</div>
                            <div class="table-responsive" style="height: 50vh; overflow-y: auto;">
                                <table class="table table-striped">
                                    <thead class="text-center">
                                        <tr>
                                            <th>No.</th>
                                            <th>Ticket Number</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        <?php
                                            $sql = "SELECT * FROM bugs_tbl WHERE fk_user_id=:user_id ORDER BY bug_status DESC";
                                            try {
                                                $result = $conn->prepare($sql);
                                                $result->execute(['user_id' => $user_id]);
                                                $i = 1;
                                                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                                    echo "<tr>
                                                            <td>{$i}</td>
                                                            <td>{$row["bug_ticket"]}</td>
                                                            <td>{$row["bug_status"]}</td>
                                                            <td>
                                                                <a href='ViewTicket.php?bug_id={$row["bug_id"]}' class='btn btn-info btn-sm'>
                                                                    <i class='fas fa-eye'></i>
                                                                </a>
                                                            </td>
                                                          </tr>";
                                                    $i++;
                                                }
                                                if ($i === 1) {
                                                    echo "<tr><td colspan='4'>No records found.</td></tr>";
                                                }
                                            } catch (Exception $e) {
                                                echo "Error: " . $e->getMessage();
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Users Table -->
                    <div class="col-lg-6 mb-3">
                        <div class="box-2 p-3 border border-success rounded">
                            <div class="text-success fw-bold mb-2">Faculty</div>
                            <div class="table-responsive" style="height: 50vh; overflow-y: auto;">
                                <table class="table table-striped">
                                    <thead class="text-center">
                                        <tr>
                                            <th>No.</th>
                                            <th>Name</th>
                                            <th>Role</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        <?php
                                            $sql = "SELECT * FROM users_tbl WHERE role = 'Faculty'";
                                            try {
                                                $result = $conn->prepare($sql);
                                                $result->execute();
                                                $i = 1;
                                                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                                    $roleLabel = ($row["role"] == 'Faculty') ? "<span class='badge bg-primary'>Faculty</span>" : "<span class='badge bg-secondary'>Registrar</span>";
                                                    echo "<tr>
                                                            <td>{$i}</td>
                                                            <td>{$row["id_number"]}</td>
                                                            <td>{$roleLabel}</td>
                                                            <td>
                                                                <a href='ViewRegistrarUser.php?user_id={$row["user_id"]}' class='btn btn-info btn-sm'>
                                                                    <i class='fas fa-eye'></i>
                                                                </a>
                                                            </td>
                                                          </tr>";
                                                    $i++;
                                                }
                                            } catch (Exception $e) {
                                                echo "Error: " . $e->getMessage();
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        html, body {
            height: 100vh;
            margin: 0;
            overflow: hidden; /* Prevents scrolling */
            }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
