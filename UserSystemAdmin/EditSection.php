<?php 
  session_start();
  include ("../server_connection/db_connect.php");
  if (empty($_SESSION["username"]) && empty ($_SESSION["a_password"])) {
    header("location: index.php");
  }
  if (!empty($_SESSION["username"])){
    $admin_id = $_SESSION["admin_id"];

    $sql = "SELECT * FROM admin_tbl WHERE admin_id=$admin_id";
    try{
      $result = $conn->prepare($sql);
      $result->execute();

      if($result->rowCount()>0){
        $data = $result->fetch(PDO::FETCH_ASSOC);
        $admin_name = $data['a_fname'] . ' ' . $data['a_lname'] . ' ' . $data['a_suffix'];
        $username = $data['username'];
      }
    }catch(Exception $e){
      echo "Error" . $e;
    }
  };
  if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: index.php");
    }
?>
<?php 

  // Fetch section details for editing
  if (isset($_GET['section_id'])) {
    $section_id = $_GET['section_id'];
    $sql = "SELECT * FROM sections_tbl WHERE section_id = :section_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['section_id' => $section_id]);
    $section = $stmt->fetch(PDO::FETCH_ASSOC);
  }

  // Save changes
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_changes'])) {
    $section_id = $_POST['section_id'];
    $fk_strand_id = $_POST['fk_strand_id'];
    $fk_year_id = $_POST['fk_year_id'];
    $section_name = $_POST['section_name'];

    $update_sql = "UPDATE sections_tbl SET fk_strand_id = :fk_strand_id, fk_year_id = :fk_year_id, section_name = :section_name WHERE section_id = :section_id";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->execute([
      'fk_strand_id' => $fk_strand_id,
      'fk_year_id' => $fk_year_id,
      'section_name' => $section_name,
      'section_id' => $section_id
    ]);
    
    header("Location: sections.php?success=Section updated successfully");
    exit();
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Section</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../Assets/components/Navbar.php'; ?>
    <div class="d-flex">
        <?php include '../Assets/components/AdminSidebar.php'; ?>
        <div class="content p-4 w-100">
            <h4 class="text-muted mb-4">Edit Section</h4>
            <div class="card">
                <div class="card-body">
                    <form method="post" action="">
                        <input type="hidden" name="section_id" value="<?php echo $section['section_id']; ?>">
                        
                        Strand:
                        <select name="fk_strand_id" id="fk_strand_id" required>
                            <option value="">Select Strand</option>
                            <?php
                                $sql = "SELECT strand_id, strand_name, strand_nn FROM strands_tbl";
                                $result = $conn->query($sql);
                                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                    $selected = ($row["strand_id"] == $section["fk_strand_id"]) ? "selected" : "";
                                    echo "<option value='{$row["strand_id"]}' $selected>{$row["strand_name"]} ({$row["strand_nn"]})</option>";
                                }
                            ?>
                        </select><br>

                        Year Level:
                        <select name="fk_year_id" id="fk_year_id" required>
                            <option value="">Select Year Level</option>
                            <?php
                                $sql = "SELECT year_level_id, yl_name FROM year_levels_tbl";
                                $result = $conn->query($sql);
                                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                                    $selected = ($row["year_level_id"] == $section["fk_year_id"]) ? "selected" : "";
                                    echo "<option value='{$row["year_level_id"]}' $selected>{$row["yl_name"]}</option>";
                                }
                            ?>
                        </select><br>

                        <div class="form-group">
                            <label for="section_name">Section Name</label>
                            <input type="text" class="form-control" id="section_name" name="section_name" value="<?php echo htmlspecialchars($section['section_name']); ?>" required>
                        </div>
                        
                        <button type="submit" name="save_changes" class="btn btn-success">Save Changes</button>
                        <a href="sections.php" class="btn btn-secondary">Cancel</a>
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
