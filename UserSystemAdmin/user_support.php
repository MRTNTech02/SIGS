<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="../../Bootstrap/css/bootstrap.min.css">
    <!-- Bootstrap Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php
        include '../Assets/components/Navbar.php';
    ?>
    <div class="d-flex">
        <?php
            include '../Assets/components/AdminSidebar.php';
        ?>
        <!-- Main content -->
        <div class="content p-4 flex-grow-1">
            <h4 class="text-muted">User Support</h4>
            <div class="card table-container">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="d-flex align-items-center me-2 w-50">
                            <input type="text" class="form-control form-control-sm me-1" id="searchInput" placeholder="Search Name">
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-striped" id="studentsTable">
                            <thead align="center">
                                <tr>
                                    <th>No.</th>
                                    <th>Ticket ID</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody" align="center">
                                <tr>
                                    <td>1</td>
                                    <td>John Doe</td>
                                    <td><span class="role-label label-faculty">FACULTY</span></td>
                                    <td>A</td>
                                    <td>
                                        <a href="view_user.php" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                        <a href="edit_user.php" class="btn btn-warning btn-sm"><i class="fas fa-pencil"></i></a>
                                        <a href="#" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Jane Smith</td>
                                    <td><span class="role-label label-registrar">REGISTRAR</span></td>
                                    <td>B</td>
                                    <td>
                                        <button class="btn btn-info btn-sm"><i class="fas fa-eye"></i></button>
                                        <button class="btn btn-warning btn-sm"><i class="fas fa-pencil"></i></button>
                                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Emily Johnson</td>
                                    <td><span class="role-label label-faculty">FACULTY</span></td>
                                    <td>C</td>
                                    <td>
                                        <button class="btn btn-info btn-sm"><i class="fas fa-eye"></i></button>
                                        <button class="btn btn-warning btn-sm"><i class="fas fa-pencil"></i></button>
                                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Michael Brown</td>
                                    <td><span class="role-label label-student">STUDENT</span></td>
                                    <td>A</td>
                                    <td>
                                        <button class="btn btn-info btn-sm"><i class="fas fa-eye"></i></button>
                                        <button class="btn btn-warning btn-sm"><i class="fas fa-pencil"></i></button>
                                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Olivia Lee</td>
                                    <td><span class="role-label label-student">STUDENT</span></td>
                                    <td>B</td>
                                    <td>
                                        <button class="btn btn-info btn-sm"><i class="fas fa-eye"></i></button>
                                        <button class="btn btn-warning btn-sm"><i class="fas fa-pencil"></i></button>
                                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Select rows per page -->
                    <div class="d-inline-flex align-items-center float-end">
                        <label for="rowsPerPage" class="me-2 mb-0">Rows per page:</label>
                        <select id="rowsPerPage" class="form-select">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                        </select>
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

        let rows = Array.from(tableBody.getElementsByTagName("tr"));
        let filteredRows = rows;
        let rowsPerPage = parseInt(rowsPerPageSelect.value);  // Default rows per page

        // Function to filter table based on search input
        function filterTable() {
          const filter = searchInput.value.toLowerCase();
          filteredRows = rows.filter(row => {
            const studentName = row.cells[1].textContent.toLowerCase();
            return studentName.includes(filter);
          });
          updateRowsPerPage();
        }

        function updateRowsPerPage() {
          const rowsPerPage = parseInt(rowsPerPageSelect.value);  
          const visibleRows = filteredRows.slice(0, rowsPerPage); 


          rows.forEach(row => row.style.display = "none");


          visibleRows.forEach(row => row.style.display = "");
          if (filteredRows.length > rowsPerPage) {
            document.querySelector(".table-responsive").style.overflowY = "auto";
          } else {
            document.querySelector(".table-responsive").style.overflowY = "hidden";
          }
        }

        searchButton.addEventListener("click", filterTable);
        searchInput.addEventListener("input", filterTable);
        rowsPerPageSelect.addEventListener("change", updateRowsPerPage);

        updateRowsPerPage(); 
      });
    </script>

    <style>
        .table-container {
            margin-top: 20px;
            min-width: 100%; 
        }
        .table-responsive {
            height: 400px;  
            overflow-y: auto; 
        }
        .content {
            transition: margin-left 0.3s ease;
        }
        @media (max-width: 992px) {
            .content {
                margin-left: 0;
            }
        }
        #rowsPerPage {
            width: auto;
        }
        .role-label {
            font-weight: bold;
            padding: 2px 8px;       
            border-radius: 12px;   
            font-size: 12px;        
            display: inline-block;
            width: 100px;           
            text-align: center;     
        }
        .label-faculty {
            background-color: #b2dba1; 
            color: #3b7a00;           
        }

        .label-student {
            background-color: #add8e6;  
            color: #004080;            
        }

        .label-registrar {
            background-color: #ffcc99; 
            color: #cc5200;          
        }
    </style>
</body>
</html>
