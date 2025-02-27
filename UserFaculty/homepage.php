<?php 
    session_start();
    include ("../server_connection/db_connect.php");

    // Ensure user is logged in
    if (!isset($_SESSION["id_number"]) || !isset($_SESSION["user_id"])) 
    {
        header("location: index.php");
    }

    $user_id = $_SESSION["user_id"];

    $sql = "SELECT * FROM users_tbl WHERE user_id = :user_id";
    try {
        $result = $conn->prepare($sql);
        $result->bindParam(':user_id', $user_id);
        $result->execute();

        if($result->rowCount() > 0){
            $data = $result->fetch(PDO::FETCH_ASSOC);
            $user_name = htmlspecialchars($data['u_fname'] . ' ' . $data['u_lname']);  
            $id_number = htmlspecialchars($data['id_number']);
        }
    } 
    catch(Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    // Logout logic
    if (isset($_GET['logout'])) {
        session_destroy();
        unset($_SESSION['id_number']);
        unset($_SESSION['user_id']);
        header("location: index.php");
        exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
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
        <div class="content p-4 flex-grow-1">
            <h4 class="text-muted">Dashboard</h4>
            <div class="main-content">
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 mb-3">
                        <div class="box d-flex flex-column justify-content-top align-items-center p-4 border border-success rounded" style="min-height: 150px; min-width: 100%;">
                            <div class="text-success text-bold mb-2">Students</div>
                            <span class="h2 text-dark mt-2">
                            <?php 
                                    $sql = "SELECT count(*) countStudents FROM students_tbl WHERE s_status = 'Active'";

                                    try {
                                        $result = $conn->prepare($sql);
                                        $result->execute();
                                        $row = $result->fetch(PDO::FETCH_ASSOC); 

                                        if ($row) {
                                            echo "{$row['countStudents']}";
                                        }
                                    } catch (Exception $e) {
                                        echo "Unexpected error has occurred! " . $e->getMessage();
                                    }
                                ?>
                            </span> 
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 mb-3">
                        <div class="box d-flex flex-column justify-content-center align-items-center p-3 border border-success rounded" style="min-height: 150px; min-width: 100%;">
                            <div class="text-success text-bold mb-2">Grade Submission</div>
                            <!-- Circular Progress Bar -->
                            <div class="position-relative">
                                <svg width="80" height="80">
                                    <circle cx="40" cy="40" r="30" stroke="#e0e0e0" stroke-width="5" fill="none"/>
                                    <circle cx="40" cy="40" r="30" stroke="green" stroke-width="5" fill="none" stroke-dasharray="189" stroke-dashoffset="-95"/>
                                </svg>
                                <div class="position-absolute top-50 start-50 translate-middle fw-bold text-dark">50%</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-3">
                    <div class="box d-flex flex-column p-4 border border-success rounded" style="min-height: 150px; min-width: 100%;">
                    <div class="text-success mb-3">Assigned Sections</div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="border rounded p-4 text-center">
                                <p class="m-0 fw-bold">Section Name</p>
                                <a href="#" class="text-success">View</a>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-4 text-center">
                                <p class="m-0 fw-bold">Section Name</p>
                                <a href="#" class="text-success">View</a>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-4 text-center">
                                <p class="m-0 fw-bold">Section Name</p>
                                <a href="#" class="text-success">View</a>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-4 text-center">
                                <p class="m-0 fw-bold">Section Name</p>
                                <a href="#" class="text-success">View</a>
                            </div>
                        </div>
                    </div>
                    </div>
            </div>
        </div>
    </div>
</body>
</html>