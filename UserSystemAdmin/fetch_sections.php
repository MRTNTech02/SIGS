<?php
    include("../server_connection/db_connect.php");

    if (isset($_POST["fk_strand_id"]) && isset($_POST["fk_year_id"])) {
        $fk_strand_id = $_POST["fk_strand_id"];
        $fk_year_id = $_POST["fk_year_id"];
    
        $sql = "SELECT section_id, section_name FROM sections_tbl WHERE fk_strand_id = ? AND fk_year_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$fk_strand_id, $fk_year_id]);
    
        $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        if ($sections) {
            foreach ($sections as $section) {
                echo "<option value='{$section["section_id"]}'>{$section["section_name"]}</option>";
            }
        } else {
            echo "<option value=''>No sections available</option>";
        }
    }
?>
