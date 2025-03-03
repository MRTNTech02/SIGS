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
<?php

    if (isset($_POST['toggleStatus'])) {
        $user_id = $_POST['user_id'];
        $new_status = $_POST['new_status'];
        $update_sql = "UPDATE users_tbl SET user_status = :new_status WHERE user_id = :user_id";
        $stmt = $conn->prepare($update_sql);
        $stmt->execute(['new_status' => $new_status, 'user_id' => $user_id]);
        header("Location: user_management.php");
    }
?>

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
    <?php include '../Assets/components/RegistrarNavbar.php'; ?>

    <div class="d-flex">
        <?php include '../Assets/components/RegistrarSidebar.php'; ?>

        <!-- Main content -->
        <div class="content p-4 flex-grow-1">
            <h4 class="text-muted">User Management</h4>
            <div class="card table-container">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <!-- Search input and button -->
                        <div class="input-group w-50">
                            <input type="text" class="form-control form-control-sm me-1" id="searchInput" placeholder="Search Name">
                            <button class="btn btn-success" id="searchButton">Search</button>
                        </div>
                        <a href="AddUser.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            Add New User
                        </a>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-striped" id="studentsTable">
                            <thead align="center">
                                <tr>
                                    <th>No.</th>
                                    <th>ID Number</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody" align="center">
                                <?php
                                    $sql = "SELECT * FROM users_tbl";
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
                                                        <td class='text-center'>{$row["id_number"]}</td>
                                                        <td>{$row["u_fname"]} {$row["u_lname"]} {$row["u_suffix"]}</td>
                                                        <td>";
                                                        if ($row["role"] == 'Faculty') {
                                                            echo "<span class='role-label label-faculty'>{$row["role"]}</span>";
                                                        } else {
                                                            echo "<span class='role-label label-registrar'>{$row["role"]}</span>";
                                                        }                                                        
                                                        echo "
                                                        </td>
                                                        <td>{$row["user_status"]} </td>";
                                                        ?>
                                                        <td class='text-center'>
                                                            <?php 
                                                                if ($row["role"] == 'Faculty'){
                                                                    echo 
                                                                    "<a href='ViewFacultyUser.php?user_id={$row["user_id"]}' class='btn btn-info btn-sm'>
                                                                        <i class='fas fa-eye'></i>
                                                                    </a>";
                                                                } else {
                                                                    echo
                                                                    "<a href='ViewRegistrarUser.php?user_id={$row["user_id"]}' class='btn btn-info btn-sm'>
                                                                        <i class='fas fa-eye'></i>
                                                                    </a>";
                                                                }
                                                                echo "
                                                                    <a href='EditUser.php?user_id={$row["user_id"]}' class='btn btn-warning btn-sm'>
                                                                        <i class='fas fa-pencil'></i>
                                                                    </a>
                                                                    <button class='btn " . ($row["user_status"] == 'Active' ? "btn-danger" : "btn-success") . " btn-sm toggle-status' data-user-id='{$row["user_id"]}' data-new-status='" . ($row["user_status"] == 'Active' ? "Inactive" : "Active") . "'>
                                                                        <i class='fas " . ($row["user_status"] == 'Active' ? "fa-toggle-off" : "fa-toggle-on") . "'></i>
                                                                    </button>
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

    <script>
        document.querySelectorAll('.toggle-status').forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                const newStatus = this.getAttribute('data-new-status');
                if (confirm(`Are you sure you want to set this user to ${newStatus}?`)) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '';
                    
                    const inputUserId = document.createElement('input');
                    inputUserId.type = 'hidden';
                    inputUserId.name = 'user_id';
                    inputUserId.value = userId;
                    form.appendChild(inputUserId);
                    
                    const inputNewStatus = document.createElement('input');
                    inputNewStatus.type = 'hidden';
                    inputNewStatus.name = 'new_status';
                    inputNewStatus.value = newStatus;
                    form.appendChild(inputNewStatus);
                    
                    const inputToggle = document.createElement('input');
                    inputToggle.type = 'hidden';
                    inputToggle.name = 'toggleStatus';
                    form.appendChild(inputToggle);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
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
