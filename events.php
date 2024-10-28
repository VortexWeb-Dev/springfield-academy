<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Academy | My Events</title>
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

        .event-card {
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

                    <!-- Dummy Data for Events (replace with dynamic data) -->
                    <div class="row">
                        <?php
                        $events = [
                            [
                                'id' => 1,
                                'title' => 'Academy Open Day',
                                'description' => 'An event to showcase university programs and facilities.',
                                'date' => '2024-08-15',
                                'time' => '10:00 AM'
                            ],
                            [
                                'id' => 2,
                                'title' => 'Career Fair',
                                'description' => 'Connect with employers and explore job opportunities.',
                                'date' => '2024-09-20',
                                'time' => '2:00 PM'
                            ],
                            [
                                'id' => 3,
                                'title' => 'Tech Symposium',
                                'description' => 'Featuring talks and workshops on emerging technologies.',
                                'date' => '2024-10-10',
                                'time' => '9:30 AM'
                            ]
                            // Add more events as needed
                        ];

                        foreach ($events as $event) {
                            echo '
                            <div class="col-md-6">
                                <div class="card event-card">
                                    <div class="card-body">
                                        <h5 class="card-title">' . $event['title'] . '</h5>
                                        <p class="card-text">' . $event['description'] . '</p>
                                        <p class="card-text"><strong>Date:</strong> ' . date('M j, Y', strtotime($event['date'])) . '</p>
                                        <p class="card-text"><strong>Time:</strong> ' . date('h:i A', strtotime($event['time'])) . '</p>
                                       
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