<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database configuration
require_once './config/database.php';
$conn = getDatabaseConnection();

// Check if the course ID is provided
if (!isset($_GET['id'])) {
    die("Error: Course ID is not specified.");
}

$course_id = intval($_GET['id']);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize input values
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $course_code = isset($_POST['course_code']) ? trim($_POST['course_code']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $selected_lessons = isset($_POST['selected_lessons']) ? explode(',', $_POST['selected_lessons']) : [];

    // Prepare an update statement for the course
    $update_query = "UPDATE courses SET title = ?, description = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param('ssi', $title, $description, $course_id);

    if ($stmt->execute()) {
        // Clear existing lessons for the course
        $delete_lessons_query = "DELETE FROM course_lessons WHERE course_id = ?";
        $stmt = $conn->prepare($delete_lessons_query);
        $stmt->bind_param('i', $course_id);
        $stmt->execute();

        // Insert updated lessons
        foreach ($selected_lessons as $lesson_id) {
            $lesson_id_int = intval($lesson_id);

            $insert_lesson_query = "INSERT INTO course_lessons (course_id, lesson_id) VALUES (?, ?)";
            $stmt = $conn->prepare($insert_lesson_query);
            $stmt->bind_param('ii', $course_id, $lesson_id_int);
            $stmt->execute();
        }

        // Redirect to a confirmation page or show success message
        header("Location: manage_courses.php");
        exit();
    } else {
        echo "Error updating course: " . $conn->error;
    }
}

// Fetch the course details
$query = "SELECT * FROM courses WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $course_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Error: Course not found.");
}

$course = $result->fetch_assoc();

// Fetch the lessons associated with this course
$selected_lessons_query = "SELECT lesson_id FROM course_lessons WHERE course_id = ?";
$selected_lessons_stmt = $conn->prepare($selected_lessons_query);
$selected_lessons_stmt->bind_param('i', $course_id);
$selected_lessons_stmt->execute();
$selected_lessons_result = $selected_lessons_stmt->get_result();

$selected_lessons = [];
while ($row = $selected_lessons_result->fetch_assoc()) {
    $selected_lessons[] = $row['lesson_id'];
}

// Fetch all available lessons
$query = "
    SELECT * FROM lessons
    WHERE id NOT IN (
        SELECT lesson_id FROM course_lessons WHERE course_id = ?
    )
