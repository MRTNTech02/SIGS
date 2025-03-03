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
    if (isset($_GET['bug_id'])) {
        $bug_id = $_GET['bug_id'];

        $sql = "SELECT * FROM bugs_tbl WHERE bug_id='$bug_id'";
        try{
            $result = $conn->prepare($sql);
            $result->execute();

            if($result->rowCount()>0){
                $data = $result->fetch(PDO::FETCH_ASSOC);
                $bug_ticket = $data["bug_ticket"];
                $short_desc = $data["short_desc"];
                $bug_desc = $data["bug_desc"];
                $bug_status = $data["bug_status"];
                $insrt_ts = $data["insrt_ts"];
                $bug_file = $data["bug_file"];
            }
        }catch(Exception $e){
            echo "Error" . $e;
        }
    }
?>
<!-- calling ticket record -->
<?php 
    $sql = "SELECT * FROM bug_resolution_tbl AS A INNER JOIN admin_tbl AS B
    ON A.fk_admin_id=B.admin_id WHERE fk_bug_id='$bug_id'";
    try{
        $result = $conn->prepare($sql);
        $result->execute();

        if($result->rowCount()>0){
            $data = $result->fetch(PDO::FETCH_ASSOC);
            $admin_name = $data['a_fname'] . ' ' . $data['a_lname'] . ' ' . $data['a_suffix'];
            $username = $data['username'];
            $comment = $data['comment'];
        }
    }catch(Exception $e){
        echo "Error" . $e;
    };
?>

<?php 
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['resolve_issue'])) {
        $comment = $_POST['comment'];
        
        try {
            $conn->beginTransaction();
            
            // Update bug status
            $sqlUpdate = "UPDATE bugs_tbl SET bug_status='Resolved' WHERE bug_id='$bug_id'";
            $stmt = $conn->prepare($sqlUpdate);
            $stmt->execute();
            
            // Insert comment into resolution table
            $sqlInsert = "INSERT INTO bug_resolution_tbl (fk_bug_id, fk_admin_id, comment) VALUES (:bug_id, :admin_id, :comment)";
            $stmt = $conn->prepare($sqlInsert);
            $stmt->execute(['bug_id' => $bug_id, 'admin_id' => $admin_id, 'comment' => $comment]);
            
            $conn->commit();
            header("Location: ViewTicket.php?bug_id=$bug_id");
            exit();
        } catch (Exception $e) {
            $conn->rollBack();
            echo "Error: " . $e->getMessage();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bug Report</title>
    <link rel="stylesheet" href="../../Bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include '../Assets/components/Navbar.php'; ?>
    <div class="d-flex">
        <?php include '../Assets/components/AdminSidebar.php'; ?>
        <div class="content p-4 flex-grow-1">
            <h4 class="text-muted">View Ticket <?php echo $bug_ticket?> </h4>
            <div class="card table-container">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="w-50">Ticket Number: <?php echo $bug_ticket ?></div>
                        <div class="w-50">Date Raised: <?php echo $insrt_ts ?></div>
                    </div>
                    <div class="d-flex justify-content-between mb-5">
                        <div class="w-50">Ticket Status: <?php echo $bug_status ?></div>
                        <div class="w-50">
                            <a href="../Assets/BugReports/<?php echo $bug_file; ?>" 
                                class="btn btn-info btn-sm" 
                                download="<?php echo $bug_file; ?>">
                                Download Bug Documentation
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                    <h6>Short Description</h6>
                    <div class="card mb-3">
                        <div class="card-body">
                            <p><?php echo $short_desc ?></p>
                        </div>
                    </div>
                    <h6>Bug Description</h6>
                    <div class="card mb-3">
                        <div class="card-body">
                            <p><?php echo $bug_desc ?></p>
                        </div>
                    </div>
                    <?php 
                        if (!empty($comment)){
                            echo "
                                <p>$comment</p>
                            ";
                        }else{
                            echo "
                            <button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#resolveModal'>
                                Resolve Issue
                            </button>
                            ";
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Resolve Issue Modal -->
    <div class="modal fade" id="resolveModal" tabindex="-1" aria-labelledby="resolveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resolveModalLabel">Resolve Issue</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="comment" class="form-label">Comment</label>
                            <textarea class="form-control" id="comment" name="comment" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="resolve_issue" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="../Bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
