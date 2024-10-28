<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Academy | My Competencies</title>
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

        .competency-card {
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

                    <!-- Dummy Data for Competencies (replace with dynamic data) -->
                    <div class="row">
                        <?php
                        $competencies = [
                            [
                                'id' => 1,
                                'name' => 'Programming in Python',
                                'description' => 'Proficient in Python programming language.',
                                'level' => 'Advanced'
                            ],
                            [
                                'id' => 2,
                                'name' => 'Web Development',
                                'description' => 'Skilled in front-end and back-end web development.',
                                'level' => 'Intermediate'
                            ],
                            [
                                'id' => 3,
                                'name' => 'Data Analysis',
                                'description' => 'Experience in analyzing and interpreting data.',
                                'level' => 'Advanced'
                            ]
                            // Add more competencies as needed
                        ];

                        foreach ($competencies as $competency) {
                            echo '
                            <div class="col-md-6">
                                <div class="card competency-card">
                                    <div class="card-body">
                                        <h5 class="card-title">' . $competency['name'] . '</h5>
                                        <p class="card-text">' . $competency['description'] . '</p>
                                        <p class="card-text"><strong>Level:</strong> ' . $competency['level'] . '</p>
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