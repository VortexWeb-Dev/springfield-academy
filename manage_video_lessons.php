<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Academy | Manage Video Lessons</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles/app.css">
    <link rel="stylesheet" href="styles/manage-course.css">
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

                <!-- Video Lessons Data Content -->
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


                    <main class="main">
                        <div class="toolbar">
                            <!-- <input type="search" class="search-input" placeholder="Search videos..."> -->
                            <a class="add-video-btn" href='add_video.php'>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="16"></line>
                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                </svg>
                                Add New Video
                            </a>
                        </div>
                        <div class="video-grid">
                            <?php
                            require_once './config/database.php';
                            $conn = getDatabaseConnection();

                            // Fetch videos from the database
                            $query = "SELECT * FROM videos";
                            $result = $conn->query($query);

                            if ($result->num_rows > 0) {
                                while ($video = $result->fetch_assoc()) {
                                    echo '
                                        <div class="video-card d-flex flex-column justify-content-between">
                                <div class="video-header">
                                    <h2 class="video-title">' . htmlspecialchars($video['title']) . '</h2>
                                </div>
                                <div class="video-content">
                                    <p class="video-description">' . htmlspecialchars($video['description']) . '</p>
                                </div>
                                <div class="video-footer">
                                    <a class="btn btn-outline" href="edit_video.php?id=' . htmlspecialchars($video['id']) . '">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                        </svg>
                                        Edit
                                    </a>
                                    <a class="btn btn-danger" href="delete_video.php?id=' . htmlspecialchars($video['id']) . '" onclick="return confirm(\'Are you sure you want to delete this video?\');">
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
                                echo '<tr><td colspan="4" class="text-center">No videos found.</td></tr>';
                            }
                            ?>
                            <!-- Additional video cards would be repeated here -->
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