<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="../../Bootstrap/css/bootstrap.min.css">
    <!-- Bootstrap Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
    <body>
        <div class="d-flex">
            <nav id="sidebar" class="sidebar bg-light vh-100 p-2">
                <i class="fas fa-columns" id="toggleSidebar" style="float: right;"></i>
                </br>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="homepage.php" class="nav-link text-dark" data-menu="homepage">
                            <i class="fas fa-home"></i> <span>Homepage</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link text-dark" data-menu="students">
                            <i class="fas fa-user"></i> <span>Students</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link text-dark" data-menu="grades">
                            <i class="fas fa-file-text"></i> <span>Grade Submission</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="#" class="nav-link text-dark" data-menu="bug">
                            <i class="fas fa-bug"></i> <span>Report a Bug</span>
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
        border-right: solid green;
    }
    .sidebar.collapsed {
        width: 70px;
    }
    .sidebar.collapsed .nav-link span {
        display: none;
    }
    .sidebar .nav-link.active {
        background-color: green !important;
        color: white !important;
    }
</style>