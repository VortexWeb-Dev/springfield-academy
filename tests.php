<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Academy | My Tests</title>
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
                        $page_url = basename($_SERVER['REQUEST_URI']); // ledgers.php
                        $title = get_title($page_url);
                        echo $title;
                        ?>
                    </h1>

                    <hr>

                    <div class="row align-items-center">
                        <div class="col-12">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Test ID</th>
                                        <th>Test Name</th>
                                        <th>Test Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // require_once './crest/crest.php';
                                    // $result = CRest::call('test.get');
                                    $tests = [
                                        ['ID' => 1, 'NAME' => 'Test 1', 'DATE_CREATE' => '2022-01-01'],
                                        ['ID' => 2, 'NAME' => 'Test 2', 'DATE_CREATE' => '2022-01-02'],
                                        ['ID' => 3, 'NAME' => 'Test 3', 'DATE_CREATE' => '2022-01-03'],
                                    ];
                                    foreach ($tests as $test) {
                                        echo "<tr>";
                                        echo "<td>" . $test['ID'] . "</td>";
                                        echo "<td>" . $test['NAME'] . "</td>";
                                        echo "<td>" . $test['DATE_CREATE'] . "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                            </table>
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