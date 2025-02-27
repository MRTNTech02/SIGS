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
            $subject_name = $_POST["subject_name"];

            if (empty($subject_name)) {
                throw new Exception("Required fields are missing.");
            }

            $sql = "INSERT INTO subjects_tbl (subject_name) 
            VALUES (:subject_name)";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ":subject_name" => $subject_name,
            ]);

            echo "<script>
                alert('New Subject Saved Successfully!');
                window.location.href = 'subjects.php';
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
    <title>Add New Subject</title>
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
            <h4 class="text-muted mb-4">Add New Subject</h4>
            <div class="main-content">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="AddSubject.php">
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="subject_name">Subject Name</label>
                                    <input type="text" class="form-control" id="subject_name" name="subject_name" required>
                                </div>
                            </div>
                            <button type="submit" name="register" id="register" class="btn btn-success">Save Subject</button>
                            <a href="subjects.php" class="btn btn-secondary"> Cancel </a>
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
