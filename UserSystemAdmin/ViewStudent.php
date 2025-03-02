<?php 
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
?>

<?php 
  session_start();
  include ("../server_connection/db_connect.php");
  if (!isset($_SESSION["username"]) || !isset($_SESSION["a_password"])) {
    header("Location: index.php");
    exit();
    }
  if (!empty($_SESSION["username"])){
    $admin_id = $_SESSION["admin_id"];

    $sql = "SELECT * FROM admin_tbl WHERE admin_id = :admin_id";
    try{
        $result = $conn->prepare($sql);
        $result->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
        $result->execute();
        if($result->rowCount()>0){
            $data = $result->fetch(PDO::FETCH_ASSOC);
            $admin_name = $data['a_fname'] . ' ' . $data['a_lname'] ;
            $username = $data['username'];
        }
    }catch(Exception $e){
      echo "Error" . $e;
    }
  };
  if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: index.php");
    }
?>

<!-- fetching student record -->
<?php
    if (isset($_GET['assignment_id'])) {
        $assignment_id = $_GET['assignment_id'];

        $sql = "SELECT SC.assignment_id, A.student_id, A.lrn_number, A.s_fname, A.s_lname, A.s_mname, 
        A.s_suffix, A.s_status, B.yl_name, C.strand_nn, C.strand_name, D.section_name
        FROM sc_assignments_tbl AS SC 
        INNER JOIN students_tbl AS A ON SC.fk_student_id = A.student_id
        INNER JOIN year_levels_tbl AS B ON SC.fk_year_id = B.year_level_id
        INNER JOIN strands_tbl AS C ON SC.fk_strand_id = C.strand_id
        INNER JOIN sections_tbl AS D ON SC.fk_section_id = D.section_id
        WHERE SC.assignment_id = :assignment_id";
        
        try{
            $result = $conn->prepare($sql);
            $result->bindParam(':assignment_id', $assignment_id, PDO::PARAM_INT);
            $result->execute();

            if($result->rowCount()>0){
                $data = $result->fetch(PDO::FETCH_ASSOC);
                $lrn_number = $data["lrn_number"];
                $student_id = $data["student_id"];
                $s_fname = $data["s_fname"];
                $s_mname = $data["s_mname"];
                $s_lname = $data["s_lname"];
                $s_suffix = $data["s_suffix"];
                $student_fullname = $data["s_fname"] . " " . $data["s_lname"];
                $s_status = $data["s_status"];
                $s_year_level = $data["yl_name"];
                $s_strand = $data["strand_name"];
                $s_strand_nn = $data["strand_nn"];
                $s_section = $data["section_name"];
                $assignment_id = $data["assignment_id"];
            }
        }catch(Exception $e){
        echo "Error" . $e;
        }
    };
?>

