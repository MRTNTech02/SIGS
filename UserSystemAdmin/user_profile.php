<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Information</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
        <div class="content p-4">
            <h4 class="text-muted">Profile</h4>
            <div class="main-content">
                <div class="row">
                    <!-- Profile Picture Section -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header text-center">Profile Picture</div>
                            <div class="card-body text-center">
                                <img src="../Assets/img/profile_pictures/userdefaultprofile.jpg" class="img-fluid mb-3" alt="Profile Picture">
                                <p>Upload your profile picture here</p>
                                <input type="file" class="form-control-file">
                                <button class="btn btn-outline-secondary mt-3">Update Email or Password</button>
                            </div>
                        </div>
                    </div>

                    <!-- Information Section -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">Information</div>
                            <div class="card-body">
                                <form>
                                    <div class="form-row">
                                        <div class="form-group col-md-3">
                                            <label for="firstName">First Name</label>
                                            <input type="text" class="form-control" id="firstName" value="John">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="middleName">Middle Name</label>
                                            <input type="text" class="form-control" id="middleName">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="lastName">Last Name</label>
                                            <input type="text" class="form-control" id="lastName" value="Dahn">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="suffix">Suffix</label>
                                            <input type="text" class="form-control" id="suffix">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email Address</label>
                                        <input type="email" class="form-control" id="email" value="john.dahn@gmail.com" readonly>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="sex">Sex</label>
                                            <select class="form-control" id="sex">
                                                <option>Male</option>
                                                <option>Female</option>
                                                <option>Other</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="birthdate">Birthdate</label>
                                            <input type="date" class="form-control" id="birthdate" value="2000-09-14">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success">Update Information</button>
                                </form>
                            </div>
                        </div>
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