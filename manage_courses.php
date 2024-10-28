<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Academy | Manage Courses</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles/app.css">
    <link rel="stylesheet" href="styles//manage-course.css">
    <style>
        /* .table thead th {
            background-color: #007bff;
            color: white;
        }

        .btn-edit {
            background-color: #ffc107;
            color: white;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
        }

        .btn-edit:hover,
        .btn-delete:hover {
            opacity: 0.8;
        } */
    </style>
</head>

<body>
    <div class="container-fluid main-container">
        <div class="row">
            <!-- Include Sidebar -->
            <?php include './inc/sidebar.php'; ?>

            <!-- Main Content -->
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 p-0">
                <!-- Include Navbar -->
                <?php include './inc/navbar.php'; ?>

                <!-- Courses Data Content -->
                <div class="container mt-4">
                    <h1 class="header-title">
                        <?php
                        require_once 'utils.php';
                        $page_url = basename($_SERVER['REQUEST_URI']);
                        $title = get_title($page_url);
                        echo $title;
                        ?>
                    </h1>
                    <hr>

                    <!-- course content -->
                    <!-- <div class="table-card">
                        <div class="text-right py-2">
                            <a href='add_course.php' class="btn btn-primary">Add Course</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Course Name</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    require_once './config/database.php';
                                    $conn = getDatabaseConnection();

                                    // Fetch courses from the database
                                    $query = "SELECT * FROM courses";
                                    $result = $conn->query($query);

                                    if ($result->num_rows > 0) {
                                        while ($course = $result->fetch_assoc()) {
                                            echo '
                                        <tr>
                                            <td>' . htmlspecialchars($course['id']) . '</td>
                                            <td>' . htmlspecialchars($course['title']) . '</td>
                                            <td>' . htmlspecialchars($course['description']) . '</td>
                                            <td class="d-flex align-items-center">
                                                <a href="edit_course.php?id=' . htmlspecialchars($course['id']) . '" class="btn btn-edit mr-2">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </a>
                                                <a href="delete_course.php?id=' . htmlspecialchars($course['id']) . '" class="btn btn-delete" onclick="return confirm(\'Are you sure you want to delete this course?\');">
                                                    <i class="bi bi-trash-fill"></i>
                                                </a>
                                            </td>
                                        </tr>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="4" class="text-center">No courses found.</td></tr>';
                                    }


                                    $conn->close();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div> -->

                    <main class="main">
                        <div class="toolbar">
                            <!-- <input type="search" class="search-input" placeholder="Search courses..."> -->
                            <a class="add-course-btn" href='add_course.php'>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="16"></line>
                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                </svg>
                                Add New Course
                            </a>
                        </div>
                        <div class="course-grid">
                            <?php
                            require_once './config/database.php';
                            $conn = getDatabaseConnection();

                            // Fetch courses from the database
                            $query = "SELECT * FROM courses";
                            $result = $conn->query($query);

                            if ($result->num_rows > 0) {
                                while ($course = $result->fetch_assoc()) {
                                    echo '
                                        <div class="course-card d-flex flex-column justify-content-between">
                                <div class="course-header">
                                    <h2 class="course-title">' . htmlspecialchars($course['title']) . '</h2>
                                </div>
                                <div class="course-content">
                                    <p class="course-description">' . htmlspecialchars($course['description']) . '</p>
                                </div>
                                <div class="course-footer">
                                    <a class="btn btn-outline" href="edit_course.php?id=' . htmlspecialchars($course['id']) . '">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                        Edit
                                    </a>
                                    <a class="btn btn-danger" href="delete_course.php?id=' . htmlspecialchars($course['id']) . '" onclick="return confirm(\'Are you sure you want to delete this module?\');">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                        </svg>
                                        Delete
                                    </a>
                                </div>
                            </div>
                                    ';
                                }
                            } else {
                                echo '<tr><td colspan="4" class="text-center">No courses found.</td></tr>';
                            }
                            ?>
                            <!-- Additional course cards would be repeated here -->
                        </div>
                    </main>
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