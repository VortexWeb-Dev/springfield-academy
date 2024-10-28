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
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $selected_lessons = isset($_POST['selected_lessons']) ? explode(',', $_POST['selected_lessons']) : [];

    // Prepare an insert statement for the course
    $query = "INSERT INTO courses (title, description) VALUES (?, ?)";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }

    // Bind parameters
    $stmt->bind_param('ss', $title, $description);

    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
        // Get the last inserted course ID
        $course_id = $stmt->insert_id;

        // Insert selected lessons into course_lessons table
        if (!empty($selected_lessons)) {
            $course_lesson_query = "INSERT INTO course_lessons (course_id, lesson_id) VALUES (?, ?)";
            $course_lesson_stmt = $conn->prepare($course_lesson_query);

            if ($course_lesson_stmt === false) {
                die("Prepare failed for course_lessons: " . htmlspecialchars($conn->error));
            }

            foreach ($selected_lessons as $lesson_id) {
                $course_lesson_stmt->bind_param('ii', $course_id, $lesson_id);
                if (!$course_lesson_stmt->execute()) {
                    echo "Error: Could not execute query for lesson ID $lesson_id: " . htmlspecialchars($course_lesson_stmt->error);
                }
            }
            $course_lesson_stmt->close();
        }

        // Redirect to a success page or display success message
        header("Location: manage_courses.php");
        exit();
    } else {
        echo "Error: Could not execute query: $query. " . htmlspecialchars($stmt->error);
    }

    $stmt->close();
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
    <title>Academy | Add Course</title>
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
                    <h1 class="header-title">Add Course</h1>
                    <hr>
                    <div class="card">
                        <div class="row align-items-center">
                            <form action="./add_course.php" method="post" class="col-12">
                                <div class="form-group">
                                    <label for="title">Course Name</label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="lessons" class="card-title">Add Lessons</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="text-muted font-weight-normal text-xs">Selected Lessons for Course</p>
                                            <div class="drop-area" id="lessonsDropzone">
                                                <p class="text-muted">Drag lessons here</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="mb-3">Available Lessons</h5>
                                            <div class="modules-list" id="lessonsList">
                                                <?php if ($result->num_rows > 0): ?>
                                                    <?php while ($lesson = $result->fetch_assoc()): ?>
                                                        <div class="module-item" data-lesson-id="<?= htmlspecialchars($lesson['id']) ?>" draggable="true">
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

                                <input type="hidden" id="selectedLessons" name="selected_lessons">

                                <div class="d-flex justify-content-end">
                                    <a href="manage_courses.php" class="btn btn-outline-danger mr-2">
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function setupDragAndDrop(itemsSelector, dropAreaSelector, hiddenInputSelector) {
                const items = document.querySelectorAll(itemsSelector);
                const dropArea = document.querySelector(dropAreaSelector);
                const hiddenInput = document.querySelector(hiddenInputSelector);
                let draggedItem = null;

                items.forEach(item => {
                    item.addEventListener('dragstart', function() {
                        draggedItem = this;
                        setTimeout(() => this.classList.add('dragging'), 0);
                    });

                    item.addEventListener('dragend', function() {
                        setTimeout(() => {
                            this.classList.remove('dragging');
                            draggedItem = null;
                        }, 0);
                    });
                });

                dropArea.addEventListener('dragover', function(e) {
                    e.preventDefault();
                });

                dropArea.addEventListener('drop', function() {
                    if (draggedItem) {
                        dropArea.appendChild(draggedItem);
                        updateSelectedItems(dropArea, hiddenInput);
                    }
                });
            }

            function updateSelectedItems(dropArea, hiddenInput) {
                const itemIds = Array.from(dropArea.querySelectorAll('.module-item'))
                    .map(item => item.getAttribute('data-lesson-id'));
                hiddenInput.value = itemIds.join(',');
            }

            setupDragAndDrop('.module-item', '#lessonsDropzone', '#selectedLessons');
        });
    </script>
</body>

</html>