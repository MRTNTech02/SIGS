<?php 
    session_start();
    include("../server_connection/db_connect.php");
    if (empty($_SESSION["id_number"]) || empty($_SESSION["user_password"])) {
        header("location: index.php");
        exit();
    }

    // Cancel Process (Reset session and redirect)
    if (isset($_POST["cancel"])) {
        unset($_SESSION["student"]);
        $_SESSION["step"] = 1;
        header("Location: AddStudent.php"); // Redirect before any output
        exit();
    }

    // Debugging
    // echo "<pre>";
    //     print_r($_SESSION);
    // echo "</pre>";

    // Setting step to 1
    if (!isset($_SESSION["step"])) {
        $_SESSION["step"] = 1;
    }

    // Prevent Step 2 from loading if student data is missing
    if ($_SESSION["step"] == 2 && empty($_SESSION["student"])) {
        $_SESSION["step"] = 1; // Reset to Step 1
    }

    // Step 1: Handling Add New Student Record (Temporary)
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["save_student"])) {
        $_SESSION["student"] = [
            "lrn_number" => $_POST["lrn_number"],
            "s_fname" => $_POST["s_fname"],
            "s_mname" => $_POST["s_mname"],
            "s_lname" => $_POST["s_lname"],
            "s_suffix" => $_POST["s_suffix"],
            "s_sex" => $_POST["s_sex"],
            "s_birthdate" => $_POST["s_birthdate"],
            "s_status" => $_POST["s_status"]
        ];
        $_SESSION["step"] = 2; // Move to step 2
        header("Location: AddStudent.php"); // Redirect to avoid resubmission issues
        exit();
    }

    // Step 2: Assigning Student to Grade, Strand, Section
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["assign_student"])) {
        if (isset($_SESSION["student"])) {
            try {
                // Insert student record
                $stmt = $conn->prepare("INSERT INTO students_tbl (lrn_number, s_fname, s_mname, s_lname, s_suffix, s_sex, s_birthdate, s_status) 
                VALUES (:lrn_number, :s_fname, :s_mname, :s_lname, :s_suffix, :s_sex, :s_birthdate, :s_status)");
                
                $stmt->execute($_SESSION["student"]);
                $student_id = $conn->lastInsertId();

                // Insert assignment record
                $stmt = $conn->prepare("INSERT INTO sc_assignments_tbl (fk_student_id, fk_year_id, fk_strand_id, fk_section_id) VALUES (?, ?, ?, ?)");
                $stmt->execute([$student_id, $_POST["year_level"], $_POST["strand"], $_POST["section"]]);

                // Clear session data and reset step
                unset($_SESSION["student"]);
                $_SESSION["step"] = 1;

                echo "<script>alert('Student successfully added!'); window.location.href='student_management.php';</script>";
                exit();
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
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
            <h4 class="text-muted mb-4">Add New Student Record</h4>
            <div class="main-content">
                <?php if (!isset($_SESSION["student"])): ?>
                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="AddStudent.php">
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
                                    <div class="form-group col-md-4">
                                        <label for="lrn_number">Learner's Reference Number</label>
                                        <input type="text" class="form-control" id="lrn_number" name="lrn_number" placeholder="LRN" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="s_sex">Sex</label>
                                        <select id="s_sex" name="s_sex" class="form-control" required>
                                            <option value="" disabled selected>Select Sex</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="s_birthdate">Birthdate</label>
                                        <input type="date" class="form-control" id="s_birthdate" name="s_birthdate" required>
                                    </div>
                                </div>
                                <button type="submit" name="save_student" class="btn btn-success">Next</button>
                                <a href="student_management.php" class="btn btn-secondary"> Cancel </a>
                                <!-- <button type="submit" name="register" id="register" class="btn btn-success">Add Student Record</button> -->
                                <!-- <a href="student_management.php" class="btn btn-secondary"> Cancel </a> -->
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Step 2: Assign Student to Grade, Strand, Section -->
                    <h2>Step 2: Assign Student Details</h2>
                    <form method="post">
                        <!-- Strand Dropdown -->
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                Strand:
                                <select class="form-control" name="strand" id="strand" required>
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
                            </div>
                            <div class="form-group col-md-4">
                                <!-- Year Level Dropdown -->
                                Year Level:
                                <select class="form-control" name="year_level" id="year_level" required>
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
                            </div>
                            <div class="form-group col-md-4">
                                <!-- Section Dropdown (Filtered Based on Strand & Year Level) -->
                                Section:
                                <select class="form-control" name="section" id="section">
                                    <option value="">Select Section</option>
                                </select><br>
                            </div>
                        </div>
                        <button type="submit" name="assign_student">Submit</button>
                        <button type="submit" name="cancel">Cancel</button>
                    </form>
                <?php endif; ?>
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