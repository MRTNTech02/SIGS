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
        $role = $_POST["role"];
    
        $sql = "UPDATE users_tbl SET 
            id_number = :id_number, 
            u_fname = :u_fname, 
            u_mname = :u_mname, 
            u_lname = :u_lname, 
            u_suffix = :u_suffix, 
            u_sex = :u_sex, 
            u_birthdate = :u_birthdate, 
            user_email = :user_email, 
            role = :role
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
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':user_id', $user_id);
    
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                header("Location: user_management.php");
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
    <title>Edit User</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../Bootstrap/css/bootstrap.min.css">
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
        <div class="content p-4 w-100">
            <h4 class="text-muted mb-4">Edit User</h4>
            <div class="main-content">
                <div class="card">
                    <div class="card-body">
                        <form action="EditUser.php" method="post">
                            <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="u_fname">First Name</label>
                                    <input type="text" class="form-control" id="u_fname" name="u_fname" value="<?php echo $u_fname ?>" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="u_mname">Middle Name</label>
                                    <input type="text" class="form-control" id="u_mname" name="u_mname" value="<?php echo $u_mname ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="u_lname">Last Name</label>
                                    <input type="text" class="form-control" id="u_lname" name="u_lname" value="<?php echo $u_lname?>" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="u_suffix">Suffix</label>
                                    <input type="text" class="form-control" id="u_suffix" name="u_suffix" value="<?php echo $u_suffix?>">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="id_number">ID Number</label>
                                    <input type="text" class="form-control" id="id_number" name="id_number" value="<?php echo $id_number ?>" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="user_email">Email</label>
                                    <input type="email" class="form-control" id="user_email" name="user_email" value="<?php echo $user_email ?>" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="role">Role</label>
                                    <select id="role" name="role" class="form-control" required>
                                        <option value="" disabled>Select Role</option>
                                        <option value="Registrar" <?php echo ($role == "Registrar") ? "selected" : ""; ?>>Registrar</option>
                                        <option value="Faculty" <?php echo ($role == "Faculty") ? "selected" : ""; ?>>Faculty</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="u_sex">Sex</label>
                                    <select id="u_sex" name="u_sex" class="form-control" required>
                                        <option value="" disabled>Select Sex</option>
                                        <option value="Male" <?php echo ($u_sex == "Male") ? "selected" : ""; ?>>Male</option>
                                        <option value="Female" <?php echo ($u_sex == "Female") ? "selected" : ""; ?>>Female</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="u_birthdate">Birthdate</label>
                                    <input type="date" class="form-control" id="u_birthdate" name="u_birthdate" value="<?php echo $u_birthdate ?>" required>
                                </div>
                            </div>
                            <button type="submit" name="saveEdit" id="saveEdit" class="btn btn-success">Save Changes</button>
                            <a href="user_management.php" class="btn btn-secondary"> Cancel </a>
                        </form>
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
