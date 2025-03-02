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
<?php
    try {
        $sql = "SELECT SC.assignment_id, A.lrn_number, A.s_fname, A.s_lname, A.s_suffix, A.s_status,
                B.yl_name, C.strand_nn, C.strand_id, D.section_name, D.section_id
                FROM sc_assignments_tbl AS SC
                INNER JOIN students_tbl AS A ON SC.fk_student_id = A.student_id
                INNER JOIN year_levels_tbl AS B ON SC.fk_year_id = B.year_level_id
                INNER JOIN strands_tbl AS C ON SC.fk_strand_id = C.strand_id
                INNER JOIN sections_tbl AS D ON SC.fk_section_id = D.section_id
                GROUP BY D.section_id"; // Group by section to avoid duplicates

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        die("Connection failed: " . $e->getMessage());
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Year & Section</title>
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
            <h4 class="text-muted mb-3">Year & Section</h4>
            <div class="main-content">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-3">
                    <div class="box d-flex flex-column p-4 border border-success rounded" style="min-height: 150px; min-width: 100%;">
                    <div class="text-success mb-3">Assigned Sections</div>
                    <div class="row">
                    <?php
                    if (!empty($sections)) {
                        foreach ($sections as $section) {
                            echo "<div class='col-md-3 mb-3'>
                                    <div class='card-custom border'>
                                    <div class='border rounded p-4 text-center'>
                                        <h5 class='fw-bold'>{$section["yl_name"]} {$section["strand_nn"]}</h5>
                                        <p>{$section["section_name"]}</p>
                                        <a href='students.php?section_id={$section["section_id"]}' class='text-success'>View</a>
                                    </div>
                                    </div>
                                </div>";
                        }
                    } else {
                        echo "<p class='text-center'>No sections found.</p>";
                    }
                    ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>