<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Academy | Course Details</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        .video-container {
            position: relative;
            width: 100%;
            height: 0;
            padding-bottom: 56.25%;
        }

        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .lesson-card {
            margin-bottom: 20px;
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

        // Fetch the course ID from the URL
        $module_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;
        $course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 1;

        // Fetch course details from the database
        $module_query = "SELECT * FROM modules WHERE id = ?";
        $stmt = $conn->prepare($module_query);

        if ($stmt === false) {
            die("Prepare failed: " . htmlspecialchars($conn->error));
        }

        $stmt->bind_param('i', $module_id);
        $stmt->execute();
        $module_result = $stmt->get_result();

        if ($module_result->num_rows > 0) {
            $module = $module_result->fetch_assoc();
        } else {
            echo '<p>Course not found.</p>';
            $module = null;
        }
        $stmt->close();

        // Fetch lessons for the current course using the pivot table
        $module_query = "
        SELECT l.* 
        FROM lessons l 
        INNER JOIN modules_lessons cl ON l.id = cl.module_id 
        WHERE cl.module_id = ?";
        $module_stmt = $conn->prepare($module_query);

        if ($module_stmt === false) {
            die("Prepare failed: " . htmlspecialchars($conn->error));
        }

        $module_stmt->bind_param('i', $module_id);
        $module_stmt->execute();
        $lessons_result = $module_stmt->get_result();
        $lessons = [];

        if ($lessons_result->num_rows > 0) {
            while ($lesson = $lessons_result->fetch_assoc()) {
                $lessons[] = $lesson;
            }
        }

        $module_stmt->close();
        ?>

        <a href="course_details.php?id=<?php echo htmlspecialchars($course_id); ?>" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i> Back to Course</a>

        <?php if ($module) : ?>
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title"><?php echo htmlspecialchars($module['title']); ?></h1>
                    <hr>
                    <h2>Lessons</h2>

                    <?php if (!empty($lessons)) : ?>
                        <div class="row">
                            <?php foreach ($lessons as $lesson) : ?>
                                <div class="col-md-6 lesson-card">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                <a href="view_lesson.php?id=<?php echo htmlspecialchars($lesson['id']); ?>&module_id=<?php echo $module_id; ?>&course_id=<?php echo $course_id; ?>">
                                                    <?php echo htmlspecialchars($lesson['title']); ?>
                                                </a>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                    <?php else : ?>
                        <p>No lessons available for this module.</p>
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