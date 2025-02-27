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
                        <div class="col-3 mb-3">
                            <div class="border rounded p-4 text-center">
                                <p class="m-0 fw-bold">
                                <?php
                                    $sql = "SELECT 
                                    subjects_tbl.subject_id, 
                                    subjects_tbl.subject_name, 
                                    strands_tbl.strand_nn, 
                                    year_levels_tbl.yl_name, 
                                    sections_tbl.section_name, 
                                    subjects_tbl.subject_status 
                                    FROM subjects_tbl
                                    INNER JOIN strands_tbl ON strands_tbl.strand_id = subjects_tbl.subject_id
                                    INNER JOIN year_levels_tbl ON year_levels_tbl.year_level_id = subjects_tbl.subject_id
                                    INNER JOIN sections_tbl ON sections_tbl.section_id = subjects_tbl.subject_id";
                                    try
                                    {
                                        $result=$conn->prepare($sql);
                                        $result->execute();
                                        // $status = $_SESSION['status'];
                                        if($result->rowcount()>0)
                                        {
                                            $i=1;
                                            while($row=$result->fetch(PDO::FETCH_ASSOC))
                                            {
                                                echo "
                                                    <tr>
                                                        <td>{$row["yl_name"]} {$row["strand_nn"]} <br>{$row["section_name"]}</td>
                                                        ";
                                                        ?>
                                                    </tr>
                                                <?php
                                                $i++;
                                            }
                                        }
                                        else
                                        {
                                            echo "<tr><tdv colspan = '6'> No records found. </td></tr>";
                                        }
                                    }
                                    catch(Exception $e)
                                    {
                                        echo "Unexpected error has been occured!" . $e ->getMessage();
                                    }
                                ?>
                                </p>
                                <a href="students.php" class="text-success">View</a>
                            </div>
                        </div>
                        <div class="col-3 mb-3">
                            <div class="border rounded p-4 text-center">
                                <p class="m-0 fw-bold">Section Name</p>
                                <a href="#" class="text-success">View</a>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="border rounded p-4 text-center">
                                <p class="m-0 fw-bold">Section Name</p>
                                <a href="#" class="text-success">View</a>
                            </div>
                        </div>
                        <div class="col-3">
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