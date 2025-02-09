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
    <!-- SheetJS for Excel Export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>
</head>
<body>
    <?php include '../Assets/components/Navbar.php'; ?>
    <div class="d-flex">
        <?php include '../Assets/components/AdminSidebar.php'; ?>
        <!-- Main content -->
        <div class="content p-4 flex-grow-1">
            <h4 class="text-muted">Log Monitoring</h4>
            <div class="card table-container">
                <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
                    <!-- Search input and button -->
                    <div class="d-flex align-items-center me-2">
                        <input type="text" class="form-control form-control-sm me-1" id="searchInput" placeholder="Search Name">
                        <!-- Date filters -->
                        <input type="date" class="form-control form-control-sm me-2" id="fromDate">
                        <input type="date" class="form-control form-control-sm me-2" id="toDate">
                        <!-- Export button -->
                        <button class="btn btn-primary btn-sm" id="exportButton">Export to Excel</button>
                    </div>
                </div>
                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-striped" id="studentsTable">
                            <thead align="center">
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Time Stamp</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody" align="center">
                                <tr>
                                    <td>1</td>
                                    <td>John Doe</td>
                                    <td><span class="role-label label-faculty">FACULTY</span></td>
                                    <td>2023-10-01</td>
                                    <td>Log In</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Jane Smith</td>
                                    <td><span class="role-label label-registrar">REGISTRAR</span></td>
                                    <td>2023-10-02</td>
                                    <td>Log Out</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Emily Johnson</td>
                                    <td><span class="role-label label-faculty">FACULTY</span></td>
                                    <td>2023-10-03</td>
                                    <td>Log In</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Michael Brown</td>
                                    <td><span class="role-label label-student">STUDENT</span></td>
                                    <td>2023-10-04</td>
                                    <td>Log In</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Olivia Lee</td>
                                    <td><span class="role-label label-student">STUDENT</span></td>
                                    <td>2023-10-05</td>
                                    <td>Log In</td>
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
            const fromDate = document.getElementById("fromDate");
            const toDate = document.getElementById("toDate");
            const exportButton = document.getElementById("exportButton");
            const tableBody = document.getElementById("tableBody");
            const rowsPerPageSelect = document.getElementById("rowsPerPage");

            let rows = Array.from(tableBody.getElementsByTagName("tr"));
            let filteredRows = rows;

            function filterTable() {
                const filter = searchInput.value.toLowerCase();
                const fromDateValue = fromDate.value;
                const toDateValue = toDate.value;

                filteredRows = rows.filter(row => {
                    const studentName = row.cells[1].textContent.toLowerCase();
                    const date = row.cells[3].textContent;

                    const nameMatches = studentName.includes(filter);
                    const fromDateMatches = fromDateValue ? date >= fromDateValue : true;
                    const toDateMatches = toDateValue ? date <= toDateValue : true;

                    return nameMatches && fromDateMatches && toDateMatches;
                });
                updateRowsPerPage();
            }

            function updateRowsPerPage() {
                const rowsPerPage = parseInt(rowsPerPageSelect.value);
                const visibleRows = filteredRows.slice(0, rowsPerPage);

                rows.forEach(row => row.style.display = "none");
                visibleRows.forEach(row => row.style.display = "");
            }

            function exportToExcel() {
                const workbook = XLSX.utils.book_new();
                const table = document.getElementById('studentsTable');
                const data = [['No.', 'Name', 'Role', 'Time Stamp', 'Status']];

                filteredRows.forEach(row => {
                    if (row.style.display !== "none") {
                        const rowData = Array.from(row.cells).map(cell => cell.textContent);
                        data.push(rowData);
                    }
                });

                const worksheet = XLSX.utils.aoa_to_sheet(data);
                XLSX.utils.book_append_sheet(workbook, worksheet, "Logs");
                XLSX.writeFile(workbook, 'SIGSLogs.xlsx');
            }

            searchButton.addEventListener("click", filterTable);
            searchInput.addEventListener("input", filterTable);
            fromDate.addEventListener("change", filterTable);
            toDate.addEventListener("change", filterTable);
            rowsPerPageSelect.addEventListener("change", updateRowsPerPage);
            exportButton.addEventListener("click", exportToExcel);

            updateRowsPerPage();
        });
    </script>

    <style>
        .table-container { 
            margin-top: 20px; min-width: 100%; 
        }
        .table-responsive { 
            height: 400px; overflow-y: auto; 
        }
        .content { 
            transition: margin-left 0.3s ease; 
        }
        @media (max-width: 992px) { 
            .content { margin-left: 0; } 
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
        .d-flex > * { 
            margin-bottom: 5px; 
        }
        .form-control, .btn { 
            height: 35px; font-size: 14px; 
            padding: 4px 10px; 
        }
        .d-flex.flex-wrap > * { 
            flex: 1 1 auto; min-width: 120px; 
        }
        .form-control-sm, .btn-sm {
            height: 30px;  
            padding: 0.25rem 0.5rem;  
            font-size: 0.875rem;  
        }

        .d-flex > * {
            margin-bottom: 0;  
        }

        @media (max-width: 768px) {
            .d-flex {
                flex-wrap: wrap;  
            }
            .d-flex > * {
                margin-bottom: 5px; 
            }
        }
    </style>
</body>
</html>
