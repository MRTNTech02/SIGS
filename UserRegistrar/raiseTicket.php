<?php 
    session_start();
    include ("../server_connection/db_connect.php");

    // Ensure user is logged in
    if (!isset($_SESSION["id_number"]) || !isset($_SESSION["user_id"])) {
        header("location: index.php");
        exit();
    }

    $user_id = $_SESSION["user_id"];

    try {
        $sql = "SELECT * FROM users_tbl WHERE user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            $user_name = htmlspecialchars($data['u_fname'] . ' ' . $data['u_lname']);  
            $id_number = htmlspecialchars($data['id_number']);
        }
    } catch(Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    // Logout logic
    if (isset($_GET['logout'])) {
        session_destroy();
        header("location: index.php");
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $short_desc = htmlspecialchars($_POST['short_desc']);
        $bug_desc = htmlspecialchars($_POST['bug_desc']);
        $upload_dir = "../Assets/BugReports/";
        $bug_file = NULL;

        // Handle File Upload
        if (!empty($_FILES["bug_file"]["name"])) {
            $file_name = basename($_FILES["bug_file"]["name"]);
            $target_file = $upload_dir . time() . "_" . $file_name; // Add timestamp to avoid conflicts
            $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $allowed_types = array("pdf", "docx", "txt");

            if (!in_array($file_type, $allowed_types)) {
                die("Invalid file type! Allowed types: PDF, DOCX, TXT.");
            }
            if ($_FILES["bug_file"]["size"] > 5 * 1024 * 1024) { // 5MB max
                die("File is too large! Max size: 5MB.");
            }
            if (move_uploaded_file($_FILES["bug_file"]["tmp_name"], $target_file)) {
                $bug_file = time() . "_" . $file_name; // Store the same name in the database
            } else {
                die("File upload failed.");
            }
        }

        // Generate Bug Ticket Number (Year + Sequence Number)
        try {
            $year = date("Y");

            $stmt = $conn->query("SELECT COUNT(*) AS count FROM bugs_tbl WHERE bug_ticket LIKE '$year%'");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $sequence = str_pad($row['count'] + 1, 3, '0', STR_PAD_LEFT); // Ensures 3-digit sequence

            $bug_ticket = $year . $sequence;

            // Insert into bugs_tbl
            $sql = "INSERT INTO bugs_tbl (bug_ticket, fk_user_id, short_desc, bug_desc, bug_file) 
                    VALUES (:bug_ticket, :fk_user_id, :short_desc, :bug_desc, :bug_file)";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':bug_ticket', $bug_ticket);
            $stmt->bindParam(':fk_user_id', $user_id);
            $stmt->bindParam(':short_desc', $short_desc);
            $stmt->bindParam(':bug_desc', $bug_desc);
            $stmt->bindParam(':bug_file', $bug_file);

            if ($stmt->execute()) {
                echo "<script>alert('Bug reported successfully! Ticket Number: $bug_ticket'); window.location.href='reportBug.php';</script>";
            } else {
                echo "Error submitting report.";
            }
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
    <title>Raise a Ticket</title>
</head>
<body>
    <?php include '../Assets/components/RegistrarNavbar.php'; ?>
    <div class="d-flex">
        <?php include '../Assets/components/RegistrarSidebar.php'; ?>
        <div class="content p-4 flex-grow-1">
            <h4 class="text-muted mb-3">Raise a Ticket</h4>
            <div class="main-content">
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="box d-flex flex-column p-4 border border-success rounded">
                            <p><strong>Report a bug you encountered.</strong><br>
                                Please describe the issue clearly. Include details like what you were doing when it happened and any error messages you saw.
                            </p>
                            <form method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="short_desc" class="form-label"><strong>Short Description:</strong></label>
                                    <input type="text" id="short_desc" name="short_desc" class="form-control" required placeholder="Briefly describe the issue...">
                                </div>
                                <div class="mb-3">
                                    <label for="bug_desc" class="form-label"><strong>Bug Description:</strong></label>
                                    <textarea id="bug_desc" name="bug_desc" class="form-control" rows="4" required placeholder="Explain the issue in detail..."></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="bug_file" class="form-label"><strong>Attach File (optional):</strong></label>
                                    <p>Submit a document file with screenshots or steps to reproduce the issue.</p>
                                    <input type="file" accept="application/msword, application/pdf" id="bug_file" name="bug_file" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane"></i> Submit Report</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.querySelector("form").addEventListener("submit", function(event) {
        const shortDesc = document.getElementById("short_desc").value.trim();
        const bugDesc = document.getElementById("bug_desc").value.trim();
        if (!shortDesc || !bugDesc) {
            alert("Please fill in all required fields before submitting.");
            event.preventDefault();
        }
    });
    </script>
</body>
</html>
<script src="../Bootstrap/js/bootstrap.bundle.min.js"></script> 