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
            $strand_id = $data["strand_id"];
            $year_level_id = $data["year_level_id"];
            
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
    <?php include '../Assets/components/FacultyNavbar.php'; ?>

    <div class="d-flex">
        <?php include '../Assets/components/FacultySidebar.php'; ?>

        <!-- Main content -->
        <div class="content p-4 flex-grow-1">
            <h4 class="text-muted"><?php echo $yl_name . '  ' . $strand_nn . '  ' . $subject_name ?></h4>
            <div class="card table-container">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <!-- Search input and button -->
                        <div class="input-group w-50">
                            <input type="text" class="form-control form-control-sm me-1" id="searchInput" placeholder="Search Name">
                            <button class="btn btn-success" id="searchButton">Search</button>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-striped" id="studentsTable">
                            <thead align="center">
                                <tr>
                                    <th>No.</th>
                                    <th>Student Name</th>
                                    <th>Grade</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody" align="center">
                                <?php
                                    $sql = "SELECT DISTINCT SUB.*, GRADE.student_grade, GRADE.student_grade_id  FROM student_grades_tbl AS GRADE 
                                    right JOIN (SELECT 
                                    SUB.subject_name, ST.s_taking_id AS fk_student_subject_id, 
                                    TEACH.user_id AS fk_faculty_id, SA.fk_student_id as STUDENT, 
                                    STUD.student_id, STUD.s_fname, STUD.s_lname, STUD.s_suffix,
                                    SEC.section_name, STRAND.strand_nn, YL.yl_name, SEC.section_id, 
                                    STRAND.strand_id, YL.year_level_id, ST.fk_subject_id, TEACH.u_fname ,
                                    ST.s_taking_id
                                    FROM faculty_assignments_tbl AS FA 
                                    INNER JOIN subjects_tbl AS SUB ON FA.fk_subject_id=SUB.subject_id
                                    INNER JOIN users_tbl AS TEACH ON FA.fk_user_id=TEACH.user_id
                                    INNER JOIN subjects_taking_tbl AS ST ON SUB.subject_id=ST.fk_subject_id
                                    INNER JOIN sc_assignments_tbl AS SA ON ST.fk_assignment_id=SA.assignment_id
                                    INNER JOIN students_tbl AS STUD ON SA.fk_student_id=STUD.student_id 
                                    INNER JOIN year_levels_tbl as YL ON SA.fk_year_id=YL.year_level_id
                                    INNER JOIN strands_tbl as STRAND ON SA.fk_strand_id=STRAND.strand_id
                                    INNER JOIN sections_tbl as SEC ON SA.fk_section_id=SEC.section_id
                                    WHERE SEC.section_id = $section_id
                                    AND TEACH.user_id = $user_id
                                    AND STRAND.strand_id = $strand_id
                                    AND YL.year_level_id = $year_level_id
                                    AND ST.fk_subject_id = $subject_id) AS SUB 
                                    ON GRADE.fk_student_subject_id=SUB.fk_student_subject_id
                                    AND GRADE.fk_faculty_id = SUB.fk_faculty_id
                                    WHERE SUB.section_id = $section_id
                                    AND SUB.fk_faculty_id = $user_id
                                    AND SUB.strand_id = $strand_id
                                    AND SUB.year_level_id = $year_level_id
                                    AND SUB.fk_subject_id = $subject_id";
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
                                                    <tr data-assignment-id='<?= {$row["s_taking_id"]} ?>' data-subject-id='<?= {$row["fk_faculty_id"]} ?>'>
                                                        <td class='text-center'>{$i} </td>
                                                        <td>{$row["s_fname"]} {$row["s_lname"]} {$row["s_suffix"]}</td>
                                                        <td>
                                                        <form action='insertGrade.php' method='POST'>
                                                            <div class='grade-wrapper'>
                                                            <span align='center' class='grade-text' id='gradeText_{$i}'>".htmlspecialchars($row['student_grade'])."</span>
                                                            <input type='number' class='grade-input' id='gradeInput_{$i}'  name='student_grade' value='".htmlspecialchars($row['student_grade'])."' style='display:none;' max='100' min='0' required>
                                                            <input type='hidden' id='fk_student_subject_id' name='fk_student_subject_id' value='{$row["s_taking_id"]}' >
                                                            <input type='hidden' id='fk_faculty_id' name='fk_faculty_id' value='$user_id' >
                                                            <input type='hidden' id='f_assignment_id' name='f_assignment_id' value='$f_assignment_id' >

                                                            
                                                            </div>
                                                            <td>
                                                            <button class='btn btn-sm btn-warning' onclick='editGrade({$i})'>
                                                                <i class='fas fa-pencil'></i>
                                                            </button>
                                                            
                                                            <button type='submit' class='save-btn btn btn-sm btn-success' name='register'id='saveBtn_{$i}' style='display:none;'>Save</button>
                                                            </td>
                                                        </form>
                                                        </td>";
                                                         
                                                    echo "
                                                    </tr>";
                                                $i++;
                                                
                                            }
                                        }else{
                                            echo "No records fetched";
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
      function editGrade(index) {
            document.getElementById("gradeText_" + index).style.display = "none";
            document.getElementById("gradeInput_" + index).style.display = "inline";
            document.getElementById("saveBtn_" + index).style.display = "inline";
        }

        // function saveGrade(index, fkAssignmentId, fkSubjectId) {
        //     let grade = document.getElementById("gradeInput_" + index).value;

        //     // AJAX request to send data to the backend
        //     let xhr = new XMLHttpRequest(); 
        //     xhr.open("POST", "insertGrade.php", true);
        //     xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        //     xhr.onreadystatechange = function () {
        //         if (xhr.readyState == 4 && xhr.status == 200) {
        //             alert(xhr.responseText); // Debugging: See response from the server
        //             document.getElementById("gradeText_" + index).innerText = grade;
        //             document.getElementById("gradeText_" + index).style.display = "inline";
        //             document.getElementById("gradeInput_" + index).style.display = "none";
        //             document.getElementById("saveBtn_" + index).style.display = "none";
        //         }
        //     };

        //     // Send data
        //     xhr.send("s_taking_id=" + fkAssignmentId + "&fk_faculty_id=" + fkSubjectId + "&student_grade=" + grade);
        // }
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
        .grade-input {
            width: 50px; /* Adjust to match your text width */
            text-align: center;
        }
        .grade-wrapper {
            position: relative;
            display: inline-block;
            width: 50px; /* Match expected width */
        }

        .grade-text, .grade-input {
            vertical-align: middle;
            width: 100%;
            text-align: center;
        }

        .grade-input {
            top: 0;
            left: 0;
            display: 'flex';
        }
        .save-btn {
            position: absolute;
            right: 40px; 
        }
    </style>
</body>
</html>
