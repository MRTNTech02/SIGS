<?php 
  session_start();
  include ("../server_connection/db_connect.php");
  if (empty($_SESSION["id_number"]) && empty ($_SESSION["user_password"])) {
    header("location: index.php");
  }
  if (!empty($_SESSION["id_number"])){
    $user_id = $_SESSION["user_id"];

    $sql = "SELECT * FROM users_tbl WHERE user_id=$user_id";
    try{
      $result = $conn->prepare($sql);
      $result->execute();

      if($result->rowCount()>0){
        $data = $result->fetch(PDO::FETCH_ASSOC);
        $registrar_name = $data['u_fname'] . ' ' . $data['u_lname'] . ' ' . $data['u_suffix'] ;
        $id_number = $data['id_number'];
      }
    }catch(Exception $e){
      echo "Error" . $e;
    }
  };
  if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['id_number']);
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
<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
        try {
            $fk_assignment_id = $_POST["fk_assignment_id"];
            $fk_subject_id = $_POST["fk_subject_id"];
            $semester = $_POST["semester"];
            $academic_year = $_POST["academic_year"];
            

            if (empty($fk_assignment_id) || empty($fk_subject_id) || empty($semester) || empty($academic_year)){
                throw new Exception("Required fields are missing.");
            }

            $sql = "INSERT INTO subjects_taking_tbl (fk_assignment_id, fk_subject_id, semester, academic_year) 
            VALUES (:fk_assignment_id, :fk_subject_id, :semester, :academic_year)";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ":fk_assignment_id" => $fk_assignment_id,
                ":fk_subject_id" => $fk_subject_id,
                ":semester" => $semester,
                ":academic_year" => $academic_year,
            ]);

            echo "<script>
                alert('New Subject Assigned Successfully!');
                window.location.href = 'ViewStudent.php?assignment_id=$fk_assignment_id';
            </script>";
            exit();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Student</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../Assets/components/Navbar.php'; ?>
    <div class="d-flex">
        <?php include '../Assets/components/AdminSidebar.php'; ?>
        <div class="content p-4 w-100">
            <h4 class="text-muted mb-4">Assign Subject</h4>
            <div class="card">
                <div class="card-body">
                    <form method="post" action="AssignSubjectStudent.php">
                        <input type="hidden" name="fk_assignment_id" id="fk_assignment_id" value="<?php echo $assignment_id; ?>">
                        
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Subjects</label>
                                <select name="fk_subject_id" id="fk_subject_id"  class="form-control" required>
                                    <option value="">Select Subject</option>
                                    <?php
                                        $sql = "SELECT subject_id, subject_name FROM subjects_tbl WHERE subject_id NOT IN 
                                        (SELECT fk_subject_id FROM subjects_taking_tbl WHERE fk_assignment_id=$assignment_id)";
                                        $result = $conn->query($sql);
                                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                            $selected = ($row["subject_id"] == $section["fk_subject_id"]) ? "selected" : "";
                                            echo "<option value='{$row["subject_id"]}' $selected>{$row["subject_name"]}</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Semester</label>
                                <select name="semester" id="semester" class="form-control" required>
                                    <option value="">Select Semester</option>
                                    <option value="1">1st Semester</option>
                                    <option value="2">2nd Semester</option>
                                    
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Academic Year</label>
                                <input type="text" class="form-control" name="academic_year" id="academic_year" required>
                            </div>
                        </div>
                       
                        <button type="submit" name="register" id="register" class="btn btn-success">Save Record</button>
                        <a href="ViewStudent.php?assignment_id=<?php echo $assignment_id ?>" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
