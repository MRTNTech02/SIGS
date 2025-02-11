<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../Bootstrap/css/bootstrap.min.css">
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
        <div class="content p-4 w-100">
            <h4 class="text-muted mb-4">Add New User</h4>
            <div class="main-content">
                <div class="card">
                    <div class="card-body">
                        <form>
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="firstName">First Name</label>
                                    <input type="text" class="form-control" id="firstName" placeholder="First Name" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="middleName">Middle Name</label>
                                    <input type="text" class="form-control" id="middleName" placeholder="Middle Name">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="lastName">Last Name</label>
                                    <input type="text" class="form-control" id="lastName" placeholder="Last Name" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="suffix">Suffix</label>
                                    <input type="text" class="form-control" id="suffix" placeholder="Suffix (Optional)">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" placeholder="Email" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="sex">Sex</label>
                                    <select id="sex" class="form-control" required>
                                        <option value="" disabled selected>Select Sex</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="birthdate">Birthdate</label>
                                    <input type="date" class="form-control" id="birthdate" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="password">Password</label>
                                    <input type="text" class="form-control" id="password" value="UserDefaultPassword123" disabled>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="role">Role</label>
                                    <select id="role" class="form-control" required>
                                        <option value="" disabled selected>Select Role</option>
                                        <option value="Registrar">Registrar</option>
                                        <option value="Faculty">Faculty</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success">Create User</button>
                            <a href="user_management.php" class="btn btn-secondary"> Cancel </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        @media (max-width: 768px) {
            .d-flex {
                flex-wrap: wrap;  
            }
            .d-flex > * {
                margin-bottom: 5px; 
            }
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
