<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Academy | My Grades</title>
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

                <!-- Grades Content -->
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
                        <div class="col-12">
                            <?php
                            $student_name = 'John Doe'; // Replace with dynamic user data
                            $grades = [
                                [
                                    'course' => 'Introduction to Computer Science',
                                    'grade' => 'A'
                                ],
                                [
                                    'course' => 'Data Structures and Algorithms',
                                    'grade' => 'B+'
                                ],
                                [
                                    'course' => 'Web Development',
                                    'grade' => 'A-'
                                ],
                                [
                                    'course' => 'Database Systems',
                                    'grade' => 'B'
                                ],
                                [
                                    'course' => 'Operating Systems',
                                    'grade' => 'A'
                                ]
                            ];
                            ?>

                            <h2>Grades for <?php echo $student_name; ?></h2>

                            <table class="table table-bordered mt-3">
                                <thead>
                                    <tr>
                                        <th>Course</th>
                                        <th>Grade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($grades as $grade) : ?>
                                        <tr>
                                            <td><?php echo $grade['course']; ?></td>
                                            <td><?php echo $grade['grade']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
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