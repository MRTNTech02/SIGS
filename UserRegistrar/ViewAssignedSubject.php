<?php 
    session_start();
    include ("../server_connection/db_connect.php");

    // Fixing session validation condition
    if (empty($_SESSION["id_number"]) || empty($_SESSION["user_password"])) {
        header("location: index.php");
        exit();
    }

    if (!empty($_SESSION["id_number"])){
        $user_id = $_SESSION["user_id"];

        $sql = "SELECT * FROM users_tbl WHERE user_id=:user_id";
        try {
            $result = $conn->prepare($sql);
            $result->execute(['user_id' => $user_id]);

            if($result->rowCount() > 0){
                $data = $result->fetch(PDO::FETCH_ASSOC);
                $registrar_name = $data['u_fname'] . ' ' . $data['u_lname'] . ' ' . $data['u_suffix'];
                $id_number = $data['id_number'];
                $user_profile = $data['user_profile'];
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    if (isset($_GET['logout'])) {
        session_destroy();
        unset($_SESSION['id_number']);
        header("location: index.php");
        exit();
    }
?>

<!-- fetching record -->
<?php
    if (isset($_GET['f_assignment_id'])) {
        $f_assignment_id = $_GET['f_assignment_id'];

        $sql = "SELECT * FROM
        faculty_assignments_tbl AS FA INNER JOIN subjects_tbl AS A ON FA.fk_subject_id=A.subject_id
        INNER JOIN year_levels_tbl AS B ON FA.fk_year_id=B.year_level_id 
        INNER JOIN strands_tbl AS C ON FA.fk_strand_id=C.strand_id 
        INNER JOIN sections_tbl AS D ON FA.fk_section_id=section_id 
        INNER JOIN users_tbl As E ON FA.fk_user_id=E.user_id WHERE FA.f_assignment_id='$f_assignment_id'";
        try{
        $result = $conn->prepare($sql);
        $result->execute();

        if($result->rowCount()>0){
            $data = $result->fetch(PDO::FETCH_ASSOC);
            $subject_name = $data["subject_name"];
            $yl_name = $data["yl_name"];
            $strand_nn = $data["strand_nn"];
            $section_id = $data["section_id"];
            // $student_id = $data["student_id"];
            $subject_id = $data["subject_id"];
        }
        }catch(Exception $e){
        echo "Error" . $e;
        }
    };
?>
<!-- getting missing grade count -->
<?php
    if (isset($_GET['f_assignment_id'])) {
        $f_assignment_id = $_GET['f_assignment_id'];

        $sql = "SELECT count(*) AS missingGrades
        FROM subjects_taking_tbl AS subs_t
        LEFT JOIN student_grades_tbl AS grade ON subs_t.s_taking_id = grade.fk_student_subject_id 
        LEFT JOIN subjects_tbl AS sub ON subs_t.fk_subject_id = sub.subject_id
        LEFT JOIN sc_assignments_tbl AS sc ON subs_t.fk_assignment_id = sc.assignment_id
        LEFT JOIN sections_tbl AS sec ON sc.fk_section_id=sec.section_id
        LEFT JOIN students_tbl AS stud ON sc.fk_student_id = stud.student_id 
        LEFT JOIN users_tbl AS teach ON grade.fk_faculty_id = teach.user_id
        WHERE sub.subject_id = $subject_id
        AND sec.section_id = $section_id
        AND grade.student_grade IS NULL";
        try{
        $result = $conn->prepare($sql);
        $result->execute();

        if($result->rowCount()>0){
            $data = $result->fetch(PDO::FETCH_ASSOC);
            $missingGrades = $data["missingGrades"];
        }
        }catch(Exception $e){
        echo "Error" . $e;
        }
    };
?>
<!-- getting Pending count -->
<?php
    if (isset($_GET['f_assignment_id'])) {
        $f_assignment_id = $_GET['f_assignment_id'];

        $sql = "SELECT count(*) AS countPending
        FROM subjects_taking_tbl AS subs_t
        LEFT JOIN student_grades_tbl AS grade ON subs_t.s_taking_id = grade.fk_student_subject_id 
        LEFT JOIN subjects_tbl AS sub ON subs_t.fk_subject_id = sub.subject_id
        LEFT JOIN sc_assignments_tbl AS sc ON subs_t.fk_assignment_id = sc.assignment_id
        LEFT JOIN sections_tbl AS sec ON sc.fk_section_id=sec.section_id
        LEFT JOIN students_tbl AS stud ON sc.fk_student_id = stud.student_id 
        LEFT JOIN users_tbl AS teach ON grade.fk_faculty_id = teach.user_id
        WHERE sub.subject_id = $subject_id
        AND sec.section_id = $section_id
        AND grade.grade_status = 'Pending'";
        try{
        $result = $conn->prepare($sql);
        $result->execute();

        if($result->rowCount()>0){
            $data = $result->fetch(PDO::FETCH_ASSOC);
            $countPending = $data["countPending"];
        }
        }catch(Exception $e){
        echo "Error" . $e;
        }
    };
?>
<!-- getting fetch count -->
<?php
    if (isset($_GET['f_assignment_id'])) {
        $f_assignment_id = $_GET['f_assignment_id'];

        $sql = "SELECT count(*) AS countFetch
        FROM subjects_taking_tbl AS subs_t
        LEFT JOIN student_grades_tbl AS grade ON subs_t.s_taking_id = grade.fk_student_subject_id 
        LEFT JOIN subjects_tbl AS sub ON subs_t.fk_subject_id = sub.subject_id
        LEFT JOIN sc_assignments_tbl AS sc ON subs_t.fk_assignment_id = sc.assignment_id
        LEFT JOIN sections_tbl AS sec ON sc.fk_section_id=sec.section_id
        LEFT JOIN students_tbl AS stud ON sc.fk_student_id = stud.student_id 
        LEFT JOIN users_tbl AS teach ON grade.fk_faculty_id = teach.user_id
        WHERE sub.subject_id = $subject_id
        AND sec.section_id = $section_id";
        try{
        $result = $conn->prepare($sql);
        $result->execute();

        if($result->rowCount()>0){
            $data = $result->fetch(PDO::FETCH_ASSOC);
            $countFetch = $data["countFetch"];
        }
        }catch(Exception $e){
        echo "Error" . $e;
        }
    };
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Assigned Subject</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="../../Bootstrap/css/bootstrap.min.css">
    <!-- Bootstrap Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include '../Assets/components/RegistrarNavbar.php'; ?>

    <div class="d-flex">
        <?php include '../Assets/components/RegistrarSidebar.php'; ?>

        <!-- Main content -->
        <div class="content p-4 flex-grow-1">
            <h4 class="text-muted"><?php echo $yl_name . ' | ' . $strand_nn . ' | ' . $subject_name ?></h4>
            <div class="card table-container">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <!-- Search input and button -->
                        <div class="input-group w-50">
                            <input type="text" class="form-control form-control-sm me-1" id="searchInput" placeholder="Search Name">
                            <button class="btn btn-success" id="searchButton">Search</button>
                        </div>
                        <?php 
                            if($missingGrades === 0){
                                if ($countPending === 0){
                                    if($countFetch === 0){
                                        echo "<span class='role-label label-warn'>No fetch records</span>";
                                    }else{
                                        echo "
                                            <span class='role-label label-success'>Grades are Approved</span>
                                        ";
                                    }
                                }else{
                                    echo "
                                        <a href='' class='btn btn-success'>
                                            Approve Grades
                                        </a>
                                    ";
                                }
                            }else{
                                echo "
                                    <span style='width: 350px;' class='role-label label-warn'>All students must be graded by Assigned Teacher</span>
                                ";
                            }
                        ?>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-striped" id="studentsTable">
                            <thead align="center">
                                <tr>
                                    <th>No.</th>
                                    <th>Student Name</th>
                                    <th>Grade</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody" align="center">
                                <?php
                                    $sql = "SELECT stud.s_fname, stud.s_lname, stud.s_suffix, sub.subject_name, teach.u_fname, 
                                    teach.u_lname, teach.u_suffix, subs_t.semester, subs_t.academic_year, grade.student_grade,
                                    grade.grade_status 
                                    FROM subjects_taking_tbl AS subs_t
                                    LEFT JOIN student_grades_tbl AS grade ON subs_t.s_taking_id = grade.fk_student_subject_id 
                                    LEFT JOIN subjects_tbl AS sub ON subs_t.fk_subject_id = sub.subject_id
                                    INNER JOIN sc_assignments_tbl AS sc ON subs_t.fk_assignment_id = sc.assignment_id
                                    LEFT JOIN sections_tbl AS sec ON sc.fk_section_id=sec.section_id
                                    INNER JOIN students_tbl AS stud ON sc.fk_student_id = stud.student_id 
                                    LEFT JOIN users_tbl AS teach ON grade.fk_faculty_id = teach.user_id
                                    WHERE sub.subject_id = $subject_id
                                    AND sec.section_id = $section_id";
                                    try
                                    {
                                        $result=$conn->prepare($sql);
                                        $result->execute();
                                        // $status = $_SESSION['status'];
                                        if($result->rowcount()>0)
                                        {
                                            $i=1;
                                            while($row=$result->fetch(PDO::FETCH_ASSOC))
                                            {
                                                echo "
                                                    <tr>
                                                        <td class='text-center'>{$i} </td>
                                                        <td>{$row["s_fname"]} {$row["s_lname"]} {$row["s_suffix"]}</td>
                                                        <td>{$row["student_grade"]}</td>";
                                                        if (empty($row["grade_status"])) {
                                                            echo "<td>No Submitted Grade</td>";
                                                        } else {
                                                            echo "<td>{$row["grade_status"]}</td>";
                                                        }    
                                                    echo "
                                                    </tr>";
                                                $i++;
                                                
                                            }
                                        }
                                    }
                                    catch(Exception $e)
                                    {
                                        echo "Unexpected error has been occured!" . $e ->getMessage();
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Select rows per page -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="d-inline-flex align-items-center">
                            <label for="rowsPerPage" class="me-2 mb-0">Rows per page:</label>
                            <select id="rowsPerPage" class="form-select">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                            </select>
                        </div>

                        <!-- Pagination controls -->
                        <nav>
                            <ul class="pagination mb-0" id="pagination"></ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="../Bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JavaScript -->
    <script>
      document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById("searchInput");
        const searchButton = document.getElementById("searchButton");
        const tableBody = document.getElementById("tableBody");
        const rowsPerPageSelect = document.getElementById("rowsPerPage");
        const pagination = document.getElementById("pagination");

        let rows = Array.from(tableBody.getElementsByTagName("tr"));
        let filteredRows = rows;
        let currentPage = 1;

        function renderTable() {
          const rowsPerPage = parseInt(rowsPerPageSelect.value);
          const start = (currentPage - 1) * rowsPerPage;
          const end = start + rowsPerPage;

          rows.forEach(row => row.style.display = "none");
          filteredRows.slice(start, end).forEach(row => row.style.display = "");

          renderPagination();
        }

        function renderPagination() {
          const rowsPerPage = parseInt(rowsPerPageSelect.value);
          const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
          pagination.innerHTML = "";

          for (let i = 1; i <= totalPages; i++) {
            const pageItem = document.createElement("li");
            pageItem.className = `page-item ${i === currentPage ? 'active' : ''}`;
            pageItem.innerHTML = `<a class="page-link" href="#">${i}</a>`;
            pageItem.addEventListener("click", function(e) {
              e.preventDefault();
              currentPage = i;
              renderTable();
            });
            pagination.appendChild(pageItem);
          }
        }

        function filterTable() {
          const filter = searchInput.value.toLowerCase();
          filteredRows = rows.filter(row => row.cells[1].textContent.toLowerCase().includes(filter));
          currentPage = 1;
          renderTable();
        }

        searchButton.addEventListener("click", filterTable);
        searchInput.addEventListener("input", filterTable);
        rowsPerPageSelect.addEventListener("change", function() {
          currentPage = 1;
          renderTable();
        });

        renderTable(); // Initial render
      });
    </script>


    <style>
        .table-container { 
            margin-top: 20px; 
            min-width: 100%; 
        }
        .table-responsive {
            height: 375px;
        }
        .content { transition: margin-left 0.3s ease; }
        @media (max-width: 992px) { .content { margin-left: 0; } }
        #rowsPerPage { width: auto; }
        .role-label { font-weight: bold; padding: 2px 8px; border-radius: 12px; font-size: 12px; display: inline-block; width: 100px; text-align: center; }
        .label-success { background-color: #b2dba1; color: #3b7a00; }
        .label-warn { 
            background-color: #ffcc99; 
            color: #cc5200; 
        }
    </style>
</body>
</html>
