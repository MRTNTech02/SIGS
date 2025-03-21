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

<!-- fetching faculty record -->
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
    <title>User Information</title>
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
        <div class="d-flex justify-content-between">
        <h4 class="text-muted mb-4">User Information</h4>
                <a href="user_management.php" class="link-offset-2 link-underline link-underline-opacity-0">
                    <button type="button" class="btn btn-warning d-flex justify-content-center align-items-center text-center p-1 mb-2">
                        <i class="fas fa-arrow-left"></i> 
                        <span class="text-wrap p-1">Go Back</span> 
                    </button>
                </a>
        </div>
            
            <div class="main-content">
                <div class="container">
                        <div class="main">
                            <div class="profile-info-container">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="box2" style="margin-top: 10px; margin-bottom: 10px; height:auto; width: 100%">
                                            <?php 
                                                if (empty($user_profile)){
                                                    echo " 
                                                        <img src='../Assets/img/profile_pictures/userdefaultprofile.png' alt='Huhu' class='avatar  mx-auto d-block'>   
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
                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <h5>Full Name: <?php echo $user_fullname ?> </h5>
                                            </div>
                                            <div class="col-12 mb-3">
                                                <h5>ID Number: <?php echo $id_number ?> </h5>
                                            </div>
                                            <div class="col-12 mb-3">
                                                <h5>Role: <?php echo $role ?> </h5>
                                            </div>
                                            <div class="col-12 mb-3">
                                                <h5>Status: <?php echo $user_status ?> </h5>
                                            </div>
                                        </div>
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
        .avatar 
        {
            margin-top: 10px;
            vertical-align: middle;
            width: 225px;
            height: auto;
            border-radius: 50%;
        }
        .table-container { 
            margin-top: 20px; 
            min-width: 100%; 
        }
        .table-responsive {
            height: 375px;
        }
        .content { 
            transition: margin-left 0.3s ease; 
        }
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
<script src="../Bootstrap/js/bootstrap.bundle.min.js"></script>
