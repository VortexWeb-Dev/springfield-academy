<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once './config/database.php';
$conn = getDatabaseConnection();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize input values
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $youtube_link = isset($_POST['youtube_link']) ? trim($_POST['youtube_link']) : '';

    if (empty($youtube_link) && empty($_FILES['video_file']['name'])) {
        echo "Error: You must provide either a video file or a YouTube link.";
        exit;
    }

    if ((isset($_FILES['video_file']) && $_FILES['video_file']['error'] == UPLOAD_ERR_OK)) {
        $uploadDir = './uploads/';

        $videoFileTmpPath = $_FILES['video_file']['tmp_name'];
        $videoFileName = $_FILES['video_file']['name'];
        $uniqueName = uniqid() . '_' . basename($videoFileName);
        $videoFileDestination = $uploadDir . $uniqueName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($videoFileTmpPath, $videoFileDestination)) {

            $query = "INSERT INTO videos (title, description, youtube_link, video_path) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);

            if ($stmt === false) {
                die("Prepare failed: " . htmlspecialchars($conn->error));
            }

            $video_path = !empty($videoFileDestination) ? $videoFileDestination : null;
            // Bind parameters
            $stmt->bind_param('ssss', $title, $description, $youtube_link, $videoFileDestination);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                header("Location: manage_video_lessons.php");
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
<?php
require_once './config/database.php';
$conn = getDatabaseConnection();

// Fetch lessons from the database
$query = "SELECT * FROM lessons";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Academy | Add Video</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <!-- fontawesome css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> <!-- customm css -->
    <link rel="stylesheet" href="styles/app.css">
    <style>
        .modules-list,
        .quizzes-list,
        .drop-area {
            border: 2px solid #ced4da;
            border-radius: 8px;
            padding: 10px;
            min-height: 200px;
            background-color: #f8f9fa;
        }

        .drop-area {
            background-color: #e9ecef;
        }

        .module-item,
        .quiz-item {
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ced4da;
            border-radius: 8px;
            background-color: #fff;
            transition: all 0.3s ease-in-out;
            cursor: grab;
        }

        .module-item:hover,
        .quiz-item:hover {
            border: 1px solid #0284c7;
            background-color: #dbeafe;
        }

        .dragging {
            opacity: 0.5;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php include './inc/sidebar.php'; ?>
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 p-0">
                <?php include './inc/navbar.php'; ?>
                <div class="container mt-4">
                    <h1 class="header-title">Add Video</h1>
                    <hr>
                    <div class="card">
                        <div class="row align-items-center">
                            <form action="./add_video.php" method="post" enctype="multipart/form-data" class="col-12">
                                <div class="form-group">
                                    <label for="title">Video Title</label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="video_file">Upload Video File</label>
                                    <input type="file" class="form-control-file" id="video_file" name="video_file" accept="video/*">
                                </div>
                                <div class="form-group">
                                    <label for="youtube_link">Youtube Embedded URL</label>
                                    <input type="text" class="form-control" id="youtube_link" name="youtube_link" placeholder="https://www.youtube.com/embed/SK6eny9PPpU">
                                </div>

                                <input type="hidden" id="selectedLessons" name="selected_lessons">

                                <div class="d-flex justify-content-end">
                                    <a href="manage_video_lessons.php" class="btn btn-outline-danger mr-2">
                                        <i class="fas fa-times-circle"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-plus-circle"></i> Add
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>