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
        $admin_name = $data['a_fname'] . ' ' . $data['a_lname'] ;
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
                                456
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
                        <div class="box d-flex flex-column p-4 border border-success rounded" style="min-height: 50vh; min-width: 100%;">
                            <div class="text-success text-bold mb-2">Bug Reports</div> 
                            <span class="h4 text-dark">56</span> 
                        </div>
                    </div>
                    <div class="col-12 col-sm-8 col-md-8 col-lg-8 mb-3">
                        <div class="box d-flex flex-column p-4 border border-success rounded" style="min-height: 50vh; width: 100%;">
                            <div class="text-success text-bold mb-2">User Logs</div> 
                            <span class="h4 text-dark">56</span> 
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