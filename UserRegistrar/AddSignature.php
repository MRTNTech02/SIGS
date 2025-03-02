<?php 
session_start();
include ("../server_connection/db_connect.php");

if (empty($_SESSION["id_number"]) && empty($_SESSION["user_password"])) {
    header("location: index.php");
    exit();
}

if (!empty($_SESSION["id_number"])) {
    $user_id = $_SESSION["user_id"];

    $sql = "SELECT * FROM users_tbl WHERE user_id=:user_id";
    try {
        $result = $conn->prepare($sql);
        $result->execute([":user_id" => $user_id]);

        if ($result->rowCount() > 0) {
            $data = $result->fetch(PDO::FETCH_ASSOC);
            $registrar_name = $data['u_fname'] . ' ' . $data['u_lname'] . ' ' . $data['u_suffix'];
            $id_number = $data['id_number'];
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['id_number']);
    header("location: index.php");
    exit();
}

?>
<?php 
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $owner_name = $_POST["owner_name"];
        $owner_role = $_POST["owner_role"];
        
        if (isset($_FILES["owner_signature"]) && $_FILES["owner_signature"]["error"] == 0) {
            $target_dir = "../Assets/img/signatures/"; // Corrected path
            $file_name = basename($_FILES["owner_signature"]["name"]);
            $target_file = $target_dir . $file_name;
            $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            
            // Ensure directory exists
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
            // Allow only PNG files
            if ($file_type != "png") {
                echo "Only PNG files are allowed.";
            } else {
                if (move_uploaded_file($_FILES["owner_signature"]["tmp_name"], $target_file)) {
                    // Save filename in database
                    $sql = "INSERT INTO e_sigs_tbl (owner_name, owner_role, owner_signature) VALUES (:owner_name, :owner_role, :owner_signature)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([":owner_name" => $owner_name, ":owner_role" => $owner_role, ":owner_signature" => $file_name]);
                    echo "<script>
                        alert('New E-signature Saved Successfully!');
                        window.location.href = 'e_sigs.php';
                    </script>";
                } else {
                    echo "Error: File upload failed. Target File Path: " . $target_file;
                }
            }
        } else {
            echo "Please upload a signature.";
        }
    }
    
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload New E-signature Record</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../Bootstrap/css/bootstrap.min.css">
</head>
<body>
    <?php include '../Assets/components/RegistrarNavbar.php'; ?>
    <div class="d-flex">
        <?php include '../Assets/components/RegistrarSidebar.php'; ?>
        <div class="content p-4 w-100">
            <h4 class="text-muted mb-4">Upload New E-signature Record</h4>
            <div class="main-content">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="AddSignature.php" enctype="multipart/form-data">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="owner_name">E-signature Owner's Name</label>
                                    <input type="text" class="form-control" id="owner_name" name="owner_name" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="role">Role</label>
                                    <select id="owner_role" name="owner_role" class="form-control" required>
                                        <option value="" disabled selected>Select Role</option>
                                        <option value="Registrar">Registrar</option>
                                        <option value="Faculty">Faculty</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="owner_signature">E-signature (PNG only)</label>
                                    <input type="file" class="form-control" id="owner_signature" name="owner_signature" accept="image/png" required>
                                </div>
                            </div>
                            <button type="submit" name="register" id="register" class="btn btn-success">Save Signature Record</button>
                            <a href="e_sigs.php" class="btn btn-secondary"> Cancel </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        @media (max-width: 768px) {
            .d-flex { flex-wrap: wrap; }
            .d-flex > * { margin-bottom: 5px; }
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
