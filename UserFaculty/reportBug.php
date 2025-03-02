<?php 
    session_start();
    include ("../server_connection/db_connect.php");

    // Ensure user is logged in
    if (!isset($_SESSION["id_number"]) || !isset($_SESSION["user_id"])) 
    {
        header("location: index.php");
    }

    $user_id = $_SESSION["user_id"];

    $sql = "SELECT * FROM users_tbl WHERE user_id = :user_id";
    try {
        $result = $conn->prepare($sql);
        $result->bindParam(':user_id', $user_id);
        $result->execute();

        if($result->rowCount() > 0){
            $data = $result->fetch(PDO::FETCH_ASSOC);
            $user_name = htmlspecialchars($data['u_fname'] . ' ' . $data['u_lname']);  
            $id_number = htmlspecialchars($data['id_number']);
        }
    } 
    catch(Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    // Logout logic
    if (isset($_GET['logout'])) {
        session_destroy();
        unset($_SESSION['id_number']);
        unset($_SESSION['user_id']);
        header("location: index.php");
        exit();
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
    <?php
        include '../Assets/components/Navbar.php';
    ?>
    <div class="d-flex">
        <?php
            include '../Assets/components/FacultySidebar.php';
        ?>
        <!-- Main content -->
        <div class="content p-4 flex-grow-1">
            <h4 class="text-muted mb-3">Raise a Ticket</h4>
            <div class="main-content">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-3">
                    <div class="box d-flex flex-column p-4 border border-success rounded" style="min-height: 150px; min-width: 100%;">
                    <div class="d-flex mb-3">
                    <p><strong>Report a bug you encountered.</strong><br>
                        Please describe the issue you're experiencing as clearly as you can. Include details like what you were doing when it happened and any error messages 
                        you saw. The more specific, the faster we can assist!
                    </p>
                    </div>

                    <form id="bugReportForm">
                        <div class="mb-3">
                            <label for="bugDescription" class="form-label"><strong>Bug Description:</strong></label>
                            <textarea id="bugDescription" class="form-control" rows="4" required placeholder="Describe the issue..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="bugScreenshot" class="form-label"><strong>Attach Screenshot (optional):</strong></label>
                            <input type="file" id="bugScreenshot" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane"></i> Submit Report</button>
                    </form>
                        
                    </div>
                    </div>
            </div>
        </div>
    </div>
    <script>
    document.getElementById("bugReportForm").addEventListener("submit", function(event) {
        event.preventDefault();
        const description = document.getElementById("bugDescription").value;
        if (!description.trim()) {
            alert("Please describe the bug before submitting.");
            return;
        }
        alert("Bug report submitted successfully! Thank you for your feedback.");
        
        this.reset();

    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>