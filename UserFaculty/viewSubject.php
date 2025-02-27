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
            <div class="d-flex justify-content-between">
                <h4 class="text-muted">Grade Level - Section</h4>
                <a href="submitGrade.php" class="link-offset-2 link-underline link-underline-opacity-0">
                <button type="button" class="btn btn-warning d-flex justify-content-center align-items-center text-center p-1">
                    <i class="fas fa-arrow-left"></i> 
                    <span class="text-wrap p-1" href="/submitGrade.php">Go Back</span> 
                </button>
                </a>
            </div>
            <div class="card table-container" >
                <div class="card-body">
                <div class="d-flex mb-3 p-1 bg-success rounded text-white fw-bold justify-content-between">Subject Name</div>
                <div class="row">
                    <!-- First Filter -->
                    <div class="col-12 col-sm-4 col-md-4 mb-2">
                        <div class="d-flex mt-1 w-100 justify-content-center align-items-center">
                            <h6 class="text-muted p-1">S.Y. 2024-2025</h6>
                            <h6 class="text-muted p-1">1st Semester</h6>
                        </div>
                    </div>

                    <!-- Input Grades Button -->
                    <div class="col-12 col-sm-4 col-md-4 mb-2">
                        <a href="#" class="btn border-black text-black bg-white text-start w-100">
                            <i class="fas fa-plus"></i> Input Grades
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12 col-sm-4 col-md-4 mb-2">
                        <a href="#" class="btn btn-success text-start w-100">
                            <i class="fa fa-check-circle"></i> Submit
                        </a>
                    </div>
                </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table" id="studentsTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Grade</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody" align="center">
                                <?php
                                    $sql = "SELECT SC.assignment_id, A.student_id, A.s_fname, A.s_lname, A.s_status,
                                    B.student_grade_id, B.student_grade
                                    FROM sc_assignments_tbl  AS SC INNER JOIN
                                    students_tbl AS A on SC.fk_student_id=A.student_id
                                    INNER JOIN student_grades_tbl AS B ON SC.fk_student_id=B.student_grade_id";
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
                                                        <td align='left'>{$row["s_lname"]}, {$row["s_fname"]} </td>
                                                        <td align='center'>{$row["student_grade"]}</td>
                                                        <td>";
                                                        
                                                        ?>
                                                        <!-- <td class='text-center'> -->
                                                            <!-- <--?php  
                                                            // echo "
                                                            //     <a href='ViewUser.php?user_id={$row["subject_id"]}' class='btn btn-info btn-sm'>
                                                            //         <i class='fas fa-eye'></i>
                                                            //     </a>
                                                            //     <a href='EditUser.php?user_id={$row["subject_id"]}' class='btn btn-warning btn-sm'>
                                                            //         <i class='fas fa-pencil'></i>
                                                            //     </a>
                                                            //     <a href='' class='btn btn-danger btn-sm'>
                                                            //         <i class='fas fa-trash'></i>
                                                            //     </a>
                                                            // "; 
                                                            ?>
                                                        </td--> 
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
            height: 40vh;
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
