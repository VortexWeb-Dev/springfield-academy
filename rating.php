<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Academy | My Rating</title>
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

        .scorecard {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 300px;
            min-height: 200px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f8f9fa;
            padding: 1rem;
            text-align: center;
        }

        .scorecard .score {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .scorecard .score i {
            margin-left: 10px;
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

                    <div class="row align-items-center">
                        <div class="col-12 d-flex justify-content-center">
                            <div class="scorecard">
                                <i class="bi bi-person-circle" style="font-size: 5rem;"></i>
                                <div class="score mt-3">
                                    <h2>My Score: 85</h2>

                                </div>
                                <h3>
                                    <?php

                                    function get_level($score)
                                    {
                                        switch ($score) {
                                            case $score < 20:
                                                return 'Beginner';
                                            case $score < 40:
                                                return 'Intermediate';
                                            case $score < 60:
                                                return 'Advanced';
                                            default:
                                                return 'Pro';
                                        }
                                    }

                                    echo get_level('85');
                                    ?>
                                    <i class="bi bi-question-circle" style="font-size: 1rem;" data-toggle="modal" data-target="#scoreModal"></i>
                                </h3>
                                <p class="mt-3 text-muted "> <i class="bi bi-info-circle"></i> Scores are given for completed training and successfully completed testing.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>

    <!-- Score Details Modal -->
    <div class="modal fade" id="scoreModal" tabindex="-1" aria-labelledby="scoreModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scoreModalLabel">Score Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>My Score:</strong> 85</p>
                    <p><strong>Details:</strong></p>
                    <ul class="list-unstyled">
                        <li>0 - 19: Beginner </li>
                        <li>20 - 39: Intermediate </li>
                        <li>40 - 59: Advanced</li>
                        <li>60+: Pro</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>