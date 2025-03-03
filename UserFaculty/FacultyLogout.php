<?php 
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    include ("../server_connection/db_connect.php");

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        try {
            // Log logout event for admins using PDO
            $log_stmt = $conn->prepare("INSERT INTO user_logs_tbl (fk_user_id, role, action) VALUES (:user_id, 'Faculty', 'Logout')");
            $log_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $log_stmt->execute();
        } catch (PDOException $e) {
            die("Error in executing statement: " . $e->getMessage());
        }
    }

    // Destroy session
    session_unset();
    session_destroy();

    header("Location: index.php"); // Redirect to login page
    exit();
?>
