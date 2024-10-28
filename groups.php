<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Academy | My Groups</title>
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

        .group-card {
            transition: transform 0.2s;
        }

        .group-card:hover {
            transform: scale(1.05);
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

                    <hr>

                    <!-- Enrolled Groups Section -->
                    <section id="enrolled-groups">
                        <h2>Enrolled Groups</h2>
                        <div class="row">
                            <?php
                            // Dummy data for enrolled groups (replace with dynamic data)
                            $enrolled_groups = [
                                [
                                    'id' => 1,
                                    'name' => 'Computer Science Club',
                                    'description' => 'A group for students interested in computer science.',
                                    'members' => 35
                                ],
                                [
                                    'id' => 2,
                                    'name' => 'Math Society',
                                    'description' => 'A society for students who love mathematics.',
                                    'members' => 20
                                ]
                            ];

                            foreach ($enrolled_groups as $group) {
                                echo '
                                <div class="col-md-4">
                                    <div class="card mb-4 group-card">
                                        <div class="card-body">
                                            <h5 class="card-title">' . $group['name'] . '</h5>
                                            <p class="card-text">' . $group['description'] . '</p>
                                            <p class="card-text"><strong>Members:</strong> ' . $group['members'] . '</p>
                                            <a href="#" class="btn btn-primary">View Details</a>
                                        </div>
                                    </div>
                                </div>';
                            }
                            ?>
                        </div>
                    </section>

                    <hr>

                    <!-- All Groups Section -->
                    <section id="all-groups">
                        <h2>All Groups</h2>
                        <div class="row">
                            <?php
                            // Dummy data for all groups (replace with dynamic data)
                            $all_groups = [
                                [
                                    'id' => 1,
                                    'name' => 'Computer Science Club',
                                    'description' => 'A group for students interested in computer science.',
                                    'members' => 35
                                ],
                                [
                                    'id' => 2,
                                    'name' => 'Math Society',
                                    'description' => 'A society for students who love mathematics.',
                                    'members' => 20
                                ],
                                [
                                    'id' => 3,
                                    'name' => 'Robotics Team',
                                    'description' => 'A team dedicated to building and programming robots.',
                                    'members' => 15
                                ]
                            ];

                            foreach ($all_groups as $group) {
                                echo '
                                <div class="col-md-4">
                                    <div class="card mb-4 group-card">
                                        <div class="card-body">
                                            <h5 class="card-title">' . $group['name'] . '</h5>
                                            <p class="card-text">' . $group['description'] . '</p>
                                            <p class="card-text"><strong>Members:</strong> ' . $group['members'] . '</p>
                                            <a href="#" class="btn btn-primary">View Details</a>
                                        </div>
                                    </div>
                                </div>';
                            }
                            ?>
                        </div>
                    </section>

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