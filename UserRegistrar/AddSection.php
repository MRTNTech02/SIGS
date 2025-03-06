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

<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
        try {
            $fk_year_id = $_POST["fk_year_id"];
            $fk_strand_id = $_POST["fk_strand_id"];
            $section_name = $_POST["section_name"];

            if (empty($section_name) || empty($fk_year_id) || empty($fk_strand_id)) {
                throw new Exception("Required fields are missing.");
            }

            $sql = "INSERT INTO sections_tbl (fk_year_id, fk_strand_id, section_name) 
            VALUES (:fk_year_id, :fk_strand_id, :section_name)";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ":fk_year_id" => $fk_year_id,
                ":fk_strand_id" => $fk_strand_id,
                ":section_name" => $section_name,
            ]);

            echo "<script>
                alert('New Section Saved Successfully!');
                window.location.href = 'sections.php';
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
    <title>Add New Section</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../Bootstrap/css/bootstrap.min.css">
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
            <h4 class="text-muted mb-4">Add New Section</h4>
            <div class="main-content">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="AddSection.php">
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

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="section_name">Section Name</label>
                                    <input type="text" class="form-control" id="section_name" name="section_name" required>
                                </div>
                            </div>

                            <!-- <button type="submit" name="assign_student">Submit</button>
                            <button type="submit" name="cancel">Cancel</button> -->
                            <button type="submit" name="register" id="register" class="btn btn-success">Save Strand</button>
                            <a href="sections.php" class="btn btn-secondary"> Cancel </a>
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
<script src="../Bootstrap/js/bootstrap.bundle.min.js"></script> 