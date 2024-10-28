<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Academy | My Programs</title>
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

        .program-card {
            margin-bottom: 20px;
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

                <!-- Dummy Data Content -->
                <div class="container mt-4">
                    <h1>
                        <?php
                        require_once 'utils.php';
                        $page_url = basename($_SERVER['REQUEST_URI']);
                        $title = get_title($page_url);
                        echo $title;
                        ?>
                    </h1>

                    <!-- Dummy Data for Programs (replace with dynamic data) -->
                    <div class="row">
                        <?php
                        $programs = [
                            [
                                'id' => 1,
                                'name' => 'Bachelor of Science in Computer Science',
                                'description' => 'A comprehensive program covering key aspects of computer science.',
                                'duration' => '4 years'
                            ],
                            [
                                'id' => 2,
                                'name' => 'Bachelor of Arts in Psychology',
                                'description' => 'Explore human behavior and psychological theories.',
                                'duration' => '3 years'
                            ],
                            [
                                'id' => 3,
                                'name' => 'Master of Business Administration (MBA)',
                                'description' => 'Develop management skills and strategic thinking.',
                                'duration' => '2 years'
                            ]
                            // Add more programs as needed
                        ];

                        foreach ($programs as $program) {
                            echo '
                            <div class="col-md-6">
                                <div class="card program-card">
                                    <div class="card-body">
                                        <h5 class="card-title">' . $program['name'] . '</h5>
                                        <p class="card-text">' . $program['description'] . '</p>
                                        <p class="card-text"><strong>Duration:</strong> ' . $program['duration'] . '</p>
                                        <a href="#" class="btn btn-primary mr-2">Details</a>
                                        <a href="#" class="btn btn-success">Apply</a>
                                    </div>
                                </div>
                            </div>';
                        }
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