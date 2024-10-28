<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database configuration
require_once './config/database.php';
$conn = getDatabaseConnection();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize input values
    $certificate_name = isset($_POST['certificate_name']) ? trim($_POST['certificate_name']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';

    // Check if a file is uploaded
    if (isset($_FILES['certificate_file']) && $_FILES['certificate_file']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['certificate_file']['tmp_name'];
        $fileName = $_FILES['certificate_file']['name'];
        $fileSize = $_FILES['certificate_file']['size'];
        $fileType = $_FILES['certificate_file']['type'];

        // Validate file type (Optional)
        if ($fileType != 'application/pdf') {
            die("Error: Only PDF files are allowed.");
        }

        // Read the file's binary data
        $fileContent = file_get_contents($fileTmpPath);

        if ($fileContent === false) {
            die("Error reading file content.");
        }

        // Prepare an insert statement
        $query = "INSERT INTO certificates (certificate_name, description, certificate_file, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())";
        $stmt = $conn->prepare($query);

        if ($stmt === false) {
            die("Prepare failed: " . htmlspecialchars($conn->error));
        }

        // Bind parameters
        $null = NULL;  // Used to bind binary data
        $stmt->bind_param('ssb', $certificate_name, $description, $null);
        $stmt->send_long_data(2, $fileContent);  // Send binary data

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Redirect to a success page or display success message
            header("Location: manage_certificates.php"); // Change to your desired success page
            exit();
        } else {
            echo "Error executing query: " . htmlspecialchars($stmt->error);
        }

        // Close statement
        $stmt->close();
    } else {
        echo "Error: File upload failed with error code: " . $_FILES['certificate_file']['error'];
    }
}

// Close database connection
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Academy | Add Certificate</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
        #sidebar {
            height: 100vh;
            position: fixed;
        }

        .nav-link.active {
            background-color: #007bff;
            color: white;
        }

        .main-content {
            margin-left: 250px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Include Sidebar -->
            <?php include './inc/sidebar.php'; ?>

            <!-- Main Content -->
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4 main-content">
                <!-- Include Navbar -->
                <?php include './inc/navbar.php'; ?>

                <!-- Form Content -->
                <div class="container mt-4">
                    <h1>Add Certificate</h1>
                    <hr>

                    <div class="row align-items-center">
                        <form action="./add_certificate.php" method="post" class="col-12" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="certificate_name">Certificate Name</label>
                                <input type="text" class="form-control" id="certificate_name" name="certificate_name" required>
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="certificate_file">Certificate File</label>
                                <input type="file" class="form-control" id="certificate_file" name="certificate_file">
                            </div>
                            <div class="d-flex">
                                <a href="manage_certificates.php" class="btn btn-secondary mr-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">Add</button>
                            </div>
                        </form>
                    </div>

                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>