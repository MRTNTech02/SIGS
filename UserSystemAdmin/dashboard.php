<?php 
  session_start();
  include ("../server_connection/db_connect.php");
  if (empty($_SESSION["username"]) && empty ($_SESSION["a_password"])) {
    header("location: index.php");
  }
  if (!empty($_SESSION["username"])){
    $admin_id = $_SESSION["admin_id"];

    $sql = "SELECT * FROM admin_tbl WHERE admin_id=$admin_id";
    try{
      $result = $conn->prepare($sql);
      $result->execute();

      if($result->rowCount()>0){
        $data = $result->fetch(PDO::FETCH_ASSOC);
        $admin_name = $data['a_fname'] . ' ' . $data['a_lname'] . ' ' . $data['a_suffix'];
        $username = $data['username'];
      }
    }catch(Exception $e){
      echo "Error" . $e;
    }
  };
  if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
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
        include '../Assets/components/Navbar.php';
    ?>
    <div class="d-flex">
        <?php
            include '../Assets/components/AdminSidebar.php';
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
                    <div class="col-12 col-sm-4 col-md-4 col-lg-4 mb-2">
                    <div class="box-2 p-2 border border-success rounded">
                            <div class="text-success mb-2">Reported Bugs</div>
                            <div class="table-responsive" style="height: 40vh; overflow-y: auto;">
                                <table class="table table-striped">
                                    <thead class="text-center">
                                        <tr>
                                            <th>No.</th>
                                            <th>Ticket Number</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableBody" align="center">
                                        <?php
                                            $sql = "SELECT * FROM bugs_tbl  WHERE bug_status = 'Open' ORDER BY insrt_ts DESC";
                                            try
                                            {
                                                $result=$conn->prepare($sql);
                                                $result->execute();
                                                if($result->rowcount()>0)
                                                {
                                                    $i=1;
                                                    while($row=$result->fetch(PDO::FETCH_ASSOC))
                                                    {
                                                        echo "
                                                            <tr>
                                                                <td class='text-center'>{$i} </td>
                                                                <td class='text-center'>{$row["bug_ticket"]}</td>
                                                                <td class='text-center'>{$row["bug_status"]}</td>
                                                                <td>
                                                                    <a href='ViewTicket.php?bug_id={$row["bug_id"]}' class='btn btn-info btn-sm'>
                                                                        <i class='fas fa-eye'></i>
                                                                    </a>
                                                                </td>";
                                                                ?>
                                                            </tr>
                                                        <?php
                                                        $i++;
                                                    }
                                                }
                                                else
                                                {
                                                    echo "<tr><tdv colspan = '6'> No records found. </td></tr>";
                                                }
                                            }
                                            catch(Exception $e)
                                            {
                                                echo "Unexpected error has been occured!" . $e ->getMessage();
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-8 col-md-8 col-lg-8 mb-3">
                        <div class="box d-flex flex-column p-2 border border-success rounded" style="min-height: 50vh; width: 100%;">
                            <div class="text-success text-bold mb-2">User Logs</div> 
                            <div class="table-responsive" style="height: 40vh; overflow-y: auto;">
                                <table class="table table-striped" id="studentsTable">
                                    <thead align="center">
                                        <tr>
                                            <th>No.</th>
                                            <th>Name</th>
                                            <th>Role</th>
                                            <th>Action</th>
                                            <th>Time Stamp</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableBody" align="center">
                                        <?php
                                            $sql = "SELECT logs.log_id, 
                                            COALESCE(
                                                CONCAT(users.u_fname, ' ', users.u_lname, ' ', IFNULL(users.u_suffix, '')), 
                                                CONCAT(admins.a_fname, ' ', admins.a_lname, ' ', IFNULL(admins.a_suffix, ''))
                                            ) AS full_name, 
                                            logs.role, 
                                            logs.action, 
                                            logs.log_ts 
                                        FROM user_logs_tbl AS logs
                                        LEFT JOIN users_tbl AS users ON logs.fk_user_id = users.user_id 
                                        LEFT JOIN admin_tbl AS admins ON logs.fk_admin_id = admins.admin_id
                                        WHERE logs.log_ts >= NOW() - INTERVAL 7 DAY
                                        ORDER BY logs.log_id DESC;
                                        ";
                                            try
                                            {
                                                $result=$conn->prepare($sql);
                                                $result->execute();
                                                if($result->rowcount()>0)
                                                {
                                                    $i=1;
                                                    while($row=$result->fetch(PDO::FETCH_ASSOC))
                                                    {
                                                        echo "
                                                            <tr>
                                                                <td class='text-center'>{$i} </td>
                                                                <td class='text-center'>{$row["full_name"]}</td>
                                                                <td>{$row["role"]}</td>
                                                                <td>{$row["action"]}</td>
                                                                <td>{$row["log_ts"]}</td>
                                                            </tr>";
                                                        $i++;
                                                    }
                                                }
                                                else
                                                {
                                                    echo "<tr><td colspan='5'> No records found. </td></tr>";
                                                }
                                            }
                                            catch(Exception $e)
                                            {
                                                echo "Unexpected error has occurred!" . $e->getMessage();
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
        @media (max-width: 768px) {
            .d-flex {
                flex-wrap: wrap;  
            }
            .d-flex > * {
                margin-bottom: 5px; 
            }
        }
    </style>
</body>
</html>
<script src="../Bootstrap/js/bootstrap.bundle.min.js"></script> 