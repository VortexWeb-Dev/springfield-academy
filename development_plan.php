<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Academy | Development Plan</title>
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

        .plan-card {
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

                <!-- IDP Content -->
                <div class="container mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1>Individual Development Plans</h1>
                        <a href="add_development_plan.php" class="btn btn-primary">Add Plan</a>
                    </div>
                    <div class="row">
                        <?php
                        // Dummy data for demonstration
                        $plans = [
                            ['id' => 1, 'title' => 'Learn JavaScript', 'status' => 'In Progress', 'details' => 'This plan involves learning JavaScript fundamentals and advanced topics.'],
                            ['id' => 2, 'title' => 'Complete React Project', 'status' => 'Completed', 'details' => 'Finish the React project by implementing state management and responsive design.'],
                            ['id' => 3, 'title' => 'Study Algorithms', 'status' => 'Not Started', 'details' => 'Prepare for coding interviews by mastering algorithms and data structures.']
                        ];

                        foreach ($plans as $plan) {
                            echo '
                            <div class="col-md-4">
                                <div class="card plan-card">
                                    <div class="card-body">
                                        <h5 class="card-title">' . $plan['title'] . '</h5>
                                        <p class="card-text"><strong>Status:</strong> ' . $plan['status'] . '</p>
                                        <p class="card-text">' . $plan['details'] . '</p>
                                        <a href="#" class="btn btn-primary mr-2">View Details</a>';

                            // Show mark as completed button only if status is not "Completed"
                            if ($plan['status'] !== 'Completed') {
                                echo '
                                        <form action="mark_completed.php" method="POST" style="display: inline;">
                                            <input type="hidden" name="plan_id" value="' . $plan['id'] . '">
                                            <button type="submit" class="btn btn-success">Mark as Completed</button>
                                        </form>';
                            }

                            echo '
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