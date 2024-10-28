<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database configuration
require_once './config/database.php';
$conn = getDatabaseConnection();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize input values
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';

    if ((isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == UPLOAD_ERR_OK)) {
        $uploadDir = './uploads/';

        $pdfFileTmpPath = $_FILES['pdf_file']['tmp_name'];
        $pdfFileName = $_FILES['pdf_file']['name'];
        $pdfFileDestination = $uploadDir . basename($pdfFileName);

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($pdfFileTmpPath, $pdfFileDestination)) {

            $query = "INSERT INTO lessons (title, pdf_path) VALUES (?, ?)";
            $stmt = $conn->prepare($query);

            if ($stmt === false) {
                die("Prepare failed: " . htmlspecialchars($conn->error));
            }

            $stmt->bind_param('ss', $title, $pdfFileDestination);

            if ($stmt->execute()) {
                header("Location: manage_lessons.php");
                exit();
            } else {
                echo "Error: Could not execute query: $query. " . htmlspecialchars($stmt->error);
            }

            $stmt->close();
        } else {
            echo "Error: Could not move the uploaded file.";
        }
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Academy | Add Lesson</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <!-- fontawesome css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- customm css -->
    <link rel="stylesheet" href="styles/app.css">
    <style>

    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Include Sidebar -->
            <?php include './inc/sidebar.php'; ?>

            <!-- Main Content -->
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 p-0">
                <!-- Include Navbar -->
                <?php include './inc/navbar.php'; ?>

                <!-- Form Content -->
                <div class="container mt-4">
                    <h1 class="header-title">Add Lesson</h1>
                    <hr>
                    <div class="card">
                        <div class="row align-items-center">
                            <form action="./add_lesson.php" method="post" class="col-12" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="title">Lesson Name</label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                </div>

                                <div class="form-group">
                                    <label for="pdf_file">PDF File</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" accept="application/pdf" class="custom-file-input" id="pdf_file" name="pdf_file" onchange="previewFile(this)">
                                            <label class="custom-file-label" for="pdf_file">Choose file</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group d-none" id="pdf_preview_container">
                                    <label for="pdf_preview">PDF Preview</label>
                                    <div id="pdf_preview" style="height: 400px;"></div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <a href="manage_lessons.php" class="btn btn-outline-danger mr-2">
                                        <i class="fas fa-times-circle"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-plus-circle"></i> Add
                                    </button>
                                </div>

                                <script>
                                    function previewFile(input) {
                                        if (input.files && input.files[0]) {
                                            var file = input.files[0];
                                            var reader = new FileReader();
                                            reader.onload = function(e) {
                                                var pdf_preview_container = document.getElementById('pdf_preview_container');
                                                pdf_preview_container.classList.remove('d-none');
                                                var preview = document.getElementById('pdf_preview');
                                                preview.innerHTML = '<embed src="' + e.target.result + '" width="100%" height="100%">';
                                            };
                                            reader.readAsDataURL(file);
                                        }
                                    }
                                </script>

                            </form>
                        </div>
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