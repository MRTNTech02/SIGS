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
                    <form>
                        <div class="mb-4" align="left">
                            <label for="username" class="form-label">Username</label>
                            <input type="username" class="form-control" id="username" placeholder="Enter username" required>
                        </div>
                        <div class="mb-4" align="left">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" placeholder="Enter password" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Login</button>
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