<?php 
  session_start();
  include ("../server_connection/db_connect.php");
  if (empty($_SESSION["username"]) && empty ($_SESSION["a_password"])) {
    header("location: index.php");
  }
  if (!empty($_SESSION["username"])){
    $admin_id = $_SESSION["admin_id"];

    $sql = "SELECT * FROM admin_tbl WHERE admin_id=$admin_id";
    try{
      $result = $conn->prepare($sql);
      $result->execute();

      if($result->rowCount()>0){
        $data = $result->fetch(PDO::FETCH_ASSOC);
        $admin_name = $data['a_fname'] . ' ' . $data['a_lname'];
        $username = $data['username'];
      }
    }catch(Exception $e){
      echo "Error" . $e;
    }
  };
  if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: index.php");
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Monitoring</title>
    <link rel="stylesheet" href="../../Bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>
</head>
<body>
    <?php include '../Assets/components/Navbar.php'; ?>
    <div class="d-flex">
        <?php include '../Assets/components/AdminSidebar.php'; ?>
        <div class="content p-4 flex-grow-1">
            <h4 class="text-muted">Log Monitoring</h4>
            <div class="card table-container">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap">
                        <div class="d-flex align-items-center me-2">
                            <input type="text" class="form-control form-control-sm me-1" id="searchInput" placeholder="Search Name">
                            <button class="btn btn-success btn-sm" id="searchButton">Search</button>
                            <input type="date" class="form-control form-control-sm me-2" id="fromDate">
                            <input type="date" class="form-control form-control-sm me-2" id="toDate">
                            <button class="btn btn-primary btn-sm" id="exportButton">Export</button>
                        </div>
                    </div>
                    <div class="table-responsive" style="height: 70vh; overflow-y: auto;">
                        <table class="table table-striped" id="studentsTable">
                            <thead align="center">
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                    <th>Time Stamp</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody" align="center">
                                <?php
                                    $sql = "SELECT logs.log_id, 
                                        COALESCE(
                                            CONCAT(users.u_fname, ' ', users.u_lname, ' ', IFNULL(users.u_suffix, '')), 
                                            CONCAT(admins.a_fname, ' ', admins.a_lname, ' ', IFNULL(admins.a_suffix, ''))
                                        ) AS full_name, 
                                        logs.role, 
                                        logs.action, 
                                        logs.log_ts 
                                    FROM user_logs_tbl as logs
                                    LEFT JOIN users_tbl as users ON logs.fk_user_id = users.user_id 
                                    LEFT JOIN admin_tbl as admins ON logs.fk_admin_id = admins.admin_id
                                    ORDER BY logs.log_id DESC";
                                    try
                                    {
                                        $result=$conn->prepare($sql);
                                        $result->execute();
                                        if($result->rowcount()>0)
                                        {
                                            $i=1;
                                            while($row=$result->fetch(PDO::FETCH_ASSOC))
                                            {
                                                echo "
                                                    <tr>
                                                        <td class='text-center'>{$i} </td>
                                                        <td class='text-center'>{$row["full_name"]}</td>
                                                        <td>{$row["role"]}</td>
                                                        <td>{$row["action"]}</td>
                                                        <td>{$row["log_ts"]}</td>
                                                    </tr>";
                                                $i++;
                                            }
                                        }
                                        else
                                        {
                                            echo "<tr><td colspan='5'> No records found. </td></tr>";
                                        }
                                    }
                                    catch(Exception $e)
                                    {
                                        echo "Unexpected error has occurred!" . $e->getMessage();
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById("searchInput");
            const searchButton = document.getElementById("searchButton");
            const fromDate = document.getElementById("fromDate");
            const toDate = document.getElementById("toDate");
            const exportButton = document.getElementById("exportButton");
            const tableBody = document.getElementById("tableBody");

            function filterTable() {
                const filter = searchInput.value.toLowerCase();
                const fromDateValue = fromDate.value;
                const toDateValue = toDate.value;
                
                Array.from(tableBody.getElementsByTagName("tr")).forEach(row => {
                    const studentName = row.cells[1].textContent.toLowerCase();
                    const date = row.cells[4].textContent;
                    
                    const nameMatches = studentName.includes(filter);
                    const fromDateMatches = fromDateValue ? date >= fromDateValue : true;
                    const toDateMatches = toDateValue ? date <= toDateValue : true;
                    
                    row.style.display = (nameMatches && fromDateMatches && toDateMatches) ? "" : "none";
                });
            }

            function exportToExcel() {
                const workbook = XLSX.utils.book_new();
                const table = document.getElementById('studentsTable');
                const data = [['No.', 'Name', 'Role', 'Action', 'Time Stamp']];

                Array.from(tableBody.getElementsByTagName("tr")).forEach(row => {
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
            exportButton.addEventListener("click", exportToExcel);
        });
    </script>
</body>
</html>
