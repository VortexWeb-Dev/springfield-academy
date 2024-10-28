<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Academy | My Courses</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles/app.css">

    <style>
        body {
            background-color: #f4f7fa;
            font-family: 'Arial', sans-serif;
        }

        .course-card {
            transition: transform 0.3s, box-shadow 0.3s;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            background: #ffffff;
            overflow: hidden;
        }

        .course-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .course-card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 20px;
            height: 100%;
        }

        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        }

        .course-card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #343a40;
            margin: 0;
            max-height: 3em;
            /* Limit height to 3 lines */
            overflow: hidden;
            /* Hide overflow text */
            text-overflow: ellipsis;
            /* Show ellipsis for overflow text */
            display: -webkit-box;
            /* Necessary for the following two lines */
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            /* Set to 2 lines for the title */
        }

        .course-card-text {
            font-size: 1rem;
            color: #6c757d;
            margin: 10px 0;
            flex-grow: 1;
            /* Allow text area to grow and fill space */
            overflow: hidden;
            /* Hide overflow text */
            text-overflow: ellipsis;
            /* Show ellipsis for overflow text */
            display: -webkit-box;
            /* Necessary for the following two lines */
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 3;
            /* Number of lines to display */
        }

        .error {
            color: red;
            font-weight: bold;
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

                <!-- Content -->
                <div class="container mt-4">
                    <h1 class="header-title">Enrolled Courses</h1>
                    <hr>
                    <div class="row">
                        <?php
                        // Database connection
                        require_once 'config/database.php';

                        // Use prepared statement to prevent SQL injection
                        $conn = getDatabaseConnection();

                        // Prepare the statement
                        $stmt = $conn->prepare("SELECT * FROM courses");
                        if (!$stmt) {
                            echo '<p class="error">Error preparing statement: ' . htmlspecialchars($conn->error) . '</p>';
                            exit;
                        }

                        // Execute the statement
                        if (!$stmt->execute()) {
                            echo '<p class="error">Error executing statement: ' . htmlspecialchars($stmt->error) . '</p>';
                            exit;
                        }

                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            while ($course = $result->fetch_assoc()) {

                                $imageUrl = !empty($course['image_url']) ? htmlspecialchars($course['image_url']) : 'https://cdn.pixabay.com/photo/2018/02/27/10/49/training-3185170_1280.jpg';
                                echo '<div class="col-md-4 mb-4">
                                        <div class="course-card">
                                            <img src="' . $imageUrl . '" alt="Course Image" class="card-img-top">
                                            <div class="course-card-body">
                                                <h5 class="course-card-title">' . htmlspecialchars($course['title']) . '</h5>
                                                <p class="course-card-text">' . htmlspecialchars($course['description']) . '</p>
                                                <a href="courseDetails.php?id=' . $course['id'] . '" class="btn btn-primary">View Course</a>
                                            </div>
                                        </div>
                                    </div>';
                            }
                        } else {
                            echo '<p>No courses available.</p>';
                        }

                        // Close statement and connection
                        $stmt->close();
                        $conn->close();
                        ?>
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