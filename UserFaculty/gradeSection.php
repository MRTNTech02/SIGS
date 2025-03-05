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
            $user_id = htmlspecialchars($data['user_id']);
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
        $sql = "SELECT FA.f_assignment_id, A.subject_name, A.subject_status, B.yl_name, C.strand_nn, D.section_name, D.section_id FROM
                faculty_assignments_tbl AS FA INNER JOIN subjects_tbl AS A ON FA.fk_subject_id=A.subject_id
                INNER JOIN year_levels_tbl AS B ON FA.fk_year_id=B.year_level_id 
                INNER JOIN strands_tbl AS C ON FA.fk_strand_id=C.strand_id 
                INNER JOIN sections_tbl AS D ON FA.fk_section_id=section_id 
                INNER JOIN users_tbl As E ON FA.fk_user_id=E.user_id WHERE FA.fk_user_id='$user_id'";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        die("Connection failed: " . $e->getMessage());
    }
?>
<!-- count my assigned subjects -->
<?php 
    try {
        $sql_count = "SELECT COUNT(*) AS subject_count FROM faculty_assignments_tbl 
                        WHERE fk_user_id = :user_id";
        $stmt_count = $conn->prepare($sql_count);
        $stmt_count->bindParam(':user_id', $user_id);
        $stmt_count->execute();
        $subject_count = $stmt_count->fetch(PDO::FETCH_ASSOC)['subject_count'];
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Assigned Sections</title>
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
            <h4 class="text-muted mb-3">My Assigned Subjects <i class="fa fa-angle-right"></i>
                <?php echo $subject_count; ?>
            </h4>
            <div class="main-content">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-3">
                    <div class="box d-flex flex-column p-4 border border-success rounded" style="min-height: 150px; min-width: 100%;">
                    <div class="text-success mb-3">Assigned Sections</div>
                    <div class="row">
                <div class="table-responsive">
                        <table class="table" id="studentsTable">
                            <thead align="center">
                                <tr>
                                    <th>Subject</th>
                                    <th>Grade Level</th>
                                    <th>Strand</th>
                                    <th>Section</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody" align="center">
                    
                    <?php
                    if (!empty($sections)) {
                        foreach ($sections as $row) {
                            echo "
                                    <tr>
                                    
                                        <td>{$row["subject_name"]}</td>
                                        <td>{$row["yl_name"]}</td>
                                        <td>{$row["section_name"]}</td>
                                        <td>{$row["strand_nn"]}</td>
                                        <td>{$row["subject_status"]}</td>
                                  ";
                                  ?>
                                  <td class='text-center'>
                                    <?php 
                                    echo "
                                    <a href='students.php?f_assignment_id={$row["f_assignment_id"]}' class='btn btn-info btn-sm'>
                                        <i class='fas fa-eye'></i>
                                    </a>
                                    ";
                        }
                    } else {
                        echo "<p class='text-center'>No sections found.</p>";
                    }
                    ?>
                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<script src="../Bootstrap/js/bootstrap.bundle.min.js"></script> 