";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $course_id);
$stmt->execute();
$lessons_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Academy | Edit Course</title>
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
                    <h1 class="header-title">Edit Course</h1>
                    <hr>
                    <div class="card">
                        <div class="row align-items-center">
                            <form action="./edit_course.php?id=<?= htmlspecialchars($course_id) ?>" method="post" class="col-12">
                                <div class="form-group">
                                    <label for="title">Course Name</label>
                                    <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($course['title']) ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($course['description']) ?></textarea>
                                </div>

                                <!-- Lessons Drag and Drop Area -->
                                <div class="form-group">
                                    <label for="lessons">Add Lessons</label>
                                    <div class="row">
                                        <!-- Drop area for selected lessons -->
                                        <div class="col-md-6">
                                            <p class="text-muted font-weight-normal text-xs">Selected Lessons for Course</p>
                                            <div class="drop-area" id="dropArea">
                                                <p class="text-muted">Drag lessons here</p>
                                                <?php if (!empty($selected_lessons)): ?>
                                                    <?php foreach ($selected_lessons as $lesson_id): ?>
                                                        <div class="lesson-item d-flex justify-content-between align-items-center" data-lesson-id="<?= htmlspecialchars($lesson_id) ?>" draggable="true">
                                                            <span><?= htmlspecialchars(getModuleNameById($lesson_id, $conn)) ?></span>
                                                            <button type="button" class="btn btn-danger btn-sm ml-2 remove-lesson" onclick="removeLesson(this)">Delete</button>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <h5 class="mb-3">Available Lessons</h5>
                                            <div class="lesson-list" id="lessonsList">
                                                <?php if ($lessons_result->num_rows > 0): ?>
                                                    <?php while ($lesson = $lessons_result->fetch_assoc()): ?>
                                                        <div class="lesson-item" data-lesson-id="<?= htmlspecialchars($lesson['id']) ?>" draggable="true">
                                                            <?= htmlspecialchars($lesson['title']) ?>
                                                        </div>
                                                    <?php endwhile; ?>
                                                <?php else: ?>
                                                    <p class="text-muted">No lessons available.</p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hidden field to store selected lessons' IDs -->
                                <input type="hidden" id="selectedLessons" name="selected_lessons">

                                <!-- buttons -->
                                <div class="d-flex justify-content-end">
                                    <a href="manage_courses.php" class="btn btn-outline-danger mr-2">
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

    <!-- Drag and Drop Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lessonsList = document.getElementById('lessonsList');
            const dropArea = document.getElementById('dropArea');
            const selectedLessons = document.getElementById('selectedLessons');
            let draggedItem = null;

            // Add drag start and end event listeners to lesson items
            document.querySelectorAll('.lesson-item').forEach(item => {
                item.addEventListener('dragstart', function() {
                    draggedItem = this;
                    setTimeout(() => {
                        this.classList.add('dragging');
                    }, 0);
                });

                item.addEventListener('dragend', function() {
                    setTimeout(() => {
                        draggedItem.classList.remove('dragging');
                        draggedItem = null;
                    }, 0);
                });
            });

            // Prevent default behavior for drag over event
            dropArea.addEventListener('dragover', function(e) {
                e.preventDefault();
            });

            // Handle drop event
            dropArea.addEventListener('drop', function() {
                if (draggedItem) {
                    dropArea.appendChild(draggedItem);
                    updateSelectedLessons();
                }
            });

            // Update hidden input field with selected lesson IDs
            function updateSelectedLessons() {
                const lessonIds = Array.from(dropArea.querySelectorAll('.lesson-item'))
                    .map(item => item.getAttribute('data-lesson-id'));
                selectedLessons.value = lessonIds.join(',');
            }

            // Function to remove a lesson item when the delete button is clicked
            window.removeLesson = function(button) {
                const lessonItem = button.closest('.lesson-item');
                const lesson_id = lessonItem.getAttribute('data-lesson-id');

                // Remove the lesson item from the drop area
                lessonItem.parentNode.removeChild(lessonItem);

                updateSelectedLessons();
            };
        });
    </script>
</body>

</html>

<?php
// Function to get lesson name by ID
function getModuleNameById($lesson_id, $conn)
{
    $query = "SELECT title FROM lessons WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $lesson_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc()['title'] ?? 'Unknown Lesson';
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize input values
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $course_code = isset($_POST['course_code']) ? trim($_POST['course_code']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $selected_lessons = isset($_POST['selected_lessons']) ? explode(',', $_POST['selected_lessons']) : [];

    // Prepare an update statement for the course
    $update_query = "UPDATE courses SET title = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param('si', $title, $course_id);

    if ($stmt->execute()) {
        // Clear existing lessons for the course
        $delete_lessons_query = "DELETE FROM course_lessons WHERE course_id = ?";
        $stmt = $conn->prepare($delete_lessons_query);
        $stmt->bind_param('i', $course_id);
        $stmt->execute();

        // Insert updated lessons
        // Insert updated lessons
        foreach ($selected_lessons as $lesson_id) {
            $lesson_id_int = intval($lesson_id); // Ensure it's an integer

            $insert_lesson_query = "INSERT INTO course_lessons (course_id, lesson_id) VALUES (?, ?)";
            $stmt = $conn->prepare($insert_lesson_query);
            $stmt->bind_param('ii', $course_id, $lesson_id_int); // Use the variable
            $stmt->execute();
        }


        // Redirect to a confirmation page or show success message
        header("Location: course_list.php?success=1");
        exit();
    } else {
        echo "Error updating course: " . $conn->error;
    }
}

$conn->close();
?>