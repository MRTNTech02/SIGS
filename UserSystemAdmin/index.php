<?php 
  session_start();
  include ("../server_connection/db_connect.php");
  $errors = array();
  $_SESSION['success'] = "";

  if(isset($_POST['submit'])){
    $username = $_POST["username"];
    $a_password = $_POST["a_password"];

    if(empty($username)){
      array_push($errors, "Username is required");
    }
    if(empty($a_password)){
      array_push($errors, "Password is required");
    }

    if (count($errors) == 0){
      $sql = "SELECT * FROM admin_tbl WHERE username=:username AND a_password=:a_password";
      $result = $conn->prepare($sql);
      $result->bindParam(':username', $username, PDO::PARAM_STR);
      $result->bindParam(':a_password', $a_password, PDO::PARAM_STR);
      $result->execute();

      if($result->rowCount() == 1){
        $data = $result->fetch(PDO::FETCH_ASSOC);

        // Store in session
        $_SESSION['username'] = $data['username'];
        $_SESSION['a_password'] = $data['a_password'];
        $_SESSION['admin_id'] = $data['admin_id'];

        // Define admin_id before using it
        $admin_id = $data['admin_id'];

        try {
          // Log login event for admins
          $log_stmt = $conn->prepare("INSERT INTO user_logs_tbl (fk_admin_id, role, action) VALUES (:admin_id, 'Admin', 'Login')");
          $log_stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
          $log_stmt->execute();
        } catch (PDOException $e) {
          die("Error in executing statement: " . $e->getMessage());
        }

        header("location: dashboard.php");
        exit();
      } else {
        array_push($errors, "Username or password incorrect");
      }
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>SIGS</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="../Bootstrap/css/bootstrap.min.css">
</head>
<body>
    <div class="container" align="center">
        <div class="login-form" style="height: 100vh;">
            <!-- Login Form Card -->
            <div class="card shadow" style="width: 100%; max-width: 500px;">
                <div class="card-header bg-success text-white">
                    <h5 align="left">
                        <img src="../Assets/img/ASHS_logo.png" alt="ASHS Logo" width="10%"> 
                        Student Information and Grading System
                    </h5>
                </div>
                <div class="card-body">
                    <h1 class="card-subtitle mb-3" align="left">Log In</h1>
                    <form action = "index.php" method = "post">
                        <div class="mb-4" align="left">
                            <label for="username" class="form-label">Username</label>
                            <input type="username" class="form-control" id="username" name="username" placeholder="Enter username" required>
                        </div>
                        <div class="mb-4" align="left">
                            <label for="a_password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="a_password" name="a_password" placeholder="Enter password" required>
                        </div>
                        <input type="submit" value="Login" id="submit" name="submit" class="btn btn-success w-100">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Javascript -->
    <script src="../Bootstrap/js/bootstrap.bundle.min.js"></script>
    <style>
        .container{
            margin-top: 10%;
        }
    </style>
</body>
</html>