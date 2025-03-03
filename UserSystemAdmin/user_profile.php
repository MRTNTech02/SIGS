<?php 
  session_start();
  include ("../server_connection/db_connect.php");
  if (empty($_SESSION["username"]) && empty ($_SESSION["a_password"])) {
    header("location: index.php");
    exit();
  }
  if (!empty($_SESSION["username"])){
    $admin_id = $_SESSION["admin_id"];

    $sql = "SELECT * FROM admin_tbl WHERE admin_id = :admin_id";
    try{
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
      $stmt->execute();

      if($stmt->rowCount() > 0){
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        $admin_name = $data['a_fname'] . ' ' . $data['a_lname'] . ' ' . $data['a_suffix'];
        $username = $data['username'];
        $a_email = $data['a_email'];
        $a_fname = $data['a_fname'];
        $a_mname = $data['a_mname'];
        $a_lname = $data['a_lname'];
        $a_suffix = $data['a_suffix'];
        $a_sex = $data['a_sex'];
        $a_profile = $data['a_profile'];
        $a_birthdate = $data['a_birthdate'];
      }
    }catch(Exception $e){
      echo "Error: " . $e->getMessage();
      exit();
    }
  }
  if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: index.php");
    exit();
  }
?>
<!-- PHP code for editing user -->
<?php 
    if (isset($_POST['saveEdit'])) {
        $admin_id = $_POST["admin_id"];
        $username = $_POST["username"];
        $a_fname = $_POST["a_fname"];
        $a_mname = $_POST["a_mname"];
        $a_lname = $_POST["a_lname"];
        $a_suffix = $_POST["a_suffix"];
        $a_sex = $_POST["a_sex"];
        $a_birthdate = $_POST["a_birthdate"];
        $a_email = $_POST["a_email"];
    
        $sql = "UPDATE admin_tbl SET 
            username = :username, 
            a_fname = :a_fname, 
            a_mname = :a_mname, 
            a_lname = :a_lname, 
            a_suffix = :a_suffix, 
            a_sex = :a_sex, 
            a_birthdate = :a_birthdate, 
            a_email = :a_email
            WHERE admin_id = :admin_id";
    
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':a_fname', $a_fname);
            $stmt->bindParam(':a_mname', $a_mname);
            $stmt->bindParam(':a_lname', $a_lname);
            $stmt->bindParam(':a_suffix', $a_suffix);
            $stmt->bindParam(':a_sex', $a_sex);
            $stmt->bindParam(':a_birthdate', $a_birthdate);
            $stmt->bindParam(':a_email', $a_email);
            $stmt->bindParam(':admin_id', $admin_id);
    
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                echo "<script>
                    alert('Profile Updated Successfully!');
                    window.location.href = 'user_profile.php';
                </script>";
                exit();
            } else {
                echo "No record has been updated";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
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
        include '../Assets/components/Navbar.php';
    ?>
    <div class="d-flex">
        <?php
            include '../Assets/components/AdminSidebar.php';
        ?>
        <!-- Main content -->
        <div class="content p-4">
            <h4 class="text-muted">Profile</h4>
            <!-- Navigation Buttons -->
            <div class="d-flex justify-content-start mb-3">
                <a href="user_profile.php" class="btn btn-success me-2">Information</a>
                <a href="change_password.php" class="btn btn-outline-dark me-2">Change Password</a>
            </div>
            <div class="main-content">
                <div class="row">
                    <!-- Profile Picture Section -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header text-center">Profile Picture</div>
                            <div class="card-body text-center">
                                <img src="../Assets/img/profile_pictures/<?php echo $a_profile ?>" class="avatar img-fluid mb-3" alt="Profile Picture">
                                <form method="post" action="EditProfilePicture.php?admin_id=<?php echo $admin_id ?>" enctype="multipart/form-data">
                                    <input type="hidden" name="admin_id" id="admin_id" value="<?php echo $admin_id?>">
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="a_profile">Upload your profile picture here</label>
                                            <input type="file" class="form-control" id="a_profile" name="a_profile" accept="image/png/jpg" required>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <button type="submit" name="saveEdit" id="saveEdit" class="btn btn-success">Save Changes</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Information Section -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">Information</div>
                            <div class="card-body">
                                <form action="user_profile.php" method="post">
                                    <input type="hidden" name="admin_id" value="<?php echo $admin_id ?>">
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label for="a_fname">First Name</label>
                                            <input type="text" class="form-control" name="a_fname" id="a_fname" value="<?php echo $a_fname ?>">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="a_mname">Middle Name</label>
                                            <input type="text" class="form-control" name="a_mname" id="a_mname" value="<?php echo $a_mname ?>">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="a_lname">Last Name</label>
                                            <input type="text" class="form-control" id="a_lname" name="a_lname" value="<?php echo $a_lname ?>">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="a_suffix">Suffix</label>
                                            <input type="text" class="form-control" id="a_suffix" name="a_suffix" value="<?php echo $a_suffix ?>">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="username">Username</label>
                                            <input type="text" class="form-control" id="username" name="username" value="<?php echo $username?>">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="a_email">Email Address</label>
                                            <input type="email" class="form-control" id="a_email" name="a_email" value="<?php echo $a_email?>">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="a_sex">Sex</label>
                                            <select class="form-control" id="a_sex" name="a_sex">
                                                <option value="Male" <?= $a_sex == 'Male' ? 'selected' : '' ?>>Male</option>
                                                <option value="Female" <?= $a_sex == 'Female' ? 'selected' : '' ?>>Female</option>
                    
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="a_birthdate">Birthdate</label>
                                            <input type="date" class="form-control" id="a_birthdate" name="a_birthdate" value="<?php echo $a_birthdate?>">
                                        </div>
                                    </div>
                                    <button type="submit" name="saveEdit" id="saveEdit" class="btn btn-success">Update Information</button>
                                </form>
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
        .avatar 
        {
            margin-top: 10px;
            vertical-align: middle;
            width: 225px;
            height: 225px;
            object-fit: cover; 
            border-radius: 50%;
        }
        .card{
            height: 500px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>