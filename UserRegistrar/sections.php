<?php 
  session_start();
  include ("../server_connection/db_connect.php");
  if (empty($_SESSION["id_number"]) && empty ($_SESSION["user_password"])) {
    header("location: index.php");
  }
  if (!empty($_SESSION["id_number"])){
    $user_id = $_SESSION["user_id"];

    $sql = "SELECT * FROM users_tbl WHERE user_id=$user_id";
    try{
      $result = $conn->prepare($sql);
      $result->execute();

      if($result->rowCount()>0){
        $data = $result->fetch(PDO::FETCH_ASSOC);
        $registrar_name = $data['u_fname'] . ' ' . $data['u_lname'] . ' ' . $data['u_suffix'] ;
        $id_number = $data['id_number'];
      }
    }catch(Exception $e){
      echo "Error" . $e;
    }
  };
  if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['id_number']);
    header("location: index.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Management</title>
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
            <h4 class="text-muted">Academic Management</h4>
                <!-- Navigation Buttons -->
                <div class="d-flex justify-content-start mb-3">
                    <a href="academic_management.php" class="btn btn-outline-dark me-2">Strands</a>
                    <a href="grade_levels.php" class="btn btn-outline-dark me-2">Grade Levels</a>
                    <a href="sections.php" class="btn btn-success me-2">Sections</a>
                    <a href="subjects.php" class="btn btn-outline-dark me-2">Subjects</a>
                </div>
            <div class="card table-container">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <!-- Search input and button -->
                        <div class="input-group w-50">
                            <input type="text" class="form-control form-control-sm me-1" id="searchInput" placeholder="Search Name">
                            <button class="btn btn-success" id="searchButton">Search</button>
                        </div>
                        <a href="AddSection.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            Add New Section Record
                        </a>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-striped" id="studentsTable">
                            <thead align="center">
                                <tr>
                                    <th>No.</th>
                                    <th>Section Name</th>
                                    <th>Strand</th>
                                    <th>Grade Level</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody" align="center">
                                <?php
                                    $sql = "SELECT A.section_name, A.section_id, B.strand_name, C.yl_name FROM sections_tbl AS A
                                    INNER JOIN strands_tbl AS B ON A.fk_strand_id=B.strand_id 
                                    INNER JOIN year_levels_tbl AS C ON A.fk_year_id=C.year_level_id";
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
                                                        <td class='text-center'>{$row["section_name"]}</td>
                                                        <td class='text-center'>{$row["strand_name"]}</td>
                                                        <td class='text-center'>{$row["yl_name"]}</td>
                                                        <td>
                                                            <a href='EditSection.php?section_id={$row["section_id"]}' class='btn btn-warning btn-sm'>
                                                                <i class='fas fa-pencil'></i>
                                                            </a>
                                                        </td>";
                                                        ?>
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
