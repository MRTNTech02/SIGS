<?php 
  session_start();
  include ("../server_connection/db_connect.php");
  $errors = array();
  $_SESSION['success'] ="";
  if(isset($_POST['submit'])){
    $id_number = $_POST["id_number"];
    $user_password = $_POST["user_password"];

    if(empty($id_number)){
      array_push($errors, "Faculty ID is required");
    }
    if(empty($user_password)){
      array_push($errors, "Password is required");
    }

    if (empty($errors)){
        $sql = "SELECT * FROM users_tbl WHERE id_number = :id_number AND user_password = :user_password";
        $result = $conn->prepare($sql);
        #bindParam() for security
        $result->bindParam(':id_number', $id_number);
        $result->bindParam(':user_password', $user_password);
        $result->execute();

        if($result->rowCount() == 1){
            $data = $result->fetch(PDO::FETCH_ASSOC);
            $_SESSION['id_number'] = $data['id_number'];
            $_SESSION['user_id'] = $data['user_id'];  

            header("location: homepage.php");
        } else {
            $errors[] = "ID Number or password incorrect";
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
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <style>
        .login-form{
            margin-top: 20%;
        }
        .left-wing{
            margin-top: 5%;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-6">
                <div class="left-wing" align="center">
                    <img src="../Assets/img/ASHS_logo.png" alt="ASHS Logo" width="70%"> 
                    <h6 class="text-success">Aurora Senior High School</h6>
                    <h4 class="text-success"><b>Student Information and Grading System</b></h4>
                </div>
            </div>
            <div class="col-6">
                <div class="login-form" style="height: 100vh;">
                    <!-- Login Form  -->
                    <div style="width: 100%; max-width: 500px;">
                        <div>
                            <h1 class="card-subtitle mb-3" align="left">Log In</h1>
                            <form method="POST" action="">
                                <div class="mb-5" align="left">
                                    <label for="id_number" class="form-label">Faculty ID Number</label>
                                    <input type="id_number" class="form-control" id="id_number" name="id_number" placeholder="Enter ID number" required>
                                </div>
                                <div class="mb-5" align="left">
                                    <label for="user_password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="user_password" name="user_password" placeholder="Enter password" required>
                                </div>
                                <button type="submit" name="submit" class="btn btn-success w-100">Login</button>
                            </form>
                            <?php if (!empty($errors)): ?>
                                <div class="alert alert-danger mt-3">
                                    <?php foreach ($errors as $error) { echo "<p>$error</p>"; } ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>