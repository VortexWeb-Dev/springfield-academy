<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once './config/database.php';
$conn = getDatabaseConnection();

$lesson_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// Fetch lesson details from the database
$query = "SELECT * FROM lessons WHERE id = ?";
$stmt = $conn->prepare($query);

if ($stmt === false) {
    die("Prepare failed: " . htmlspecialchars($conn->error));
}

$stmt->bind_param('i', $lesson_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $lesson = $result->fetch_assoc();
} else {
    echo '<p>Lesson not found.</p>';
    $lesson = null;
}

$stmt->close();

// Handle form submission for lesson update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo '<pre>';
    print_r($_POST);
    print_r($_GET);
    echo '</pre>';
    $title = $_POST['title'];
    // $description = $_POST['description'];

    // File upload paths
    $uploadDir = './uploads/';
    // $videoFilePath = $lesson['video_path'];
    $pdfFilePath = $lesson['pdf_path'];

    // Check if new video or PDF files are uploaded
    // if (isset($_FILES['video_file']) && $_FILES['video_file']['error'] == UPLOAD_ERR_OK) {
    //     $videoFileTmpPath = $_FILES['video_file']['tmp_name'];
    //     $videoFileName = $_FILES['video_file']['name'];
    //     $videoFilePath = $uploadDir . basename($videoFileName);

    //     if (!is_dir($uploadDir)) {
    //         mkdir($uploadDir, 0777, true);
    //     }

    //     // Move uploaded video file
    //     move_uploaded_file($videoFileTmpPath, $videoFilePath);
    // }

    if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == UPLOAD_ERR_OK) {
        $pdfFileTmpPath = $_FILES['pdf_file']['tmp_name'];
        $pdfFileName = $_FILES['pdf_file']['name'];
        $pdfFilePath = $uploadDir . basename($pdfFileName);

        // Move uploaded PDF file
        move_uploaded_file($pdfFileTmpPath, $pdfFilePath);
    }

    // Update lesson in the database
    $update_query = "UPDATE lessons SET title = ?, pdf_path = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);

    if ($update_stmt === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }

    $update_stmt->bind_param('ssi', $title, $pdfFilePath, $lesson_id);
    $update_success = $update_stmt->execute();

    if ($update_success) {
        echo '<script>window.location.href = "manage_lessons.php";</script>';
    } else {
        echo '<script>alert("Error updating lesson: ' . htmlspecialchars($conn->error) . '");</script>';
    }

    $update_stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Academy | Edit Lesson</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <!-- fontawesome css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- customm css -->
    <link rel="stylesheet" href="styles/app.css">
    <style>
        .form-section {
            margin-bottom: 2rem;
        }

        .current-file {
            margin-bottom: 1rem;
        }

        .form-control {
            margin-top: 0.5rem;
        }
    </style>
</head>

<body>
    <div class="container-fluid mb-5">
        <div class="row">
            <!-- Include Sidebar -->
            <?php include './inc/sidebar.php'; ?>

            <!-- Main Content -->
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 p-0">
                <!-- Include Navbar -->
                <?php include './inc/navbar.php'; ?>

                <div class="container mt-4">
                    <h1 class="header-title">Edit Lesson</h1>
                    <hr>
                    <div class="card">
                        <form action="./edit_lesson.php?id=<?php echo $lesson_id; ?>" method="post" enctype="multipart/form-data">
                            <div class="form-section">
                                <div class="form-group">
                                    <label for="title">Lesson Name</label>
                                    <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($lesson['title']); ?>" required>
                                </div>
                            </div>

                            <div class="form-section">
                                <div class="row">
                                    <!-- current file -->
                                    <div class="form-group col-md-6">
                                        <label>Current PDF:</label>
                                        <div class="current-file">
                                            <?php if ($lesson['pdf_path']): ?>
                                                <iframe src="<?php echo htmlspecialchars($lesson['pdf_path']); ?>" width="100%" height="400"></iframe>
                                            <?php else: ?>
                                                <p>No PDF uploaded.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <!-- preview new file -->
                                    <div class="form-group col-md-6 d-none" id="pdf_preview_container">
                                        <label for="pdf_preview">New PDF Preview:</label>
                                        <div id="pdf_preview" style="height: 400px;"></div>
                                    </div>
                                </div>

                                <!-- <div class="form-group">
                                    <label for="pdf_file">Upload New PDF (Optional)</label>
                                    <input type="file" accept="application/pdf" class="form-control" id="pdf_file" name="pdf_file" onchange="previewFile(this)>
                                </div> -->

                                <!-- upload new pdf -->
                                <div class="form-group">
                                    <label for="pdf_file">Upload New PDF (Optional)</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" accept="application/pdf" class="custom-file-input form-control" id="pdf_file" name="pdf_file" onchange="previewFile(this)">
                                            <label class="custom-file-label" for="pdf_file">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- buttons -->
                            <div class="d-flex justify-content-end">
                                <a href="manage_lessons.php" class="btn btn-outline-danger mr-2">
                                    <i class="fas fa-times-circle"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-plus-circle"></i> Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </main>
        </div>
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
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>