

<!DOCTYPE html>
<html lang="en">
<head>
  <title>SIGS</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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

        <!-- Profile Dropdown -->
        <div class="dropdown">
          <button class="btn bg-transparent border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false=">
            <i class="fas fa-user-circle"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="user_profile.php">Profile</a></li>
            <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</a></li>
          </ul>
        </div>

      </div>
    </div>
  </nav>

  <!-- Logout Modal -->
  <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="logoutModalLabel">Ready to Leave?</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Select "Logout" below if you are ready to end your current session.
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="index.php">Logout</a>
        </div>
      </div>
    </div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
<!-- Javascript for Poppers-->
<script src="../Bootstrap/js/bootstrap.bundle.min.js"></script> 
