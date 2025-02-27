<!DOCTYPE html>
<html lang="en">
<head>
  <title>SIGS</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap -->
  <link rel="stylesheet" href="../../Bootstrap/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script defer src="../Bootstrap/js/bootstrap.bundle.min.js"></script>
</head>
<body>
  <nav class="navbar bg-success" data-bs-theme="dark">
    <div class="container-fluid">
      <!-- Logo and Title -->
      <a class="navbar-brand d-flex align-items-center">
        <img src="../Assets/img/ASHS_logo.png" alt="ASHS logo" width="30px">
        <h5 class="mb-0 ms-2">Student Information and Grading System</h5>
      </a>
  
      <!-- Right-aligned icons -->
      <div class="d-flex align-items-center">
        <!-- Notification Dropdown -->
        <div class="dropdown me-2">
          <button class="btn bg-transparent border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-bell"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="#">New notifications</a></li>
            <li><a class="dropdown-item" href="#">Mark all as read</a></li>
          </ul>
        </div>
  
        <!-- Profile Dropdown -->
        <div class="dropdown">
          <button class="btn bg-transparent border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false=">
            <i class="fas fa-user-circle"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="user_profile.php">Profile</a></li>
            <li><a class="dropdown-item" href="index.php">Logout</a></li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
</body>
</html>
<!-- Javascript for Poppers-->
<script src="../Bootstrap/js/bootstrap.bundle.min.js"></script> 