<!-- Fetching Grades per Subject -->
<?php 
    // Set default semester and academic year (Modify this as needed)
    $default_semester = "1nd Semester";
    $num_semester = '1';
    $default_academic_year = "2024-2025";

    // Get selected semester and academic year from dropdown filters
    $semester = isset($_GET['semester']) ? $_GET['semester'] : $num_semester;
    $academic_year = isset($_GET['academic_year']) ? $_GET['academic_year'] : $default_academic_year;

    $sql = "SELECT stud.s_fname, stud.s_lname, stud.s_suffix, sub.subject_name, teach.u_fname, 
        teach.u_lname, teach.u_suffix, subs_t.semester, subs_t.academic_year, subs_t.fk_assignment_id,
        grade.student_grade 
        FROM subjects_taking_tbl AS subs_t
        LEFT JOIN student_grades_tbl AS grade ON subs_t.s_taking_id = grade.fk_student_subject_id 
        LEFT JOIN subjects_tbl AS sub ON subs_t.fk_subject_id = sub.subject_id
        LEFT JOIN faculty_assignments_tbl AS FA ON sub.subject_id=FA.fk_subject_id
        LEFT JOIN sc_assignments_tbl AS sc ON subs_t.fk_assignment_id = sc.assignment_id
        LEFT JOIN students_tbl AS stud ON sc.fk_student_id = stud.student_id 
        LEFT JOIN users_tbl AS teach ON FA.fk_user_id = teach.user_id
        WHERE stud.student_id = :student_id
        AND subs_t.semester = :semester
        AND subs_t.academic_year = :academic_year
        ";

        $result = $conn->prepare($sql);
        $result->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $result->bindParam(':semester', $semester, PDO::PARAM_STR);
        $result->bindParam(':academic_year', $academic_year, PDO::PARAM_STR);
        $result->execute();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../Bootstrap/css/bootstrap.min.css">
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
        <div class="content p-4 w-100">
            <h4 class="text-muted mb-4">Student Information</h4>
            <div class="main-content">
                <div class="container">
                        <div class="main">
                            <div class="profile-info-container">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="box2" style="margin-top: 10px; margin-bottom: 10px; height:auto; width: 100%">
                                            <img src='../Assets/img/profile_pictures/studentdefaultprofile.png' alt='else' class='avatar  mx-auto d-block'>
                                        </div> 
                                    </div>
                                    <div class="col-lg-7">
                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <h5>Full Name: <?php echo $student_fullname ?> </h5>
                                            </div>
                                            <div class="col-12 mb-3">
                                                <h5>LRN: <?php echo $lrn_number ?> </h5>
                                            </div>
                                            <div class="col-12 mb-3">
                                                <h5>Strand: <?php echo $s_strand . " (" . $s_strand_nn . ")"?> </h5>
                                            </div>
                                            <div class="col-12 mb-3">
                                                <h5>Grade Level: <?php echo $s_year_level ?> </h5>
                                            </div>
                                            <div class="col-12 mb-3">
                                                <h5>Section: <?php echo $s_section ?> </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="assigned-subjects">
                                <h5 class="text-success mb-2">Subjects Taking</h5>
                                <form method="GET">
                                    <input type="hidden" id="assignment_id" name="assignment_id" value="<?php echo $assignment_id; ?>">
                                    <input type="hidden" id="student_id" name="student_id" value="<?php echo $student_id; ?>">
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label for="semester">Select Semester:</label>
                                            <select name="semester" id="semester" class="form-control">
                                                <option value="1" <?php if ($semester == "1") echo "selected"; ?>>1st Semester</option>
                                                <option value="2" <?php if ($semester == "2") echo "selected"; ?>>2nd Semester</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="academic_year">Select Academic Year:</label>
                                            <select name="academic_year" id="academic_year" class="form-control">
                                                <option value="2023-2024" <?php if ($academic_year == "2023-2024") echo "selected"; ?>>2023-2024</option>
                                                <option value="2024-2025" <?php if ($academic_year == "2024-2025") echo "selected"; ?>>2024-2025</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <button type="submit" class="btn btn-success">Filter</button>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <a href="AssignSubjectStudent.php?assignment_id=<?php echo $assignment_id ?>" class="btn btn-primary">
                                                <i class="fas fa-plus"></i>
                                                Assign a Subject
                                            </a>
                                        </div>
                                    </div>
                                </form>
                                <div class="table-responsive">
                                    <table class="table table-striped" id="studentsTable">
                                        <thead align="center">
                                            <tr>
                                                <th>No.</th>
                                                <th>Subject</th>
                                                <th>Assigned Teacher</th>
                                                <th>Academic Year</th>
                                                <th>Semester</th>
                                                <th>Grade</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableBody" align="center">
                                            <?php if ($result->rowCount() > 0): 
                                                $i = 1;
                                                while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
                                                    <tr>
                                                        <td class='text-center'><?php echo $i; ?> </td>
                                                        <td><?php echo $row['subject_name']; ?></td>
                                                        <td><?php echo $row['u_fname'] . ' ' . $row['u_lname']. ' ' .$row['u_suffix']; ?></td>
                                                        <td><?php echo $row['academic_year']; ?></td>
                                                        <td><?php echo $row['semester']; ?></td>
                                                        <td><?php echo ($row['student_grade'] !== null) ? $row['student_grade'] : 'Pending from Instructor'; ?></td>
                                                    </tr>
                                                    <?php $i++; ?>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr><td colspan="6">No subjects found for the selected semester and academic year.</td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .table-container { 
            margin-top: 20px; 
            min-width: 100%; 
        }
        .table-responsive {
            height: 375px;
        }
        .content { 
            transition: margin-left 0.3s ease; 
        }
        @media (max-width: 768px) {
            .d-flex {
                flex-wrap: wrap;  
            }
            .d-flex > * {
                margin-bottom: 5px; 
            }
        }
        .avatar 
        {
            margin-top: 10px;
            vertical-align: middle;
            width: 225px;
            height: auto;
            border-radius: 50%;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
