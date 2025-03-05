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
<?php
    try {
    $subject_id = isset($_GET['subject_id']) && is_numeric($_GET['subject_id']) ? intval($_GET['subject_id']) : 0;
    $section_id = isset($_GET['section_id']) ? $_GET['section_id'] : null;
    $yl_name = isset($_GET['yl_name']) ? $_GET['yl_name'] : null;
    $strand_nn = isset($_GET['strand_nn']) ? $_GET['strand_nn'] : null;
    // Fetch section, year level, and strand details for the selected subject
    $section_name = null;
    $subject_name = null;
        $sql = "SELECT SUB.*, GRADE.student_grade FROM student_grades_tbl AS GRADE 
            inner JOIN (SELECT SUB.subject_name, ST.s_taking_id AS fk_student_subject_id, TEACH.user_id AS fk_faculty_id, SA.fk_student_id as STUDENT, STUD.student_id, STUD.s_fname, SEC.section_name, STRAND.strand_nn, YL.yl_name, SEC.section_id, STRAND.strand_id, YL.year_level_id, ST.fk_subject_id,
            TEACH.u_fname FROM faculty_assignments_tbl AS FA 
            INNER JOIN subjects_tbl AS SUB ON FA.fk_subject_id=SUB.subject_id
            INNER JOIN users_tbl AS TEACH ON FA.fk_user_id=TEACH.user_id
            INNER JOIN subjects_taking_tbl AS ST ON SUB.subject_id=ST.fk_subject_id
            INNER JOIN sc_assignments_tbl AS SA ON ST.fk_assignment_id=SA.assignment_id
            INNER JOIN students_tbl AS STUD ON SA.fk_student_id=STUD.student_id 
            INNER JOIN year_levels_tbl as YL ON SA.fk_year_id=YL.year_level_id
            INNER JOIN strands_tbl as STRAND ON SA.fk_strand_id=STRAND.strand_id
            INNER JOIN sections_tbl as SEC ON SA.fk_section_id=SEC.section_id
                    WHERE SEC.section_id = 1
            AND TEACH.user_id = 3
            AND STRAND.strand_id = 1
            AND YL.year_level_id = 2
            AND ST.fk_subject_id = 1) AS SUB 
            ON GRADE.fk_student_subject_id=SUB.fk_student_subject_id
            AND GRADE.fk_faculty_id = SUB.fk_faculty_id
            WHERE SUB.section_id = 1
            AND SUB.fk_faculty_id = 3
            AND SUB.strand_id = 1
            AND SUB.year_level_id = 2
            AND SUB.fk_subject_id = 1;";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
        $stmt->execute();
        $section = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($section) {
            $yl_name = $section['yl_name'];
            $strand_nn = $section['strand_nn'];
            $section_name = $section['section_name'];
            $subject_name = $section['subject_name'];  // <-- Make sure this is added
        }
        
        if ($yl_name && $strand_nn && $section_name) {
            $sql = "SELECT SC.assignment_id, A.lrn_number, A.s_fname, A.s_lname, A.s_suffix, A.s_status,
                        B.yl_name, C.strand_nn, D.section_name
                    FROM sc_assignments_tbl AS SC
                    INNER JOIN students_tbl AS A ON SC.fk_student_id = A.student_id
                    INNER JOIN year_levels_tbl AS B ON SC.fk_year_id = B.year_level_id
                    INNER JOIN strands_tbl AS C ON SC.fk_strand_id = C.strand_id
                    INNER JOIN sections_tbl AS D ON SC.fk_section_id = D.section_id
                    WHERE B.yl_name = :yl_name AND C.strand_nn = :strand_nn AND D.section_name = :section_name";
    
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':yl_name', $yl_name, PDO::PARAM_STR);
            $stmt->bindParam(':strand_nn', $strand_nn, PDO::PARAM_STR);
            $stmt->bindParam(':section_name', $section_name, PDO::PARAM_STR);
    
            if (!$stmt->execute()) {
                print_r($stmt->errorInfo()); // Show errors if query fails
            }
        }
    } catch (Exception $e) {
        echo "<p class='text-danger'>Database Error: " . $e->getMessage() . "</p>";
    }
    
    // Fetch students enrolled in the selected subject's section, year level, and strand
    $students = [];
    if ($section) {
        try {
            $sql = "SELECT SC.assignment_id, SC.fk_strand_id, A.student_id, A.s_fname, A.s_lname, A.s_status,
                           B.student_grade_id, B.student_grade, ST.s_taking_id
                    FROM sc_assignments_tbl AS SC
                    INNER JOIN subjects_taking_tbl AS ST ON SC.assignment_id = ST.s_taking_id
                    INNER JOIN students_tbl AS A ON SC.fk_student_id = A.student_id
                    INNER JOIN student_grades_tbl AS B ON SC.fk_student_id = B.student_grade_id
                    GROUP BY SC.fk_section_id = :section_id AND ST.s_taking_id = :subject_id";
            
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':section_id', $section['section_id'], PDO::PARAM_INT);
            // $stmt->bindParam(':year_id', $section['yl_name'], PDO::PARAM_INT);
            // $stmt->bindParam(':strand_id', $section['strand_id'], PDO::PARAM_INT);
            $stmt->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
            $stmt->execute();
            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo "<p class='text-danger'>Error fetching students: " . $e->getMessage() . "</p>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Subject</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="../../Bootstrap/css/bootstrap.min.css">
    <!-- Bootstrap Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include '../Assets/components/Navbar.php'; ?>

    <div class="d-flex">
        <?php include '../Assets/components/FacultySidebar.php'; ?>

        <!-- Main content -->
        <div class="content p-4 flex-grow-1">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class='text-muted'>
                <?php echo($yl_name . ' ' . $strand_nn . ' ' . $section_name ); ?> 
                <i class="fa fa-angle-right mx-2"></i>
                <strong><?php echo htmlspecialchars($subject_name); ?></strong>
            </h4>

                <a href="submitGrade.php" class="link-offset-2 link-underline link-underline-opacity-0">
                <button type="button" class="btn btn-warning d-flex justify-content-center align-items-center text-center p-1">
                    <i class="fas fa-arrow-left"></i> 
                    <span class="text-wrap p-1" href="/submitGrade.php">Go Back</span> 
                </button>
                </a>
            </div>
            <div class="card table-container" >
                <div class="card-body">
                <div class="row">
                    <!-- First Filter -->
                    <div class="col-12 col-sm-6 col-md-6 mb-2">
                        <div class="d-flex mt-1 w-100 justify-content-center align-items-center">
                            <h6 class="text-muted p-1">S.Y. 2024-2025</h6>
                            <h6 class="text-muted p-1">1st Semester</h6>
                        </div>
                    </div>

                    <!-- Input Grades Button -->
                    <!-- <div class="col-12 col-sm-4 col-md-4 mb-2">
                        <a href="#" class="btn border-black text-black bg-white text-start w-100">
                            <i class="fas fa-plus"></i> Input Grades
                        </a>
                    </div> -->

                    <!-- Submit Button -->
                    <!-- <div class="col-12 col-sm-6 col-md-6 mb-2">
                    <div class="d-flex mt-1 w-100 justify-content-end align-items-center">
                        <a href="#" class="btn btn-success text-start justify-content-end">
                            <i class="fa fa-check-circle"></i> Submit
                        </a>
                    </div>
                    </div> -->
                </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table mb-2" id="studentsTable">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Grade</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                            <?php
                                if (!empty($students)) {
                                    $i = 1;
                                    foreach ($students as $row) {
                                        echo "<tr>
                                                <td>{$i}</td>
                                                <td>{$row["s_lname"]}, {$row["s_fname"]} </td>
                                                <td>
                                                    <span align='center' id='gradeText_{$i}'>".htmlspecialchars($row['student_grade'])."</span>
                                                </td>
                                                <td>
                                                    <input type='number' id='gradeInput_{$i}' value='".htmlspecialchars($row['student_grade'])."' style='display:none;' max='100' min='0' required>
                                                    <button class='btn btn-sm btn-warning' onclick='editGrade({$i})'>
                                                        <i class='fas fa-pencil'></i>
                                                    </button>
                                                    <button class='btn btn-sm btn-success' id='saveBtn_{$i}' onclick='saveGrade({$i}, 
                                                    ".$row['student_grade_id'].")' style='display:none;'>Save</button>
                                                </td>
                                            </tr>";
                                        $i++;
                                    }
                                } else {
                                    echo "<tr><td colspan='4' class='text-center'>No students found.</td></tr>";
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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
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

        function saveGrade(index, studentGradeId) {
            let grade = document.getElementById("gradeInput_" + index).value;

            // AJAX request to send data to the backend
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "updateGrade.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // alert(xhr.responseText); // Debugging: See response from the server
                    document.getElementById("gradeText_" + index).innerText = grade;
                    document.getElementById("gradeText_" + index).style.display = "inline";
                    document.getElementById("gradeInput_" + index).style.display = "none";
                    document.getElementById("saveBtn_" + index).style.display = "none";
                }
            };

            // Send data
            xhr.send("student_grade_id=" + studentGradeId + "&student_grade=" + grade);
        }
    </script>

    <style>
        .table-container { 
            margin-top: 20px; 
            min-width: 100%; 
        }
        .table-responsive {
            height: 40vh;
        }
        .content { transition: margin-left 0.3s ease; }
        @media (max-width: 992px) { .content { margin-left: 0; } }
        #rowsPerPage { width: auto; }
        .role-label { font-weight: bold; padding: 2px 8px; border-radius: 12px; font-size: 12px; display: inline-block; width: 100px; text-align: center; }
        .label-faculty { background-color: #b2dba1; color: #3b7a00; }
        .label-registrar { background-color: #ffcc99; color: #cc5200; }
        
    </style>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<script src="../Bootstrap/js/bootstrap.bundle.min.js"></script> 
