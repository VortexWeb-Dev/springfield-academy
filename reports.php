<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Academy | My Reports</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles/app.css">
    <style>
        body {
            background-color: #f4f7fa;
            font-family: 'Arial', sans-serif;
        }

        .chart-card {
            transition: transform 0.3s, box-shadow 0.3s;
            border-radius: 15px;
            padding: 2rem 1rem;
            height: 30rem;
            /* min-height: 30rem; */
            /* max-height: 30rem; */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            background: #ffffff;
            overflow: hidden;
        }

        .chart-card-body {
            text-align: center;
            height: 90%;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .chart-card-title {
            color: #64748b;
            font-weight: bold;
            text-align: center;
        }

        .chart-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        }

        canvas {
            width: 100% !important;
            height: 100% !important;
        }

        @media (max-width: 768px) {
            .chart-card {
                margin-bottom: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Include Sidebar -->
            <?php include './inc/sidebar.php'; ?>

            <!-- Main Content -->
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 p-0">
                <!-- Include Navbar -->
                <?php include './inc/navbar.php'; ?>

                <!-- Reports Content -->
                <div class="container mt-4">
                    <h1 class="header-title">Reports</h1>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="chart-card">
                                <h5 class="chart-card-title">Student Enrollment</h5>
                                <div class="chart-card-body">
                                    <canvas id="studentEnrollmentChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="chart-card">
                                <h5 class="chart-card-title">Course Completion</h5>
                                <div class="chart-card-body">
                                    <canvas id="courseCompletionChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Dummy data for demonstration
        var enrollmentData = {
            labels: ['January', 'February', 'March', 'April', 'May', 'June'],
            datasets: [{
                label: 'Enrollment',
                data: [65, 59, 80, 81, 56, 55],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };

        var completionData = {
            labels: ['January', 'February', 'March', 'April', 'May', 'June'],
            datasets: [{
                label: 'Completion',
                data: [30, 45, 70, 60, 50, 75],
                backgroundColor: 'rgba(255, 159, 64, 0.2)',
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 1
            }]
        };

        // Initialize charts
        var enrollmentCtx = document.getElementById('studentEnrollmentChart').getContext('2d');
        var enrollmentChart = new Chart(enrollmentCtx, {
            type: 'bar',
            data: enrollmentData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        var completionCtx = document.getElementById('courseCompletionChart').getContext('2d');
        var completionChart = new Chart(completionCtx, {
            type: 'line',
            data: completionData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>