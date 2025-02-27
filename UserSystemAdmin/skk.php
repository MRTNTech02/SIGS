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
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
        try {
            $student_number = $_POST["student_number"];
            $lrn_number = $_POST["lrn_number"];
            $s_fname = $_POST["s_fname"];
            $s_mname = $_POST["s_mname"];
            $s_lname = $_POST["s_lname"];
            $s_suffix = $_POST["s_suffix"];
            $s_sex = $_POST["s_sex"];
            $s_birthdate = $_POST["s_birthdate"];
            $s_status = $_POST["s_status"];
            $s_profile = $_POST["s_profile"];

            $sql = "INSERT INTO students_tbl (student_number, lrn_number, s_fname, s_mname, s_lname, s_suffix, s_sex, s_birthdate, s_status, s_profile) 
            VALUES (:student_number, :lrn_number, :s_fname, :s_mname, :s_lname, :s_suffix, :s_sex, :s_birthdate, :s_status, :s_profile)";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ":student_number" => $student_number,
                ":lrn_number" => $lrn_number,
                ":s_fname" => $s_fname,
                ":s_mname" => $s_mname,
                ":s_lname" => $s_lname,
                ":s_suffix" => $s_suffix,
                ":s_sex" => $s_sex,
                ":s_birthdate" => $s_birthdate,
                ":s_status" => $s_status,
                ":s_profile" => $s_profile,
            ]);

            echo "<script>
                alert('New Student Record Saved Successfully!');
                window.location.href = 'student_management.php';
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
    <title>Add New Student Record</title>
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
            <h4 class="text-muted mb-4">Add New Student Record</h4>
            <div class="main-content">
                <?php if (!isset($_SESSION["student"])): ?>
                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="AddStudent.php">
                                <input type="hidden" id="s_profile" name="s_profile" value="studentdefaultprofile.jpg">
                                <input type="hidden" id="s_status" name="s_status" value="Active">
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label for="s_fname">First Name</label>
                                        <input type="text" class="form-control" id="s_fname" name="s_fname" placeholder="First Name" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="s_mname">Middle Name</label>
                                        <input type="text" class="form-control" id="s_mname" name="s_mname" placeholder="Middle Name">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="s_lname">Last Name</label>
                                        <input type="text" class="form-control" id="s_lname" name="s_lname" placeholder="Last Name" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="s_suffix">Suffix</label>
                                        <input type="text" class="form-control" id="s_suffix" name="s_suffix" placeholder="Suffix (Optional)">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label for="lrn_number">LRN</label>
                                        <input type="text" class="form-control" id="lrn_number" name="lrn_number" placeholder="Email" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="student_number">ID Number</label>
                                        <input type="text" class="form-control" id="student_number" name="student_number" placeholder="ID Number" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="s_sex">Sex</label>
                                        <select id="s_sex" name="s_sex" class="form-control" required>
                                            <option value="" disabled selected>Select Sex</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="s_birthdate">Birthdate</label>
                                        <input type="date" class="form-control" id="s_birthdate" name="s_birthdate" required>
                                    </div>
                                </div>
                                <button type="submit" name="register" id="register" class="btn btn-success">Add Student Record</button>
                                <a href="student_management.php" class="btn btn-secondary"> Cancel </a>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Step 2: Assign Student to Grade, Strand, Section -->
                    <h2>Step 2: Assign Student Details</h2>
                    <form method="post">
                        Year Level:
                        <select name="year_level" required>
                            <option value="">Select Year Level</option>
                            <?php while ($row = $yearLevels->fetch_assoc()): ?>
                                <option value="<?= $row['year_level_id'] ?>"><?= $row['yl_name'] ?></option>
                            <?php endwhile; ?>
                        </select><br>

                        Strand:
                        <select name="strand" required>
                            <option value="">Select Strand</option>
                            <?php while ($row = $strands->fetch_assoc()): ?>
                                <option value="<?= $row['strand_id'] ?>"><?= $row['strand_nn'] ?></option>
                            <?php endwhile; ?>
                        </select><br>

                        Section:
                        <select name="section" required>
                            <option value="">Select Section</option>
                            <?php while ($row = $sections->fetch_assoc()): ?>
                                <option value="<?= $row['section_id'] ?>"><?= $row['section_name'] ?></option>
                            <?php endwhile; ?>
                        </select><br>

                        <button type="submit" name="assign_student">Submit</button>
                        <button type="submit" name="cancel">Cancel</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
