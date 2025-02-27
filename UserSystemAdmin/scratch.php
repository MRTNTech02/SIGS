<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customization Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .theme-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: inline-block;
            margin: 5px;
            cursor: pointer;
            border: 2px solid transparent;
        }
        .selected {
            border: 2px solid blue;
        }
    </style>
</head>
<body class="p-3">

    <!-- Navigation Buttons -->
    <div class="d-flex justify-content-start mb-3">
        <button class="btn btn-outline-dark me-2">Profile Information</button>
        <button class="btn btn-outline-dark me-2">Update Email or Password</button>
        <button class="btn btn-primary">Customization</button>
    </div>

    <div class="row">
        <!-- Company Logo Section -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    <strong>Company Logo</strong>
                </div>
                <div class="card-body text-center">
                    <img src="https://via.placeholder.com/100" alt="Company Logo" class="img-fluid mb-2">
                    <p>Upload your company logo here</p>
                    <input type="file" class="form-control">
                </div>
            </div>
        </div>

        <!-- Customization Section -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <strong>Company Name</strong>
                </div>
                <div class="card-body d-flex justify-content-between align-items-center">
                    <span>Lorem Ipsum Firm Name</span>
                    <button class="btn btn-sm btn-outline-primary">Update Firm Name</button>
                </div>
            </div>

            <!-- Theme Selection -->
            <div class="card mt-3">
                <div class="card-header bg-primary text-white">
                    <strong>Theme</strong>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap">
                        <div class="theme-circle bg-primary selected"></div>
                        <div class="theme-circle bg-success"></div>
                        <div class="theme-circle bg-warning"></div>
                        <div class="theme-circle bg-danger"></div>
                        <div class="theme-circle bg-secondary"></div>
                        <div class="theme-circle bg-info"></div>
                        <div class="theme-circle bg-pink"></div>
                        <div class="theme-circle bg-dark"></div>
                        <div class="theme-circle bg-light border"></div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button class="btn btn-success">Save</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
