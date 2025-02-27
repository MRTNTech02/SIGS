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
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
        try {
            $lrn_number = $_POST["lrn_number"];
            $s_fname = $_POST["s_fname"];
            $s_mname = $_POST["s_mname"];
            $s_lname = $_POST["s_lname"];
            $s_suffix = $_POST["s_suffix"];
            $s_sex = $_POST["s_sex"];
            $s_birthdate = $_POST["s_birthdate"];
            $s_status = $_POST["s_status"];

            if (empty($lrn_number) || empty($s_fname) || empty($s_lname)) {
                throw new Exception("Required fields are missing.");
            }

            $sql = "INSERT INTO students_tbl (lrn_number, s_fname, s_mname, s_lname, s_suffix, s_sex, s_birthdate, s_status) 
            VALUES (:lrn_number, :s_fname, :s_mname, :s_lname, :s_suffix, :s_sex, :s_birthdate, :s_status)";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ":lrn_number" => $lrn_number,
                ":s_fname" => $s_fname,
                ":s_mname" => $s_mname,
                ":s_lname" => $s_lname,
                ":s_suffix" => $s_suffix,
                ":s_sex" => $s_sex,
                ":s_birthdate" => $s_birthdate,
                ":s_status" => $s_status,
            ]);

            echo "<script>
                alert('New Student Added Successfully!');
                window.location.href = 'students.php';
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
    <title>Add New User</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../Bootstrap/css/bootstrap.min.css">
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
        <div class="content p-4 w-100">
            <h4 class="text-muted mb-4">Add New User</h4>
            <div class="main-content">
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
                                    <label for="lrn_number">Learner's Reference Number (LRN)</label>
                                    <input type="text" class="form-control" id="lrn_number" name="lrn_number" placeholder="ID Number" required>
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
                            <button type="submit" name="register" id="register" class="btn btn-success">Add Student</button>
                            <a href="students.php" class="btn btn-secondary"> Cancel </a>
                        </form>
                    </div>
                </div>
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
