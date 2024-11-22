<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database configuration
require_once './config/database.php';
$conn = getDatabaseConnection();

// Check if the video ID is provided
if (!isset($_GET['id'])) {
    die("Error: Video ID is not specified.");
}

$video_id = intval($_GET['id']);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $youtube_link = isset($_POST['youtube_link']) ? trim($_POST['youtube_link']) : '';

    // Prepare an update statement for the video
    $update_query = "UPDATE videos SET title = ?, description = ?, youtube_link = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param('sssi', $title, $description, $youtube_link, $video_id);

    if ($stmt->execute()) {
        header("Location: manage_video_lessons.php");
        exit();
    } else {
        echo "Error updating video: " . $conn->error;
    }
}

// Fetch the video details
$query = "SELECT * FROM videos WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $video_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Error: Video not found.");
}

$video = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Academy | Edit Video</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <!-- fontawesome css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- customm css -->
    <link rel="stylesheet" href="styles/app.css">
    <style>
        .lesson-list,
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

        .lesson-item,
        .quiz-item {
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ced4da;
            border-radius: 8px;
            background-color: #fff;
            transition: all 0.3s ease-in-out;
            cursor: grab;
        }

        .lesson-item:hover,
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
            <!-- Include Sidebar -->
            <?php include './inc/sidebar.php'; ?>

            <!-- Main Content -->
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 p-0">
                <!-- Include Navbar -->
                <?php include './inc/navbar.php'; ?>

                <!-- Form Content -->
                <div class="container my-4">
                    <h1 class="header-title">Edit Video</h1>
                    <hr>
                    <div class="card">
                        <div class="row align-items-center">
                            <form action="./edit_video.php?id=<?= htmlspecialchars($video_id) ?>" method="post" class="col-12">
                                <div class="form-group">
                                    <label for="title">Video Name</label>
                                    <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($video['title']) ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($video['description']) ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="youtube_link">Youtube Embedded URL</label>
                                    <input type="text" class="form-control" id="youtube_link" name="youtube_link" value="<?= htmlspecialchars($video['youtube_link']) ?>" required>
                                </div>

                                <!-- buttons -->
                                <div class="d-flex justify-content-end">
                                    <a href="manage_videos.php" class="btn btn-outline-danger mr-2">
                                        <i class="fas fa-times-circle"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-plus-circle"></i> Edit
                                    </button>
                                </div>
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