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
    $section_id = isset($_GET['section_id']) ? $_GET['section_id'] : null;
    $yl_name = isset($_GET['yl_name']) ? $_GET['yl_name'] : null;
    $strand_nn = isset($_GET['strand_nn']) ? $_GET['strand_nn'] : null;
    
    $section_name = null;

    if ($section_id) {
        $sql_section = "SELECT B.yl_name, C.strand_nn, D.section_name
                        FROM sections_tbl AS D
                        INNER JOIN year_levels_tbl AS B ON D.fk_year_id = B.year_level_id
                        INNER JOIN strands_tbl AS C ON D.fk_strand_id = C.strand_id
                        WHERE D.section_id = :section_id";

        $stmt_section = $conn->prepare($sql_section);
        $stmt_section->bindParam(':section_id', $section_id, PDO::PARAM_INT);
        $stmt_section->execute();
        $section = $stmt_section->fetch(PDO::FETCH_ASSOC);

        if ($section) {
            $yl_name = $section['yl_name'];
            $strand_nn = $section['strand_nn'];
            $section_name = $section['section_name'];
        }
    }

    $students = [];

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

        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students</title>
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
        <div class="d-flex justify-content-between">
            <h4 class="text-muted"> 
                <a class="link-offset-2 link-underline link-underline-opacity-0 text-success" 
                    href="gradeSection.php">Year & Section 
                </a>
                <i class="fa fa-angle-right"></i>
                <?php echo($yl_name . ' ' . $strand_nn . ' ' . $section_name ); ?>
            </h4>
                <a href="gradeSection.php" class="link-offset-2 link-underline link-underline-opacity-0">
                    <button type="button" class="btn btn-warning d-flex justify-content-center align-items-center text-center p-1">
                        <i class="fas fa-arrow-left"></i> 
                        <span class="text-wrap p-1">Go Back</span> 
                    </button>
                </a>
            </div>
            <div class="card table-container">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-1">
                        <!-- Search input and button -->
                        <div class="input-group w-50">
                            <input type="text" class="form-control form-control-sm me-1" id="searchInput" placeholder="Search Name">
                            <button class="btn btn-success" id="searchButton">Search</button>
                        </div>
                        <a href="AddStudent.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Student
                        </a>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table" id="studentsTable">
                            <thead align="center">
                                <tr>
                                    <th>No.</th>
                                    <th>LRN</th>
                                    <th>Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody" align="center">
                            <?php
                                if (!empty($students)) {
                                    $i = 1;
                                    foreach ($students as $row) {
                                        echo "<tr>
                                                <td>{$i}</td>
                                                <td>{$row["lrn_number"]}</td>
                                                <td>{$row["s_lname"]}, {$row["s_fname"]} {$row["s_suffix"]}</td>
                                                <td>
                                                    <a href='ViewUser.php?assignment_id={$row["assignment_id"]}' class='btn btn-info btn-sm'><i class='fas fa-eye'></i></a>
                                                    <a href='EditUser.php?assignment_id={$row["assignment_id"]}' class='btn btn-warning btn-sm'><i class='fas fa-pencil'></i></a>
                                                    <a href='#' class='btn btn-danger btn-sm'><i class='fas fa-trash'></i></a>
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
          filteredRows = rows.filter(row => row.cells[2].textContent.toLowerCase().includes(filter));
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
        
        document.querySelectorAll('.filter-btn').forEach(button => {
        button.addEventListener('click', function() {
            const filterText = this.getAttribute('data-filter');
            document.getElementById('filterText').textContent = `Filter by ${filterText}`;
            });
        });
      });
    </script>

    <style>
        .table-container { 
            margin-top: 20px; 
            min-width: 100%; 
        }
        .table-responsive {
            height: 350px;
        }
        .content { transition: margin-left 0.3s ease; }
        @media (max-width: 992px) { .content { margin-left: 0; } }
        #rowsPerPage { width: auto; }
        .role-label { font-weight: bold; padding: 2px 8px; border-radius: 12px; font-size: 12px; display: inline-block; width: 100px; text-align: center; }
        .label-faculty { background-color: #b2dba1; color: #3b7a00; }
        .label-registrar { background-color: #ffcc99; color: #cc5200; }
        
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
