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
        $admin_name = $data['a_fname'] . ' ' . $data['a_lname'];
        $username = $data['username'];
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

<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
        try {
            $id_number = $_POST["id_number"];
            $u_fname = $_POST["u_fname"];
            $u_mname = $_POST["u_mname"];
            $u_lname = $_POST["u_lname"];
            $u_suffix = $_POST["u_suffix"];
            $u_sex = $_POST["u_sex"];
            $u_birthdate = $_POST["u_birthdate"];
            $user_email = $_POST["user_email"];
            $role = $_POST["role"];
            $user_password = $_POST["user_password"];
            $user_profile = $_POST["user_profile"];
            $user_status = $_POST["user_status"];

            if (empty($id_number) || empty($u_fname) || empty($u_lname) || empty($user_email) || empty($role) || empty($user_password)) {
                throw new Exception("Required fields are missing.");
            }

            $sql = "INSERT INTO users_tbl (id_number, u_fname, u_mname, u_lname, u_suffix, u_sex, u_birthdate, user_email, role, user_password, user_profile, user_status) 
            VALUES (:id_number, :u_fname, :u_mname, :u_lname, :u_suffix, :u_sex, :u_birthdate, :user_email, :role, :user_password, :user_profile, :user_status)";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ":id_number" => $id_number,
                ":u_fname" => $u_fname,
                ":u_mname" => $u_mname,
                ":u_lname" => $u_lname,
                ":u_suffix" => $u_suffix,
                ":u_sex" => $u_sex,
                ":u_birthdate" => $u_birthdate,
                ":user_email" => $user_email,
                ":role" => $role,
                ":user_password" => $user_password,
                ":user_profile" => $user_profile,
                ":user_status" => $user_status,
            ]);

            echo "<script>
                alert('New Student Added Successfully!');
                window.location.href = 'students.php';
            </script>";
            exit();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../Bootstrap/css/bootstrap.min.css">
</head>
<body>
    <?php
        include '../Assets/components/Navbar.php';
    ?>
    <div class="d-flex">
        <?php
            include '../Assets/components/FacultySidebar.php';
        ?>
        <!-- Main content -->
        <div class="content p-4 w-100">
            <h4 class="text-muted mb-4">Add New User</h4>
            <div class="main-content">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="AddUser.php">
                            <input type="hidden" id="user_status" name="user_status" value="Active">
                            <input type="hidden" id="user_profile" name="user_profile" value="userdefaultprofile.jpg">
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="u_fname">First Name</label>
                                    <input type="text" class="form-control" id="u_fname" name="u_fname" placeholder="First Name" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="u_mname">Middle Name</label>
                                    <input type="text" class="form-control" id="u_mname" name="u_mname" placeholder="Middle Name">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="u_lname">Last Name</label>
                                    <input type="text" class="form-control" id="u_lname" name="u_lname" placeholder="Last Name" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="u_suffix">Suffix</label>
                                    <input type="text" class="form-control" id="u_suffix" name="u_suffix" placeholder="Suffix (Optional)">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="id_number">Learner's Reference Number (LRN)</label>
                                    <input type="text" class="form-control" id="id_number" name="id_number" placeholder="ID Number" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="user_email">Email</label>
                                    <input type="email" class="form-control" id="user_email" name="user_email" placeholder="Email" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="u_sex">Sex</label>
                                    <select id="u_sex" name="u_sex" class="form-control" required>
                                        <option value="" disabled selected>Select Sex</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="u_birthdate">Birthdate</label>
                                    <input type="date" class="form-control" id="u_birthdate" name="u_birthdate" required>
                                </div>
                            </div>
                            <button type="submit" name="register" id="register" class="btn btn-success">Create User</button>
                            <a href="students.php" class="btn btn-secondary"> Cancel </a>
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
