<?php
    session_start();
    include("../server_connection/db_connect.php");

    if (empty($_SESSION["username"]) && empty ($_SESSION["a_password"])) {
        header("location: index.php");
    }

    if (isset($_GET['id'])) {
        $signature_id = $_GET['id'];

        // Prepare the delete query
        $sql = "DELETE FROM e_sigs_tbl WHERE signature_id = :signature_id";
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":signature_id", $signature_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Record deleted successfully!";
                $_SESSION['msg_type'] = "success";
            } else {
                $_SESSION['message'] = "Failed to delete the record!";
                $_SESSION['msg_type'] = "danger";
            }
        } catch (Exception $e) {
            $_SESSION['message'] = "Error: " . $e->getMessage();
            $_SESSION['msg_type'] = "danger";
        }

        header("Location: e_sigs.php"); // Redirect back to the main page
        exit();
    } else {
        $_SESSION['message'] = "Invalid request!";
        $_SESSION['msg_type'] = "warning";
        header("Location: e_sigs.php");
        exit();
    }
?>
