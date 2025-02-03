<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="../../Bootstrap/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
    <body>
        <div class="d-flex">
            <nav id="sidebar" class="sidebar bg-light vh-100 p-2">
                <i class="bi bi-house-door" id="toggleSidebar"></i>
                <!-- <button class="btn btn-primary w-100 mb-3" id="toggleSidebar">Toggle</button> -->
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link text-dark" data-menu="dashboard">
                            <i class="bi bi-house-door"></i> <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="user_management.php" class="nav-link text-dark" data-menu="users">
                            <i class="bi bi-info-circle"></i> <span>User Management</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link text-dark" data-menu="logs">
                            <i class="bi bi-gear"></i> <span>Log Monitoring</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link text-dark" data-menu="maintenance">
                            <i class="bi bi-envelope"></i> <span>Maintenance</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link text-dark" data-menu="support">
                            <i class="bi bi-gear"></i> <span>User Support</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </body>
</html>
<!-- js for toggling -->
<script>
    document.getElementById("toggleSidebar").addEventListener("click", function() {
        document.getElementById("sidebar").classList.toggle("collapsed");
    });
        
    document.querySelectorAll(".nav-link").forEach(item => {
        item.addEventListener("click", function() {
            document.querySelectorAll(".nav-link").forEach(link => link.classList.remove("active"));
            this.classList.add("active");
        });
    });
</script>
<!-- Javascript for Poppers-->
<script src="../Bootstrap/js/bootstrap.bundle.min.js"></script>
<style>
    .sidebar {
        width: 250px;
        transition: width 0.3s;
    }
    .sidebar.collapsed {
        width: 80px;
    }
    .sidebar.collapsed .nav-link span {
        display: none;
    }
    .sidebar .nav-link.active {
        background-color: green !important;
        color: white !important;
    }
</style>