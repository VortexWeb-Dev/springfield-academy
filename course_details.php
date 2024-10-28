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
        $course_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

        // Fetch course details from the database
        $course_query = "SELECT * FROM courses WHERE id = ?";
        $stmt = $conn->prepare($course_query);

        if ($stmt === false) {
            die("Prepare failed: " . htmlspecialchars($conn->error));
        }

        $stmt->bind_param('i', $course_id);
        $stmt->execute();
        $course_result = $stmt->get_result();

        if ($course_result->num_rows > 0) {
            $course = $course_result->fetch_assoc();
        } else {
            echo '<p>Course not found.</p>';
            $course = null;
        }
        $stmt->close();

        // Fetch lessons for the current course using the pivot table
        $module_query = "
        SELECT l.* 
        FROM modules l 
        INNER JOIN course_modules cl ON l.id = cl.module_id 
        WHERE cl.course_id = ?";
        $module_stmt = $conn->prepare($module_query);

        if ($module_stmt === false) {
            die("Prepare failed: " . htmlspecialchars($conn->error));
        }

        $module_stmt->bind_param('i', $course_id);
        $module_stmt->execute();
        $modules_result = $module_stmt->get_result();
        $modules = [];

        if ($modules_result->num_rows > 0) {
            while ($module = $modules_result->fetch_assoc()) {
                $modules[] = $module;
            }
        }

        $module_stmt->close();
        ?>

        <a href="courses.php" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i> Back to Courses</a>

        <?php if ($course) : ?>
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h1>
                    <p class="card-text"><?php echo htmlspecialchars($course['description']); ?></p>
                    <hr>
                    <h2>Modules</h2>

                    <?php if (!empty($modules)) : ?>
                        <div class="row">
                            <?php foreach ($modules as $module) : ?>
                                <div class="col-md-6 lesson-card">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                <a href="view_module.php?id=<?php echo htmlspecialchars($module['id']); ?>&course_id=<?php echo htmlspecialchars($course['id']); ?>">
                                                    <?php echo htmlspecialchars($module['title']); ?>
                                                </a>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        </div>
                    <?php else : ?>
                        <p>No lessons available for this course.</p>
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