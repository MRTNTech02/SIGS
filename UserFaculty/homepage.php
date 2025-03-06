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
            INNER JOIN users_tbl As E ON FA.fk_user_id=E.user_id WHERE FA.fk_user_id='$user_id'"; // Group by section to avoid duplicates

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
    <title>Homepage</title>
</head>
<body>
    <?php
        include '../Assets/components/FacultyNavbar.php';
    ?>
    <div class="d-flex">
        <?php
            include '../Assets/components/FacultySidebar.php';
        ?>
        <!-- Main content -->
        <div class="content p-4 flex-grow-1">
            <h4 class="text-muted">Dashboard</h4>
            <div class="main-content">
                <div class="rowr">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-3">
                    <div class="box d-flex flex-column justify-content-center align-items-center p-4 border border-success rounded" style="min-height: 100px; min-width: 100%;">
                        <div class="text-success text-bold fw-bold mb-2">Students</div>
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
                </div>
                
                <div class="row justify-content-center">
                <div class="col-lg-6 mb-3">
                        <div class="box-2 p-3 border border-success rounded">
                            <div class="text-success fw-bold mb-2">Reported Bugs</div>
                            <div class="table-responsive" style="height: 40vh; overflow-y: auto;">
                                <table class="table table-striped">
                                    <thead class="text-center">
                                        <tr>
                                            <th>No.</th>
                                            <th>Ticket Number</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        <?php
                                            $sql = "SELECT * FROM bugs_tbl WHERE fk_user_id=:user_id AND bug_status = 'Open' ORDER BY insrt_ts DESC";
                                            try {
                                                $result = $conn->prepare($sql);
                                                $result->execute(['user_id' => $user_id]);
                                                $i = 1;
                                                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                                    echo "<tr>
                                                            <td>{$i}</td>
                                                            <td>{$row["bug_ticket"]}</td>
                                                            <td>{$row["bug_status"]}</td>
                                                            <td>
                                                                <a href='ViewTicket.php?bug_id={$row["bug_id"]}' class='btn btn-info btn-sm'>
                                                                    <i class='fas fa-eye'></i>
                                                                </a>
                                                            </td>
                                                          </tr>";
                                                    $i++;
                                                }
                                                if ($i === 1) {
                                                    echo "<tr><td colspan='4'>No records found.</td></tr>";
                                                }
                                            } catch (Exception $e) {
                                                echo "Error: " . $e->getMessage();
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-6 mb-3">
                        <div class="box d-flex flex-column p-3 border border-success rounded" style="min-height: 150px; min-width: 100%;">
                        <div class="text-success fw-bold mb-2">Assigned Sections</div>
                    <div class="row">
                    <div class="table-responsive" style="height: 40vh; overflow-y: auto;">
                        <table class="table table-striped">
                            <thead class="text-center">
                                <tr>
                                    <th>Subject</th>
                                    <th>Year & Strand</th>
                                    <th>Section</th>
                                    <!-- <th>Action</th> -->
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php
                                if (!empty($sections)) {
                                    foreach ($sections as $row) {
                                        echo "
                                                <tr>
                                                
                                                    <td>{$row["subject_name"]}</td>
                                                    <td>{$row["yl_name"]} - {$row["strand_nn"]}</td>
                                                    <td>{$row["section_name"]}</td>
                                            ";
                                            ?>
                                            <td class='text-center'>
                                                <?php 
                                                ;
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

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>