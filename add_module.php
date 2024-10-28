<?php
session_start(); // Start session for feedback messages
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database configuration
require_once './config/database.php';
$conn = getDatabaseConnection();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $selected_lessons = isset($_POST['selected_lessons']) ? explode(',', $_POST['selected_lessons']) : [];

    // Prepare an insert statement for the module
    $query = "INSERT INTO modules (title) VALUES (?)";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die("Prepare failed: " . htmlspecialchars($conn->error));
    }

    // Bind parameters
    $stmt->bind_param('s', $title);

    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
        // Get the last inserted module ID
        $module_id = $stmt->insert_id;

        // Insert selected lessons into modules_lessons table
        if (!empty($selected_lessons)) {
            $modules_lessons_query = "INSERT INTO modules_lessons (module_id, lesson_id) VALUES (?, ?)";
            $modules_lessons_stmt = $conn->prepare($modules_lessons_query);

            if ($modules_lessons_stmt === false) {
                die("Prepare failed for modules_lessons: " . htmlspecialchars($conn->error));
            }

            foreach ($selected_lessons as $lesson_id) {
                $modules_lessons_stmt->bind_param('ii', $module_id, $lesson_id);
                if (!$modules_lessons_stmt->execute()) {
                    echo "Error: Could not execute query for lesson ID $lesson_id: " . htmlspecialchars($modules_lessons_stmt->error);
                }
            }
            $modules_lessons_stmt->close();
        }

        // Set a session message
        $_SESSION['message'] = "Module added successfully!";
        // Redirect to a success page or display success message
        header("Location: manage_modules.php");
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
    <title>Academy | Add Module</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <!-- fontawesome css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> <!-- customm css -->
    <link rel="stylesheet" href="styles/app.css">
    <style>
        .lessons-list,
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
            <?php include './inc/sidebar.php'; ?>
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 p-0">
                <?php include './inc/navbar.php'; ?>
                <div class="container mt-4">
                    <h1 class="header-title">Add Module</h1>
                    <hr>
                    <!-- main content -->
                    <div class="card">
                        <div class="row align-items-center">
                            <form action="./add_module.php" method="post" class="col-12">
                                <div class="form-group">
                                    <label for="title">Module Name</label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                </div>

                                <div class="form-group">
                                    <label for="lessons" class="card-title">Add Lessons</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="text-muted font-weight-normal text-xs">Selected Lessons for Module</p>
                                            <div class="drop-area" id="lessonsDropArea">
                                                <p class="text-muted">Drag lessons here</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="mb-3">Available Lessons</h5>
                                            <div class="lessons-list" id="lessonsList">
                                                <?php if ($result->num_rows > 0): ?>
                                                    <?php while ($lesson = $result->fetch_assoc()): ?>
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

                                <input type="hidden" id="selectedLessons" name="selected_lessons">

                                <!-- buttons -->
                                <div class="d-flex justify-content-end">
                                    <a href="manage_modules.php" class="btn btn-outline-danger mr-2">
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

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Reusable drag-and-drop setup function
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
                    if (draggedItem && !Array.from(dropArea.children).some(child => child.dataset.lessonId === draggedItem.dataset.lessonId)) {
                        dropArea.appendChild(draggedItem);
                        updateSelectedItems(dropArea, hiddenInput);
                    } else {
                        alert("This lesson is already selected!");
                    }
                });
            }

            function updateSelectedItems(dropArea, hiddenInput) {
                const itemIds = Array.from(dropArea.querySelectorAll('.lesson-item'))
                    .map(item => item.getAttribute('data-lesson-id'));
                hiddenInput.value = itemIds.join(',');
            }

            // Initialize drag-and-drop for lessons
            setupDragAndDrop('.lesson-item', '#lessonsDropArea', '#selectedLessons');
        });
    </script>
</body>

</html>