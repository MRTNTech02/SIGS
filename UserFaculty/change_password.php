<?php 
    session_start();
    include ("../server_connection/db_connect.php");

    if (!isset($_SESSION["id_number"])) {
        header("location: index.php");
        exit();
    }

    $user_id = $_SESSION["user_id"];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $current_password = $_POST['user_password'];
        $new_password = $_POST['new_password'];
        $retype_pass = $_POST['retype_pass'];

        // Fetch current password from the database
        $sql = "SELECT user_password FROM users_tbl WHERE user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo "<script>alert('User not found!'); window.location.href='change_password.php';</script>";
            exit();
        }

        // Verify current password (plain text comparison)
        if ($current_password !== $user['user_password']) {
            echo "<script>alert('Current password is incorrect!'); window.location.href='change_password.php';</script>";
            exit();
        }

        // Validate new password match
        if ($new_password !== $retype_pass) {
            echo "<script>alert('New passwords do not match!'); window.location.href='change_password.php';</script>";
            exit();
        }

        // Update password in the database (saving as plain text)
        $update_sql = "UPDATE users_tbl SET user_password = :new_password WHERE user_id = :user_id";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->execute([
            'new_password' => $new_password,
            'user_id' => $user_id
        ]);

        echo "<script>alert('Password changed successfully!'); window.location.href='faculty_profile.php';</script>";
        exit();
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../Assets/components/FacultyNavbar.php'; ?>
    <div class="d-flex">
        <?php include '../Assets/components/FacultySidebar.php'; ?>
        <div class="content p-4">
            <h4 class="text-muted">Change Password</h4>
            <!-- Navigation Buttons -->
            <div class="d-flex justify-content-start mb-3">
                <a href="faculty_profile.php" class="btn btn-outline-dark me-2">Information</a>
                <a href="change_password.php" class="btn btn-success me-2">Change Password</a>
            </div>
            <div class="card">
                <div class="card-header">Change Password</div>
                <div class="card-body">
                    <form action="change_password.php" method="post">
                        <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
                        <div class="form-group">
                            <label for="user_password">Current Password</label>
                            <input type="password" class="form-control" name="user_password" id="user_password" required>
                        </div>
                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" class="form-control" name="new_password" id="new_password" required>
                        </div>
                        <div class="form-group">
                            <label for="retype_pass">Confirm New Password</label>
                            <input type="password" class="form-control" name="retype_pass" id="retype_pass" required>
                        </div>
                        <button type="submit" class="btn btn-success">Change Password</button>
                    </form>

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
        .avatar 
        {
            margin-top: 10px;
            vertical-align: middle;
            width: 225px;
            height: 225px;
            object-fit: cover; 
            border-radius: 50%;
        }
        .card{
            height: 500px;
            width: 700px
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<script src="../Bootstrap/js/bootstrap.bundle.min.js"></script> 
