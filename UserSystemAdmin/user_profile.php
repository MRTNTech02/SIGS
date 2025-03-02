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
            <div class="main-content">
                <div class="row">
                    <!-- Profile Picture Section -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header text-center">Profile Picture</div>
                            <div class="card-body text-center">
                                <img src="../Assets/img/profile_pictures/userdefaultprofile.jpg" class="img-fluid mb-3" alt="Profile Picture">
                                <p>Upload your profile picture here</p>
                                <input type="file" class="form-control-file">
                                <button class="btn btn-outline-secondary mt-3">Update Email or Password</button>
                            </div>
                        </div>
                    </div>

                    <!-- Information Section -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">Information</div>
                            <div class="card-body">
                                <form>
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
                                            <label for="username">Email Address</label>
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
                                    <button type="submit" class="btn btn-success">Update Information</button>
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
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>