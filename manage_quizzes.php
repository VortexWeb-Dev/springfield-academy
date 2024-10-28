<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Academy | Manage Quizzes</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
        #sidebar {
            height: 100vh;
            position: fixed;
        }

        .nav-link.active {
            background-color: #007bff;
            color: white;
        }

        .main-content {
            margin-left: 250px;
        }

        .table thead th {
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
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Include Sidebar -->
            <?php include './inc/sidebar.php'; ?>

            <!-- Main Content -->
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4 main-content">
                <!-- Include Navbar -->
                <?php include './inc/navbar.php'; ?>

                <!-- Quizzes Data Content -->
                <div class="container mt-4">
                    <div class="d-flex w-full align-items-center justify-content-between">
                        <h1>
                            Quizzes
                        </h1>
                        <a href='add_quiz.php' class="btn btn-primary">Add Quiz</a>
                    </div>
                    <hr>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>No. of Questions</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                require_once './config/database.php';
                                $conn = getDatabaseConnection();

                                // Fetch quizzes from the database
                                $query = "SELECT * FROM quizzes";
                                $result = $conn->query($query);

                                if ($result->num_rows > 0) {
                                    while ($quiz = $result->fetch_assoc()) {
                                        echo '
                                        <tr>
                                            <td>' . htmlspecialchars($quiz['quiz_id']) . '</td>
                                            <td><a href="view_quiz.php?id=' . htmlspecialchars($quiz['quiz_id']) . '">' . htmlspecialchars($quiz['title']) . '</a></td>
                                            <td>' . htmlspecialchars($quiz['description']) . '</td>
                                            <td>' . htmlspecialchars($quiz['total_questions']) . '</td>
                                            <td class="d-flex align-items-center">
                                                <a href="edit_quiz.php?id=' . htmlspecialchars($quiz['quiz_id']) . '" class="btn btn-edit mr-2">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </a>
                                                <a href="delete_quiz.php?id=' . htmlspecialchars($quiz['quiz_id']) . '" class="btn btn-delete" onclick="return confirm(\'Are you sure you want to delete this quiz?\');">
                                                    <i class="bi bi-trash-fill"></i>
                                                </a>
                                            </td>
                                        </tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="4" class="text-center">No quizzes found.</td></tr>';
                                }


                                $conn->close();
                                ?>
                            </tbody>
                        </table>
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