<?php
session_start();
include("../server_connection/db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST["subject_id"], $_POST["section_id"], $_POST["f_assignment_id"])) {
        echo "Missing required parameters.";
        exit();
    }

    $current_datetime = date('Y-m-d H:i:s');
    $subject_id = $_POST["subject_id"];
    $section_id = $_POST["section_id"];
    $f_assignment_id = $_POST["f_assignment_id"];
    $updt_ts = $current_datetime;
    $grade_status = 'Approved'; // ✅ Fixed missing semicolon

    // SQL Query using PDO placeholders
    $sql = "UPDATE student_grades_tbl 
            SET grade_status = :grade_status, updt_ts = :updt_ts
            WHERE student_grade_id IN (
                SELECT grade.student_grade_id 
                FROM subjects_taking_tbl AS subs_t
                LEFT JOIN student_grades_tbl AS grade ON subs_t.s_taking_id = grade.fk_student_subject_id 
                LEFT JOIN subjects_tbl AS sub ON subs_t.fk_subject_id = sub.subject_id
                INNER JOIN sc_assignments_tbl AS sc ON subs_t.fk_assignment_id = sc.assignment_id
                LEFT JOIN sections_tbl AS sec ON sc.fk_section_id = sec.section_id
                INNER JOIN students_tbl AS stud ON sc.fk_student_id = stud.student_id 
                LEFT JOIN users_tbl AS teach ON grade.fk_faculty_id = teach.user_id
                WHERE sub.subject_id = :subject_id AND sec.section_id = :section_id
            )";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':grade_status', $grade_status);
        $stmt->bindParam(':updt_ts', $updt_ts);
        $stmt->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
        $stmt->bindParam(':section_id', $section_id, PDO::PARAM_INT);

        $stmt->execute();

        if ($stmt->rowCount() > 0) { // ✅ `rowCount()` works with PDO
            echo "<script>
                alert('Grade Updated Successfully!');
                window.location.href = 'ViewAssignedSubject.php?f_assignment_id=$f_assignment_id';
            </script>";
        } else {
            echo "<script>
                alert('No changes made or invalid student grade ID.');
                window.location.href = 'ViewAssignedSubject.php?f_assignment_id=$f_assignment_id';
            </script>";
        }

    } catch (Exception $e) {
        $_SESSION["message"] = "Error: " . $e->getMessage();
    }

    header("Location: ViewAssignedSubject.php?f_assignment_id=$f_assignment_id");
    exit();
}
?>
