<?php
    include("../server_connection/db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Get form data
        $assignment_id = $_POST['assignment_id'] ?? null;
        $fk_strand_id = $_POST['fk_strand_id'] ?? null;
        $fk_year_id = $_POST['fk_year_id'] ?? null;
        $fk_section_id = $_POST['fk_section_id'] ?? null;

        // Check if required fields are filled
        if (!$assignment_id || !$fk_strand_id || !$fk_year_id || !$fk_section_id) {
            throw new Exception("All fields are required.");
        }

        // Prepare SQL update statement
        $sql = "UPDATE sc_assignments_tbl 
                SET fk_strand_id = :fk_strand_id, 
                    fk_year_id = :fk_year_id, 
                    fk_section_id = :fk_section_id 
                WHERE assignment_id = :assignment_id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':fk_strand_id', $fk_strand_id, PDO::PARAM_INT);
        $stmt->bindParam(':fk_year_id', $fk_year_id, PDO::PARAM_INT);
        $stmt->bindParam(':fk_section_id', $fk_section_id, PDO::PARAM_INT);
        $stmt->bindParam(':assignment_id', $assignment_id, PDO::PARAM_INT);

        // Execute the query
        if ($stmt->execute()) {
            echo "<script>alert('Student information updated successfully!'); window.location.href='ViewStudent.php?assignment_id=$assignment_id';</script>";
        } else {
            throw new Exception("Update failed. Please try again.");
        }
    } catch (Exception $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.history.back();</script>";
}
?>
