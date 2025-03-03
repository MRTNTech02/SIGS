<?php
include ("../server_connection/db_connect.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["saveEdit"])) {
    $targetDir = "../Assets/img/profile_pictures/";
    
    if (!is_dir($targetDir) && !mkdir($targetDir, 0777, true)) {
        die("Error: Failed to create directory.");
    }
    
    if (!isset($_FILES["user_profile"]) || $_FILES["user_profile"]["error"] !== UPLOAD_ERR_OK) {
        die("Error: No file uploaded or file upload error.");
    }

    $user_id = $_POST["user_id"];
    $fileName = basename($_FILES["user_profile"]["name"]);
    $fileType = strtolower(trim(pathinfo($fileName, PATHINFO_EXTENSION)));

    echo "Detected file type: " . $fileType . "<br>"; // Debugging

    $allowedTypes = ["png", "jpg", "jpeg", "jfif"];
    if (!in_array($fileType, $allowedTypes)) {
        die("Error: Only PNG, JPG, and JPEG files are allowed.");
    }

    $allowedMimeTypes = ["image/png", "image/jpg", "image/jpeg", "image/pjpeg"];
    $mimeType = mime_content_type($_FILES["user_profile"]["tmp_name"]);

    echo "Detected MIME type: " . $mimeType . "<br>"; // Debugging

    if (!in_array($mimeType, $allowedMimeTypes)) {
        die("Error: Invalid file type.");
    }

    if ($_FILES["user_profile"]["size"] > 2 * 1024 * 1024) {
        die("Error: File size must be less than 2MB.");
    }

    $newFileName = "profile_" . $user_id . "." . $fileType;
    $targetFilePath = $targetDir . $newFileName;

    if (!move_uploaded_file($_FILES["user_profile"]["tmp_name"], $targetFilePath)) {
        die("Error: File upload failed.");
    }

    // **Use PDO properly here**
    try {
        $sql = "UPDATE users_tbl SET user_profile = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$newFileName, $user_id]);
        echo "<script>
                alert('Profile Updated Successfully!');
                window.location.href = 'faculty_profile.php';
            </script>";
    } catch (PDOException $e) {
        die("Error: Database update failed - " . $e->getMessage());
    }
}
?>
