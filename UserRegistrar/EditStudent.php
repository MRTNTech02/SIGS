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
<!-- calling student Record -->
<?php
    if (isset($_GET['student_id'])) {
        $student_id = $_GET['student_id'];

        $sql = "SELECT * FROM students_tbl WHERE student_id='$student_id'";
        try{
        $result = $conn->prepare($sql);
        $result->execute();

        if($result->rowCount()>0){
            $data = $result->fetch(PDO::FETCH_ASSOC);
            $s_fname = $data["s_fname"];
            $s_lname = $data["s_lname"];
            $s_lname = $data["s_lname"];
            $s_suffix = $data["s_suffix"];
            $lrn_number = $data["lrn_number"];
            $s_sex = $data["s_sex"];
            $s_birthdate = $data["s_birthdate"];
            $student_id = $data["student_id"];
        }
        }catch(Exception $e){
        echo "Error" . $e;
        }
    };
?>
<!-- PHP code for editing user -->
<?php 
    if (isset($_POST['saveEdit'])) {
        $s_fname = $_POST["s_fname"];
        $s_lname = $_POST["s_lname"];
        $s_lname = $_POST["s_lname"];
        $s_suffix = $_POST["s_suffix"];
        $lrn_number = $_POST["lrn_number"];
        $s_sex = $_POST["s_sex"];
        $s_birthdate = $_POST["s_birthdate"];
        $student_id = $_POST["student_id"];
    
        $sql = "UPDATE students_tbl SET 
            s_fname = :s_fname, 
            s_mname = :s_mname, 
            s_lname = :s_lname, 
            s_suffix = :s_suffix, 
            s_sex = :s_sex, 
            s_birthdate = :s_birthdate, 
            lrn_number = :lrn_number
            WHERE student_id = :student_id";
    
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':s_fname', $s_fname);
            $stmt->bindParam(':s_mname', $s_mname);
            $stmt->bindParam(':s_lname', $s_lname);
            $stmt->bindParam(':s_suffix', $s_suffix);
            $stmt->bindParam(':s_sex', $s_sex);
            $stmt->bindParam(':s_birthdate', $s_birthdate);
            $stmt->bindParam(':lrn_number', $lrn_number);
            $stmt->bindParam(':student_id', $student_id);
    
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                header("Location: student_management.php");
                exit();
            } else {
                echo "No record has been updated";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }    
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student Record</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../Bootstrap/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery for AJAX -->
</head>
<body>
    <?php
        include '../Assets/components/RegistrarNavbar.php';
    ?>
    <div class="d-flex">
        <?php
            include '../Assets/components/RegistrarSidebar.php';
        ?>
        <!-- Main content -->
        <div class="content p-4 w-100">
            <h4 class="text-muted mb-4">Edit Student Record</h4>
            <div class="main-content">
                <div class="card">
                        <div class="card-body">
                            <form method="post" action="EditStudent.php">
                                <input type="hidden" name="student_id" id="student_id" value="<?php echo $student_id ?>">
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label for="s_fname">First Name</label>
                                        <input type="text" class="form-control" id="s_fname" name="s_fname" value="<?php echo $s_fname ?>" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="s_mname">Middle Name</label>
                                        <input type="text" class="form-control" id="s_mname" name="s_mname" value="<?php echo $s_mname ?>">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="s_lname">Last Name</label>
                                        <input type="text" class="form-control" id="s_lname" name="s_lname" value="<?php echo $s_lname ?>" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="s_suffix">Suffix</label>
                                        <input type="text" class="form-control" id="s_suffix" name="s_suffix" value="<?php echo $s_suffix ?>">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label for="lrn_number">Learner's Reference Number</label>
                                        <input type="text" class="form-control" id="lrn_number" name="lrn_number" value="<?php echo $lrn_number ?>" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="s_sex">Sex</label>
                                        <select id="s_sex" name="s_sex" class="form-control" required>
                                            <option value="" disabled>Select Sex</option>
                                            <option value="Male" <?php echo ($s_sex == "Male") ? "selected" : ""; ?>>Male</option>
                                            <option value="Female" <?php echo ($s_sex == "Female") ? "selected" : ""; ?>>Female</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="s_birthdate">Birthdate</label>
                                        <input type="date" class="form-control" id="s_birthdate" name="s_birthdate" value="<?php echo $s_birthdate ?>" required>
                                    </div>
                                </div>
                                <button type="submit" name="saveEdit" id="saveEdit" class="btn btn-success">Save Changes</button>
                                <a href="student_management.php" class="btn btn-secondary"> Cancel </a>
                            </form>
                        </div>
                    </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            function loadSections() {
                var strand_id = $('#strand').val();
                var year_level_id = $('#year_level').val();

                if (strand_id && year_level_id) {
                    $.ajax({
                        url: "get_sections.php",
                        type: "POST",
                        data: { strand_id: strand_id, year_level_id: year_level_id },
                        success: function(data) {
                            $('#section').html(data);
                        }
                    });
                } else {
                    $('#section').html('<option value="">Select Section</option>');
                }
            }

            $('#strand, #year_level').change(loadSections);
        });
    </script>

    <style>
        @media (max-width: 768px) {
            .d-flex {
                flex-wrap: wrap;  
            }
            .d-flex > * {
                margin-bottom: 5px; 
            }
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<script src="../Bootstrap/js/bootstrap.bundle.min.js"></script> 