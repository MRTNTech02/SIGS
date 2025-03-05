<?php 
  session_start();
  include ("../server_connection/db_connect.php");
  if (empty($_SESSION["username"]) && empty ($_SESSION["a_password"])) {
    header("location: index.php");
    exit();
  }
  if (!empty($_SESSION["username"])){
    $admin_id = $_SESSION["admin_id"];

    $sql = "SELECT * FROM admin_tbl WHERE admin_id = :admin_id";
    try{
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
      $stmt->execute();

      if($stmt->rowCount() > 0){
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        $admin_name = $data['a_fname'] . ' ' . $data['a_lname'];
        $username = $data['username'];
      }
    }catch(Exception $e){
      echo "Error: " . $e->getMessage();
      exit();
    }
  }
  if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: index.php");
    exit();
  }
?>

<?php
    if (isset($_GET['user_id'])) {
        $faculty_id = $_GET['user_id'];
        $sql = "SELECT * FROM users_tbl WHERE user_id=:user_id";
        try {
            $result = $conn->prepare($sql);
            $result->execute(['user_id' => $faculty_id]);

            if($result->rowCount() > 0){
                $data = $result->fetch(PDO::FETCH_ASSOC);
                $faculty_name = $data['u_fname'] . ' ' . $data['u_lname'] . ' ' . $data['u_suffix'];
                $id_number = $data['id_number'];
                $user_profile = $data['user_profile'];
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
?>

<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
        try {
            $fk_user_id = $_POST["fk_user_id"];
            $fk_strand_id = $_POST["fk_strand_id"];
            $fk_year_id = $_POST["fk_year_id"];
            $fk_section_id = $_POST["fk_section_id"];
            $fk_subject_id = $_POST["fk_subject_id"];
            $f_academic_year = $_POST["f_academic_year"];

            if (empty($fk_user_id) || empty($fk_subject_id) || empty($fk_strand_id) || empty($fk_year_id) || empty($fk_section_id) || empty($f_academic_year)) {
                throw new Exception("Required fields are missing.");
            }

            $sql = "INSERT INTO faculty_assignments_tbl (fk_user_id, fk_subject_id, fk_strand_id, fk_year_id, fk_section_id, f_academic_year) 
            VALUES (:fk_user_id, :fk_subject_id, :fk_strand_id, :fk_year_id, :fk_section_id, :f_academic_year)";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ":fk_user_id" => $fk_user_id,
                ":fk_subject_id" => $fk_subject_id,
                ":fk_strand_id" => $fk_strand_id,
                ":fk_year_id" => $fk_year_id,
                ":fk_section_id" => $fk_section_id,
                ":f_academic_year" => $f_academic_year,
            ]);

            echo "<script>
                alert('New Subject Successfully Assigned to Faculty!');
                window.location.href = 'ViewFacultyUser.php?user_id=$fk_user_id';
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
    <title>Assign To Faculty</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../Bootstrap/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery for AJAX -->
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
            <h4 class="text-muted mb-4">Assign Subject to <?php echo $faculty_name ?></h4>
            <div class="main-content">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="AssignSubjectFaculty.php?user_id=<?php echo $faculty_id ?>">
                            <input type="hidden" name="fk_user_id" id="fk_user_id" value="<?php echo $faculty_id ?>">
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <!-- Strand Dropdown -->
                                    Strand:
                                    <select class="form-control" name="fk_strand_id" id="fk_strand_id" required>
                                        <option value="">Select Strand</option>
                                        <?php
                                            $sql = "SELECT strand_id, strand_name, strand_nn FROM strands_tbl";
                                            try {
                                                $result = $conn->prepare($sql);
                                                $result->execute();
                                                if ($result->rowCount() > 0) {
                                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                                        echo "<option value='{$row["strand_id"]}'>{$row["strand_name"]} ({$row["strand_nn"]})</option>";
                                                    }
                                                } else {
                                                    echo "<option value=''>No records found.</option>";
                                                }
                                            } catch (Exception $e) {
                                                echo "Unexpected error occurred!" . $e->getMessage();
                                            }
                                        ?>
                                    </select><br>

                                    <!-- Year Level Dropdown -->
                                    Year Level:
                                    <select class="form-control" name="fk_year_id" id="fk_year_id" required>
                                        <option value="">Select Year Level</option>
                                        <?php
                                            $sql = "SELECT year_level_id, yl_name FROM year_levels_tbl";
                                            try {
                                                $result = $conn->prepare($sql);
                                                $result->execute();
                                                if ($result->rowCount() > 0) {
                                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                                        echo "<option value='{$row["year_level_id"]}'>{$row["yl_name"]}</option>";
                                                    }
                                                } else {
                                                    echo "<option value=''>No records found.</option>";
                                                }
                                            } catch (Exception $e) {
                                                echo "Unexpected error occurred!" . $e->getMessage();
                                            }
                                        ?>
                                    </select><br>

                                    <!-- Section Dropdown (Filtered Based on Strand & Year Level) -->
                                    Section:
                                    <select class="form-control" name="fk_section_id" id="fk_section_id" required>
                                        <option value="">Select Section</option>
                                        <?php
                                            $sql = "SELECT section_id, section_name FROM sections_tbl";
                                            try {
                                                $result = $conn->prepare($sql);
                                                $result->execute();
                                                if ($result->rowCount() > 0) {
                                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                                        echo "<option value='{$row["section_id"]}'>{$row["section_name"]}</option>";
                                                    }
                                                } else {
                                                    echo "<option value=''>No records found.</option>";
                                                }
                                            } catch (Exception $e) {
                                                echo "Unexpected error occurred!" . $e->getMessage();
                                            }
                                        ?>
                                    </select><br>

                                    <!-- Subject Dropdown -->
                                    Subject:
                                    <select class="form-control" name="fk_subject_id" id="fk_subject_id" required>
                                        <option value="">Select Subject</option>
                                        <?php
                                            $sql = "SELECT subject_id, subject_name FROM subjects_tbl";
                                            try {
                                                $result = $conn->prepare($sql);
                                                $result->execute();
                                                if ($result->rowCount() > 0) {
                                                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                                        echo "<option value='{$row["subject_id"]}'>{$row["subject_name"]}</option>";
                                                    }
                                                } else {
                                                    echo "<option value=''>No records found.</option>";
                                                }
                                            } catch (Exception $e) {
                                                echo "Unexpected error occurred!" . $e->getMessage();
                                            }
                                        ?>
                                    </select><br>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="f_academic_year">Academic Year</label>
                                    <input type="text" class="form-control" id="f_academic_year" name="f_academic_year" required>
                                </div>
                            </div>
                            <button type="submit" name="register" id="register" class="btn btn-success">Assign Subject</button>
                            <a href="ViewFacultyUser.php?user_id=<?php echo $faculty_id?>" class="btn btn-secondary"> Cancel </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            function loadSections() {
                var fk_strand_id = $('#fk_strand_id').val();
                var fk_year_id = $('#fk_year_id').val();

                if (fk_strand_id && fk_year_id) {
                    $.ajax({
                        url: "fetch_sections.php",
                        type: "POST",
                        data: { fk_strand_id: fk_strand_id, fk_year_id: fk_year_id },
                        success: function(data) {
                            console.log("Fetched sections:", data); // Debugging
                            $('#fk_section_id').html('<option value="">Select Section</option>' + data);
                        },
                        error: function(xhr, status, error) {
                            console.log("AJAX Error:", error);
                        }
                    });
                } else {
                    $('#fk_section_id').html('<option value="">Select Section</option>');
                }
            }

            $('#fk_strand_id, #fk_year_id').change(loadSections);
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
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
