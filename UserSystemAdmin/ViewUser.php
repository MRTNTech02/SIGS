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

<!-- calling holder record -->
<?php
    if (isset($_GET['user_id'])) {
        $user_id = $_GET['user_id'];

        $sql = "SELECT * FROM users_tbl WHERE user_id='$user_id'";
        try{
        $result = $conn->prepare($sql);
        $result->execute();

        if($result->rowCount()>0){
            $data = $result->fetch(PDO::FETCH_ASSOC);
            $id_number = $data["id_number"];
            $u_fname = $data["u_fname"];
            $u_mname = $data["u_mname"];
            $u_lname = $data["u_lname"];
            $u_suffix = $data["u_suffix"];
            $user_fullname = $data["u_fname"] . " " . $data["u_lname"];
            $u_sex = $data["u_sex"];
            $u_birthdate = $data["u_birthdate"];
            $user_email = $data["user_email"];
            $role = $data["role"];
            $user_password = $data["user_password"];
            $user_profile = $data["user_profile"];
            $user_status = $data["user_status"];
        }
        }catch(Exception $e){
        echo "Error" . $e;
        }
    };
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../Bootstrap/css/bootstrap.min.css">
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
        <div class="content p-4 w-100">
            <h4 class="text-muted mb-4">User Information</h4>
            <div class="main-content">
                <div class="container">
                        <div class="main">
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="box2" style="margin-top: 10px; margin-bottom: 10px; height:auto; width: 100%">
                                            <?php 
                                                if (empty($user_profile)){
                                                    echo " 
                                                        <img src='../Assets/img/profile_pictures/userdefaultprofile.jpg' alt='Huhu' class='avatar  mx-auto d-block'>   
                                                    ";
                                                }else{
                                                    echo " 
                                                        <img src='../Assets/img/profile_pictures/$user_profile' alt='else' class='avatar  mx-auto d-block'>
                                                    ";
                                                }
                                            ?>
                                        </div> 
                                    </div>
                                    <div class="col-lg-7">
                                        <div class="box2" style="margin-top: 10px; margin-bottom: 10px; height:auto; width: 100%">
                                        
                                            <table class="table" >
                                                <tr>
                                                    <td><h4>Full Name: </h4></td>
                                                    <td><h4><?php echo $user_fullname ?></h4></td>
                                                </tr>
                                                <tr>
                                                    <td><h4>ID Number: </h4></td>
                                                    <td><h4><?php echo $id_number?></h4></td>
                                                </tr>
                                                <tr>
                                                    <td><h4>Role:</h4></td>
                                                    <td><h4><?php echo $role ?></h4></td>
                                                </tr>
                                                <tr>
                                                <td><h4>Assigned Office:</h4></td>
                                                <td>
                                                    <?php
                                                        $aoQuery = "SELECT office_name FROM holder_tbl INNER JOIN office_tbl ON fk_office = ID_office WHERE ID_holder = :ID_holder;";

                                                        try {
                                                            $result = $conn->prepare($aoQuery);
                                                            $result->bindParam(':ID_holder', $ID_holder, PDO::PARAM_INT);
                                                            $result->execute();

                                                            if ($result->rowCount() > 0) {
                                                                $row = $result->fetch(PDO::FETCH_ASSOC);
                                                                echo "<h4> {$row['office_name']}</h4>";
                                                            } 
                                                        } catch (Exception $e) {
                                                            echo "Unexpected error has occurred! " . $e->getMessage();
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
