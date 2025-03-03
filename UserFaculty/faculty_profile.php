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
        $raculty_name = $data['u_fname'] . ' ' . $data['u_lname'] . ' ' . $data['u_suffix'] ;
        $id_number = $data['id_number'];
        $user_profile = $data['user_profile'];
        $u_fname = $data['u_fname'];
        $u_mname = $data['u_mname'];
        $u_lname = $data['u_lname'];
        $u_suffix = $data['u_suffix'];
        $u_sex = $data['u_sex'];
        $user_email = $data['user_email'];
        $u_birthdate = $data['u_birthdate'];
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
<!-- PHP code for editing user -->
<?php 
    if (isset($_POST['saveEdit'])) {
        $user_id = $_POST["user_id"];
        $id_number = $_POST["id_number"];
        $u_fname = $_POST["u_fname"];
        $u_mname = $_POST["u_mname"];
        $u_lname = $_POST["u_lname"];
        $u_suffix = $_POST["u_suffix"];
        $u_sex = $_POST["u_sex"];
        $u_birthdate = $_POST["u_birthdate"];
        $user_email = $_POST["user_email"];
    
        $sql = "UPDATE users_tbl SET 
            id_number = :id_number, 
            u_fname = :u_fname, 
            u_mname = :u_mname, 
            u_lname = :u_lname, 
            u_suffix = :u_suffix, 
            u_sex = :u_sex, 
            u_birthdate = :u_birthdate, 
            user_email = :user_email
            WHERE user_id = :user_id";
    
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_number', $id_number);
            $stmt->bindParam(':u_fname', $u_fname);
            $stmt->bindParam(':u_mname', $u_mname);
            $stmt->bindParam(':u_lname', $u_lname);
            $stmt->bindParam(':u_suffix', $u_suffix);
            $stmt->bindParam(':u_sex', $u_sex);
            $stmt->bindParam(':u_birthdate', $u_birthdate);
            $stmt->bindParam(':user_email', $user_email);
            $stmt->bindParam(':user_id', $user_id);
    
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                echo "<script>
                    alert('Profile Updated Successfully!');
                    window.location.href = 'faculty_profile.php';
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
        include '../Assets/components/FacultyNavbar.php';
    ?>
    <div class="d-flex">
        <?php
            include '../Assets/components/FacultySidebar.php';
        ?>
        <!-- Main content -->
        <div class="content p-4">
            <h4 class="text-muted">Profile</h4>
            <!-- Navigation Buttons -->
            <div class="d-flex justify-content-start mb-3">
                <a href="faculty_profile.php" class="btn btn-success me-2">Information</a>
                <a href="change_password.php" class="btn btn-outline-dark me-2">Change Password</a>
            </div>
            <div class="main-content">
                <div class="row">
                    <!-- Profile Picture Section -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header text-center">Profile Picture</div>
                            <div class="card-body text-center">
                                <img src="../Assets/img/profile_pictures/<?php echo $user_profile ?>" class="avatar img-fluid mb-3" alt="Profile Picture">
                                <form method="post" action="EditProfilePicture.php?user_id=<?php echo $user_id ?>" enctype="multipart/form-data">
                                    <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id?>">
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="user_profile">Upload your profile picture here</label>
                                            <input type="file" class="form-control" id="user_profile" name="user_profile" accept="image/png/jpg" required>
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
                                <form action="faculty_profile.php" method="post">
                                    <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label for="u_fname">First Name</label>
                                            <input type="text" class="form-control" name="u_fname" id="u_fname" value="<?php echo $u_fname ?>">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="u_mname">Middle Name</label>
                                            <input type="text" class="form-control" name="u_mname" id="u_mname" value="<?php echo $u_mname ?>">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="u_lname">Last Name</label>
                                            <input type="text" class="form-control" id="u_lname" name="u_lname" value="<?php echo $u_lname ?>">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="u_suffix">Suffix</label>
                                            <input type="text" class="form-control" id="u_suffix" name="u_suffix" value="<?php echo $u_suffix ?>">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="id_number">ID Number</label>
                                            <input type="text" class="form-control" id="id_number" name="id_number" value="<?php echo $id_number?>">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="user_email">Email Address</label>
                                            <input type="email" class="form-control" id="user_email" name="user_email" value="<?php echo $user_email?>">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="u_sex">Sex</label>
                                            <select class="form-control" id="u_sex" name="u_sex">
                                                <option value="Male" <?= $u_sex == 'Male' ? 'selected' : '' ?>>Male</option>
                                                <option value="Female" <?= $u_sex == 'Female' ? 'selected' : '' ?>>Female</option>
                    
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="u_birthdate">Birthdate</label>
                                            <input type="date" class="form-control" id="u_birthdate" name="u_birthdate" value="<?php echo $u_birthdate?>">
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