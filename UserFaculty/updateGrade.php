<?php
    session_start();
    include ("../server_connection/db_connect.php");
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!isset($_POST["student_grade_id"], $_POST["student_grade"])) {
            echo "Missing required parameters.";
            exit();
        }
        $current_datetime = date('Y-m-d H:i:s');
        $student_grade_id = $_POST["student_grade_id"];
        $student_grade = $_POST["student_grade"];
        $f_assignment_id = $_POST["f_assignment_id"];
        $updt_ts = $current_datetime;

        $sql = "UPDATE student_grades_tbl SET student_grade = :student_grade,
        updt_ts = :updt_ts WHERE student_grade_id = :student_grade_id";

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ":student_grade" => $student_grade,
                ":student_grade_id" => $student_grade_id,
                ":updt_ts" => $updt_ts
            ]);

            if ($stmt->rowCount() > 0) {
                echo "<script>
                    alert('Grade Updated Successfully!');
                    window.location.href = 'students.php?f_assignment_id=$f_assignment_id';
                </script>";
            } else {
                echo "<script>
                    alert('No changes made or invalid student grade ID.');
                    window.location.href = 'students.php?f_assignment_id=$f_assignment_id';
                </script>";
            }
        } catch (Exception $e) {
            $_SESSION["message"] = "Error: " . $e->getMessage();
        }

        header("Location: students.php?f_assignment_id=$f_assignment_id");
        exit();
    }
?>
