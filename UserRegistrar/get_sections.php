<?php
    include("../server_connection/db_connect.php");

    if (isset($_POST['strand_id']) && isset($_POST['year_level_id'])) {
        $strand_id = $_POST['strand_id'];
        $year_level_id = $_POST['year_level_id'];

        $sql = "SELECT section_id, section_name FROM sections_tbl WHERE fk_strand_id = :strand_id AND fk_year_id = :year_level_id";
        
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':strand_id', $strand_id, PDO::PARAM_INT);
            $stmt->bindParam(':year_level_id', $year_level_id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo "<option value=''>Select Section</option>";
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='{$row["section_id"]}'>{$row["section_name"]}</option>";
                }
            } else {
                echo "<option value=''>No sections available</option>";
            }
        } catch (Exception $e) {
            echo "<option value=''>Error loading sections</option>";
        }
    }
?>
