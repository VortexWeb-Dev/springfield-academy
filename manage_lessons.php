<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Academy | Manage Lessons</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles/app.css">
    <link rel="stylesheet" href="styles//manage-course.css">
    <style>
        .table thead th {
            /* background-color: #e2e8f0; */
            border: none !important;
        }

        .table tbody tr:hover {
            background-color: #f8fafc;
            border-bottom: 1px solid #dee2e6;
            cursor: pointer;
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

                <!-- Lessons Data Content -->
                <div class="container mt-4">
                    <h1 class="header-title">
                        Lessons
                    </h1>
                    <hr>
                    <div class="text-right py-2">
                        <a class="add-course-btn" href='add_lesson.php'>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="16"></line>
                                <line x1="8" y1="12" x2="16" y2="12"></line>
                            </svg>
                            Add New Lession
                        </a>
                    </div>
                    <div class="table-card">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th style="width: 50rem;">Lesson Name</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    require_once './config/database.php';
                                    $conn = getDatabaseConnection();

                                    // Fetch lessons from the database
                                    $query = "SELECT * FROM lessons";
                                    $result = $conn->query($query);

                                    if ($result->num_rows > 0) {
                                        while ($lesson = $result->fetch_assoc()) {
                                            echo '
                                        <tr>
                                            <td>' . htmlspecialchars($lesson['id']) . '</td>
                                            <td>' . htmlspecialchars($lesson['title']) . '</td>
                                            <td class="d-flex justify-content-center align-items-center">
                                                <a class="btn btn-outline mr-2" href="edit_lesson.php?id=' . htmlspecialchars($lesson['id']) . '">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                    </svg>
                                                    Edit
                                                </a>
                                                <a class="btn btn-danger" href="delete_lesson.php?id=' . htmlspecialchars($lesson['id']) . '&pdf_path=' . htmlspecialchars($lesson['pdf_path']) . '" onclick="return confirm(\'Are you sure you want to delete this lesson?\');">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                                    </svg>
                                                    Delete
                                                </a>
                                            </td>
                                        </tr>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="4" class="text-center">No lessons found.</td></tr>';
                                    }


                                    $conn->close();
                                    ?>
                                </tbody>
                            </table>
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