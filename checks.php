<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Academy | My Checks</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
        /* Add your custom styles here */
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Include Sidebar -->
            <?php include './inc/sidebar.php'; ?>

            <!-- Main Content -->
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
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

                    <!-- Dummy data for checks (replace with dynamic data) -->
                    <div class="row">
                        <div class="col-md-6">
                            <h2>Recent Checks</h2>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Check 1
                                    <span class="badge badge-primary badge-pill">Pending</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Check 2
                                    <span class="badge badge-success badge-pill">Approved</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Check 3
                                    <span class="badge badge-danger badge-pill">Rejected</span>
                                </li>
                                <!-- Add more checks as needed -->
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h2>Search Checks</h2>
                            <form>
                                <div class="form-group">
                                    <label for="searchInput">Search by ID:</label>
                                    <input type="text" class="form-control" id="searchInput" placeholder="Enter check ID">
                                </div>
                                <button type="submit" class="btn btn-primary">Search</button>
                            </form>
                        </div>
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