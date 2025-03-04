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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Assigned Subjects</title>
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
            <h4 class="text-muted">My Assigned Subjects</h4>
            <div class="card table-container">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <!-- Search input and button -->
                        <div class="input-group w-50">
                            <input type="text" class="form-control form-control-sm me-1" id="searchInput" placeholder="Search Name">
                            <button class="btn btn-success" id="searchButton">Search</button>
                        </div>
                        <!-- <div class="btn-group">
                                <button type="button" class="btn border-black dropdown-toggle" 
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-filter"></i> Filter by 
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <button class="dropdown-item filter-btn" data-filter="Subject">Subject</button>
                                    <button class="dropdown-item filter-btn" data-filter="Strand">Strand</button>
                                    <button class="dropdown-item filter-btn" data-filter="Grade Level">Grade Level</button>
                                    <button class="dropdown-item filter-btn" data-filter="Section">Section</button>
                                    <button class="dropdown-item filter-btn" data-filter="Status">Status</button>
                                </div>
                            </div> -->
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table" id="studentsTable">
                            <thead align="center">
                                <tr>
                                    <th>Subject</th>
                                    <th>Grade Level</th>
                                    <th>Strand</th>
                                    <th>Section</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody" align="center">
                                <?php
                                    $sql = "SELECT 
                                    subjects_tbl.subject_id, 
                                    subjects_tbl.subject_name, 
                                    strands_tbl.strand_nn, 
                                    year_levels_tbl.yl_name, 
                                    sections_tbl.section_id, 
                                    sections_tbl.section_name, 
                                    subjects_tbl.subject_status 
                                    FROM subjects_tbl
                                    INNER JOIN strands_tbl ON strands_tbl.strand_id = subjects_tbl.subject_id
                                    INNER JOIN year_levels_tbl ON year_levels_tbl.year_level_id = subjects_tbl.subject_id
                                    INNER JOIN sections_tbl ON sections_tbl.section_id = subjects_tbl.subject_id";
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
                                                        <td>{$row["subject_name"]}</td>
                                                        <td>{$row["yl_name"]}</td>
                                                        <td>{$row["strand_nn"]}</td>
                                                        <td>{$row["section_name"]}</td>
                                                        <td>{$row["subject_status"]}</td>
                                                        ";
                                                        ?>
                                                        <td class='text-center'>
                                                            <?php 
                                                            echo "
                                                                <a href='viewSubject.php?subject_id={$row["subject_id"]}section_id={$row["section_id"]}' class='btn btn-info btn-sm'>
                                                                    <i class='fas fa-eye'></i>
                                                                </a>
                                                                <a href='' class='btn btn-danger btn-sm'>
                                                                    <i class='fas fa-trash'></i>
                                                                </a>
                                                            "; 
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php
                                                $i++;
                                            }
                                        }
                                        else
                                        {
                                            echo "<tr><tdv colspan = '6'> No records found. </td></tr>";
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
        .label-faculty { background-color: #b2dba1; color: #3b7a00; }
        .label-registrar { background-color: #ffcc99; color: #cc5200; }
        
    </style>
</body>
</html>
