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
<!-- calling ticket record -->
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
            $insrt_ts =$data["insrt_ts"];
        }
        }catch(Exception $e){
        echo "Error" . $e;
        }
    };
?>

<!-- calling ticket record -->
<?php 
    $comment = "No resolution comment available.";
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bug Report</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="../../Bootstrap/css/bootstrap.min.css">
    <!-- Bootstrap Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include '../Assets/components/Navbar.php'; ?>

    <div class="d-flex">
        <?php include '../Assets/components/FacultySidebar.php'; ?>

        <!-- Main content -->
        <div class="content p-4 flex-grow-1">
            <h4 class="text-muted">View Ticket <?php echo $bug_ticket?> </h4>
            <div class="card table-container">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <!-- Search input and button -->
                        <div class="w-50">
                            Ticket Number: <?php echo $bug_ticket ?>
                        </div>
                        <div class="w-50">
                            Date Raised: <?php echo $insrt_ts ?>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mb-5">
                        <!-- Search input and button -->
                        <div class="w-50">
                            Ticket Status: <?php echo $bug_status ?>
                        </div>
                    </div>
                    <h6>Short Description</h6>
                    <div class="card mb-3">
                        <div class="card-body">
                            <p>
                                <?php echo $short_desc ?>
                            </p>
                        </div>
                    </div>
                    <h6>Bug Description</h6>
                    <div class="card mb-3">
                        <div class="card-body">
                            <p>
                                <?php echo $bug_desc ?>
                            </p>
                        </div>
                    </div>
                    <h6>Resolution Comment:</h6>
                    <div class="card mb-3">
                        <div class="card-body">
                            <p>
                                <?php echo $comment ?>
                            </p>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="../Bootstrap/js/bootstrap.bundle.min.js"></script>

    <style>
        .table-container { 
            margin-top: 20px; 
            min-width: 100%; 
        }
        .content { transition: margin-left 0.3s ease; }
        @media (max-width: 992px) { .content { margin-left: 0; } }
        #rowsPerPage { width: auto; }
        .role-label { font-weight: bold; padding: 2px 8px; border-radius: 12px; font-size: 12px; display: inline-block; width: 100px; text-align: center; }
        .label-faculty { background-color: #b2dba1; color: #3b7a00; }
        .label-registrar { background-color: #ffcc99; color: #cc5200; }
    </style>
</body>
</html>
