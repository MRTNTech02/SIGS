<?php 
  session_start();
  include ("../server_connection/db_connect.php");
  if (empty($_SESSION["id_number"]) && empty ($_SESSION["user_password"])) {
    header("location: index.php");
  }
  if (!empty($_SESSION["id_number"])){
    $user_id = $_SESSION["user_id"];

    $sql = "SELECT * FROM users_tbl WHERE user_id=$user_id";
    try{
      $result = $conn->prepare($sql);
      $result->execute();

      if($result->rowCount()>0){
        $data = $result->fetch(PDO::FETCH_ASSOC);
        $registrar_name = $data['u_fname'] . ' ' . $data['u_lname'] . ' ' . $data['u_suffix'] ;
        $id_number = $data['id_number'];
      }
    }catch(Exception $e){
      echo "Error" . $e;
    }
  };
  if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['id_number']);
    header("location: index.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <?php
        include '../Assets/components/RegistrarNavbar.php';
    ?>
    <div class="d-flex">
        <?php
            include '../Assets/components/RegistrarSidebar.php';
        ?>
        <!-- Main content -->
        <div class="content p-4 flex-grow-1">
            <h4 class="text-muted">Dashboard</h4>
            <div class="main-content">
                <div class="row">
                    <div class="col-12 col-sm-4 col-md-4 col-lg-4 mb-3 ">
                        <div class="box d-flex flex-column justify-content-center align-items-center p-4 border border-success rounded" style="min-height: 150px; min-width: 100%;">
                            <div class="text-success text-bold mb-2">Students</div>
                            <span class="h4 text-dark">
                                <?php 
                                    $sql = "SELECT count(*) countStudents FROM students_tbl WHERE s_status = 'Active'";

                                    try {
                                        $result = $conn->prepare($sql);
                                        $result->execute();
                                        $row = $result->fetch(PDO::FETCH_ASSOC); 

                                        if ($row) {
                                            echo "{$row['countStudents']}";
                                        }
                                    } catch (Exception $e) {
                                        echo "Unexpected error has occurred! " . $e->getMessage();
                                    }
                                ?>
                            </span> 
                        </div>
                    </div>
                    <div class="col-12 col-sm-4 col-md-4 col-lg-4 mb-3">
                        <div class="box d-flex flex-column justify-content-center align-items-center p-4 border border-success rounded" style="min-height: 150px; min-width: 100%;">
                            <div class="text-success text-bold mb-2">Faculty</div>
                            <span class="h4 text-dark">
                                <?php 
                                    $sql = "SELECT count(*) as countFaculty FROM users_tbl WHERE role = 'Faculty' and user_status = 'Active'";

                                    try {
                                        $result = $conn->prepare($sql);
                                        $result->execute();
                                        $row = $result->fetch(PDO::FETCH_ASSOC); 

                                        if ($row) {
                                            echo "{$row['countFaculty']}";
                                        }
                                    } catch (Exception $e) {
                                        echo "Unexpected error has occurred! " . $e->getMessage();
                                    }
                                ?>
                            </span>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4 col-md-4 col-lg-4 mb-3">
                        <div class="box d-flex flex-column justify-content-center align-items-center p-4 border border-success rounded" style="min-height: 150px; min-width: 100%;">
                            <div class="text-success text-bold mb-2">Registrar</div> 
                            <span class="h4 text-dark">
                                <?php 
                                    $sql = "SELECT count(*) as countRegistrar FROM users_tbl WHERE role = 'Registrar' and user_status = 'Active'";

                                    try {
                                        $result = $conn->prepare($sql);
                                        $result->execute();
                                        $row = $result->fetch(PDO::FETCH_ASSOC); 

                                        if ($row) {
                                            echo "{$row['countRegistrar']}";
                                        }
                                    } catch (Exception $e) {
                                        echo "Unexpected error has occurred! " . $e->getMessage();
                                    }
                                ?>
                            </span> 
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-sm-4 col-md-4 col-lg-4 mb-3">
                    <div class="box d-flex flex-column justify-content-center align-items-center p-3 border border-success rounded" style="min-height: 150px; min-width: 100%;">
                            <div class="text-success text-bold mb-2">Grade Submission</div>
                            <!-- Circular Progress Bar -->
                            <div class="position-relative">
                                <svg width="250" height="250">
                                    <circle cx="100" cy="100" r="70" stroke="#e0e0e0" stroke-width="20" fill="none"/>
                                    <circle cx="100" cy="100" r="70" stroke="green" stroke-width="20" fill="none" stroke-dasharray="189" stroke-dashoffset="-95"/>
                                </svg>
                                <div class="position-absolute top-50 start-50 translate-middle fw-bold text-dark">50%</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-8 col-md-8 col-lg-8 mb-3">
                        <div class="box d-flex flex-column p-4 border border-success rounded" style="min-height: 50vh; width: 100%;">
                            <div class="text-success text-bold mb-2"></div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        @media (max-width: 768px) {
            .d-flex {
                flex-wrap: wrap;  
            }
            .d-flex > * {
                margin-bottom: 5px; 
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>