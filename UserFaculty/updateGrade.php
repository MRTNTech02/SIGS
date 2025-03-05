<?php
include ("../server_connection/db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['student_grade_id']) && isset($_POST['student_grade'])) {
        $student_grade_id = $_POST['student_grade_id'];
        $student_grade = $_POST['student_grade'];

        // Ensure the grade is a valid number
        if (!is_numeric($student_grade) || $student_grade < 0 || $student_grade > 100) {
            echo "Invalid grade value.";
            exit;
        }

        try {
            $sql = "INSERT student_grades_tbl SET student_grade = :student_grade WHERE student_grade_id = :student_grade_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':student_grade', $student_grade);
            $stmt->bindParam(':student_grade_id', $student_grade_id);
            
            if ($stmt->execute()) {
                echo "Grade updated successfully!";
            } else {
                echo "Failed to update grade.";
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Missing data.";
    }
} else {
    echo "Invalid request.";
}
?>
