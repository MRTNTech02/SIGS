<?php
session_start();
include ("../server_connection/db_connect.php");

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
        try {
            $student_grade = $_POST["student_grade"];
            $fk_student_subject_id = $_POST["fk_student_subject_id"];
            $fk_faculty_id = $_POST["fk_faculty_id"];
            $f_assignment_id = $_POST["f_assignment_id"];

            if (empty($student_grade) || empty($fk_student_subject_id) || empty($fk_faculty_id)) {
                throw new Exception("Required fields are missing.");
            }

            $sql = "INSERT INTO student_grades_tbl (student_grade, fk_student_subject_id, fk_faculty_id) 
            VALUES (:student_grade, :fk_student_subject_id, :fk_faculty_id)";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ":student_grade" => $student_grade,
                ":fk_student_subject_id" => $fk_student_subject_id,
                ":fk_faculty_id" => $fk_faculty_id,
            ]);

            echo "<script>
                alert('Grade Submitted Successfully!');
                window.location.href = 'students.php?f_assignment_id=$f_assignment_id';
            </script>";
            exit();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

?>
