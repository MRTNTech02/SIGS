<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <?php
        include '../Assets/components/Navbar.php';
    ?>
    <div class="d-flex">
        <?php
            include '../Assets/components/AdminSidebar.php';
        ?>
        <!-- Main content -->
        <div class="content p-4">
            <h4 class="text-muted">Dashboard</h4>
            <div class="main-content">
                <div class="row">
                    <div class="col-12 col-sm-4 col-md-4 col-lg-4 mb-3 ">
                        <div class="box d-flex flex-column justify-content-center align-items-center p-4 border border-success rounded" style="min-height: 150px; min-width: 300px;">
                            <div class="text-success text-bold mb-2">Students</div>
                            <span class="h4 text-dark">456</span> 
                        </div>
                    </div>
                    <div class="col-12 col-sm-4 col-md-4 col-lg-4 mb-3">
                        <div class="box d-flex flex-column justify-content-center align-items-center p-4 border border-success rounded" style="min-height: 150px; min-width: 300px;">
                            <div class="text-success text-bold mb-2">Faculty</div>
                            <span class="h4 text-dark">45</span>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4 col-md-4 col-lg-4 mb-3">
                        <div class="box d-flex flex-column justify-content-center align-items-center p-4 border border-success rounded" style="min-height: 150px; min-width: 300px;">
                            <div class="text-success text-bold mb-2">Registrar</div> 
                            <span class="h4 text-dark">56</span> 
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-sm-4 col-md-4 col-lg-4 mb-3">
                        <div class="box d-flex flex-column p-4 border border-success rounded" style="min-height: 350px; min-width: 150px;">
                            <div class="text-success text-bold mb-2">Bug Reports</div> 
                            <span class="h4 text-dark">56</span> 
                        </div>
                    </div>
                    <div class="col-12 col-sm-8 col-md-8 col-lg-8 mb-3">
                        <div class="box d-flex flex-column p-4 border border-success rounded" style="min-height: 350px; width: 100%;">
                            <div class="text-success text-bold mb-2">User Logs</div> 
                            <span class="h4 text-dark">56</span> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>