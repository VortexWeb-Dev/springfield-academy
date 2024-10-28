<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once './config/database.php';

$conn = getDatabaseConnection();

// Fetch the course ID from the URL
$course_id = isset($_GET['id']) ? (int)$_GET['id'] : null;

if (!$course_id) {
    die("Error: Course ID is not specified.");
}

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
    die("Error: Course not found.");
}
$stmt->close();

// Fetch lesson details from the database
$lesson_query = "SELECT * FROM lessons INNER JOIN course_lessons ON lessons.id = course_lessons.lesson_id WHERE course_id = ?";
$stmt_lesson = $conn->prepare($lesson_query);
if ($stmt_lesson === false) {
    die("Prepare failed: " . htmlspecialchars($conn->error));
}

$stmt_lesson->bind_param('i', $course_id);
$stmt_lesson->execute();
$lesson_result = $stmt_lesson->get_result();
$lessons = $lesson_result->fetch_all(MYSQLI_ASSOC);
$stmt_lesson->close();

// If no lessons are found, handle it gracefully
if (empty($lessons)) {
    die("Error: No lessons found for this course.");
}

// Function to get the first lesson
function getFirstLesson($lessons)
{
    return !empty($lessons) ? $lessons[0] : null;
}

// Function to get the current lesson based on lesson_id
function getCurrentLesson($conn, $lessons)
{
    $current_lesson_id = isset($_GET['lesson_id']) ? (int)$_GET['lesson_id'] : null;

    if ($current_lesson_id) {
        $current_lesson_query = "SELECT * FROM lessons WHERE id = ?";
        $current_lesson_stmt = $conn->prepare($current_lesson_query);

        if ($current_lesson_stmt === false) {
            die("Prepare failed: " . htmlspecialchars($conn->error));
        }

        $current_lesson_stmt->bind_param('i', $current_lesson_id);
        $current_lesson_stmt->execute();
        $current_lesson_result = $current_lesson_stmt->get_result();

        if ($current_lesson_result->num_rows > 0) {
            return $current_lesson_result->fetch_assoc();
        }
    }

    // Return the first lesson if no current lesson is found
    return getFirstLesson($lessons);
}

// Get the current lesson
$current_lesson = getCurrentLesson($conn, $lessons);

// Close the database connection
// $conn->close();

// At this point, you have the course, lessons, and the current lesson ready to use
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academy | Course Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/app.css">
    <style>
        .pdf-container {
            height: 80vh;
            /* Adjust height as needed */
            overflow: hidden;
        }

        .pdf-embed {
            width: 100%;
            height: 100%;
            border: none;
        }

        body {
            font-family: Arial, sans-serif;
            padding-top: 56px;
            /* Height of the topbar */
        }

        .topbar {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .sidebar {
            background-color: #f8f9fa;
            height: calc(100vh - 56px);
            overflow-y: auto;
            top: 56px;
        }

        .main-content {
            height: calc(100vh - 56px);
            overflow-y: auto;
        }

        .lesson-item {
            cursor: pointer;
            padding: 10px 15px;
            margin-bottom: 5px;
        }

        .lesson-item.active {
            background-color: #fff;
            border-left: 4px solid #ff6b35;
        }

        .lesson-item {
            padding: 5px 15px 5px 30px;
            font-size: 0.9rem;
            border-bottom: 1px solid #dee2e6;
        }

        .lesson-item.active {
            color: #ff6b35;
        }

        .video-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            background-color: #000;
        }

        .pdf-container {
            display: flex;
            justify-content: center;
            align-items: center;
            /* background-color: #000; */
            width: 100%;
            height: 80vh;
            /* padding: 0.5rem; */
            box-shadow: inset 0 0 0 1px rgba(0, 0, 0, 0.1);
            border: solid 1px grey;
            border-radius: 0.5rem;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .pdf-embed {
            width: 100%;
            height: 100%;
            border: none;
        }

        canvas {
            width: 100%;
            height: auto;
        }

        @media (max-width: 768px) {
            .pdf-container {
                height: 70vh;
                /* Slightly reduce height for mobile for better fit */
            }

            .pdf-embed {
                height: 100%;
                /* Ensure PDF scales correctly */
            }
        }


        .video-container .video-controls {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(0, 0, 0, 0.5);
            padding: 10px;
            color: white;
        }

        .tag {
            background-color: #ff6b35;
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            margin-right: 5px;
            margin-bottom: 5px;
            display: inline-block;
        }

        .comment-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .progress-circle {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            margin-right: 10px;
        }

        .progress-circle.active {
            background-color: #ff6b35;
            color: white;
        }

        /* Custom Scrollbar Styles */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Responsive Styles */
        @media (max-width: 991px) {
            .sidebar {
                position: fixed;
                left: -100%;
                top: 56px;
                bottom: 0;
                z-index: 1000;
                transition: 0.3s;
                width: 80%;
                max-width: 300px;
            }

            .sidebar.show {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }
        }

        .custom-nav-link {
            text-decoration: none;
            color: gray;
        }

        @media screen and (max-width: 768px) {
            .custom-nav-link {
                font-size: 1rem;
            }

        }

        .custom-nav-link.active {
            color: black;
        }

        .custom-nav-link:hover {
            color: black;
        }

        .accordion-item {
            margin-bottom: 10px;
            border-radius: 8px;
            overflow: hidden;
        }

        .accordion-item:last-child {
            margin-bottom: 0;
        }

        .comments-section {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <!-- Sticky Topbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top topbar">
        <div class="container-fluid">
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#leftSidebar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand custom-nav-link text-black" href="./index.php">Springfield Academy Course</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="./index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./courses.php">My Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./reports.php">Reports</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Left Sidebar -->
            <nav id="leftSidebar" class="col-lg-3 offcanvas-lg offcanvas-start">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title">Course Content</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body py-4">
                    <ul class="nav flex-column w-100">
                        <?php foreach ($lessons as $lesson) : ?>
                            <li class="nav-item">
                                <a class="nav-link <?= $current_lesson['id'] == $lesson['id'] ? 'active' : ''; ?>" href="./courseDetails.php?id=<?= $course_id; ?>&lesson_id=<?= $lesson['id']; ?>">
                                    <?php echo htmlspecialchars($lesson['title']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </nav>


            <!-- Main Content Area -->
            <main class="col-lg-6 main-content">
                <div class="pt-3 pb-2 mb-3 d-flex justify-content-start align-items-center gap-2 text-secondary">
                    <span>Current Lesson:</span>
                    <span class="fw-bold"><?= htmlspecialchars($current_lesson['title']); ?></span>
                </div>

                <!-- PDF viewer -->
                <div class="pdf-container mb-4">
                    <iframe src="<?= htmlspecialchars($current_lesson['pdf_path']); ?>" type="application/pdf" class="pdf-embed"></iframe>
                </div>

                <!-- Tabs for Comments and Course Details (visible only on mobile) -->
                <ul class="nav nav-tabs d-lg-none" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab" aria-controls="details" aria-selected="true">Course Details</button>
                    </li>
                </ul>
            </main>

            <!-- Right Sidebar -->
            <aside class="col-lg-3">
                <h5>Course Details</h5>
                <p><?= htmlspecialchars($course['description']); ?></p>
                <!-- You can add more course details here -->
            </aside>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>