<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Academy | Lesson Video</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        .video-container {
            position: relative;
            width: 100%;
            height: 0;
            padding-bottom: 56.25%;
        }

        .video-container video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .pdf-container {
            width: 100%;
            height: 500px;
            /* Adjust the height as needed */
            border: 1px solid #ccc;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <?php
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        require_once './config/database.php';
        $conn = getDatabaseConnection();

        // Fetch the lesson ID from the URL
        $lesson_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
        $module_id = isset($_GET['module_id']) ? (int)$_GET['module_id'] : 1;
        $course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 1;

        // Fetch lesson details from the database
        $lesson_query = "SELECT * FROM lessons WHERE id = ?";
        $stmt = $conn->prepare($lesson_query);

        if ($stmt === false) {
            die("Prepare failed: " . htmlspecialchars($conn->error));
        }

        $stmt->bind_param('i', $lesson_id);
        $stmt->execute();
        $lesson_result = $stmt->get_result();

        if ($lesson_result->num_rows > 0) {
            $lesson = $lesson_result->fetch_assoc();
        } else {
            echo '<p>Lesson not found.</p>';
            $lesson = null;
        }
        $stmt->close();

        ?>

        <a href="view_module.php?id=<?php echo htmlspecialchars($module_id); ?>&course_id=<?php echo htmlspecialchars($course_id); ?>" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i> Back to Module</a>

        <?php if ($lesson) : ?>
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title"><?php echo htmlspecialchars($lesson['title']); ?></h1>

                    <?php if (!empty($lesson['pdf_path'])) : ?>
                        <div class="pdf-container">
                            <iframe src="<?php echo htmlspecialchars($lesson['pdf_path']); ?>" width="100%" height="100%">
                                Your browser does not support PDFs. <a href="<?php echo htmlspecialchars($lesson['pdf_path']); ?>">Download the PDF</a>.
                            </iframe>
                        </div>
                    <?php else : ?>
                        <p class="text-muted">No PDF attachment available for this lesson.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>