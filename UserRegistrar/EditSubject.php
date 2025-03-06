<?php 
  session_start();
  include ("../server_connection/db_connect.php");
  if (empty($_SESSION["id_number"]) && empty ($_SESSION["user_password"])) {
    header("location: index.php");
  }
  $current_datetime = date("Y-m-d H:i:s");

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
<!-- calling Strand Record -->
<?php
    if (isset($_GET['subject_id'])) {
        $subject_id = $_GET['subject_id'];

        $sql = "SELECT * FROM subjects_tbl WHERE subject_id='$subject_id'";
        try{
        $result = $conn->prepare($sql);
        $result->execute();

        if($result->rowCount()>0){
            $data = $result->fetch(PDO::FETCH_ASSOC);
            $subject_name = $data["subject_name"];
        }
        }catch(Exception $e){
        echo "Error" . $e;
        }
    };
?>

<!-- PHP code for editing strand -->
<?php 
    if (isset($_POST['saveEdit'])) {
        $subject_id = $_POST["subject_id"];
        $subject_name = $_POST["subject_name"];
    
        $sql = "UPDATE subjects_tbl SET 
            subject_name = :subject_name, 
            updt_ts = :updt_ts
            WHERE subject_id = :subject_id";
    
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':subject_name', $subject_name);
            $stmt->bindParam(':updt_ts', $current_datetime);
            $stmt->bindParam(':subject_id', $subject_id); 
    
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                header("Location: subjects.php");
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
    <title>Edit Subject</title>
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
            <h4 class="text-muted mb-4">Edit Subject</h4>
            <div class="main-content">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="EditSubject.php">
                            <input type="hidden" name="subject_id" value="<?php echo $subject_id ?>">
                            <div class="form-row">
                                <div class="form-group col-md-9">
                                    <label for="subject_name">Subject Name</label>
                                    <input type="text" class="form-control" id="subject_name" name="subject_name" value="<?php echo $subject_name ?>" required>
                                </div>
                            </div>
                            <button type="submit" name="saveEdit" id="saveEdit" class="btn btn-success">Save Changes</button>
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
<script src="../Bootstrap/js/bootstrap.bundle.min.js"></script> 