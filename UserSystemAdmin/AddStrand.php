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
            $strand_name = $_POST["strand_name"];
            $strand_nn = $_POST["strand_nn"];

            if (empty($strand_name) || empty($strand_nn)) {
                throw new Exception("Required fields are missing.");
            }

            $sql = "INSERT INTO strands_tbl (strand_name, strand_nn) 
            VALUES (:strand_name, :strand_nn)";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ":strand_name" => $strand_name,
                ":strand_nn" => $strand_nn,
            ]);

            echo "<script>
                alert('New Strand Saved Successfully!');
                window.location.href = 'academic_management.php';
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
    <title>Add New Strand</title>
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
            <h4 class="text-muted mb-4">Add New Strand</h4>
            <div class="main-content">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="AddStrand.php">
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="strand_name">Strand Name</label>
                                    <input type="text" class="form-control" id="strand_name" name="strand_name" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="strand_nn">Strand Abbreviation</label>
                                    <input type="text" class="form-control" id="strand_nn" name="strand_nn" >
                                </div>
                            </div>
                            <button type="submit" name="register" id="register" class="btn btn-success">Save Strand</button>
                            <a href="academic_management.php" class="btn btn-secondary"> Cancel </a>
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
