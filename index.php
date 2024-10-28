<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Academy | Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles/app.css">
    <style>
        .info-card {
            background-color: #fff;
            /* box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); */
            border: 1.7px solid #ddd;
            margin-bottom: 20px;
            border-radius: 15px;
            padding: 15px;
            height: 8rem;
            transition: border-color 0.2s ease-in-out, background-color 0.2s ease-in-out;
        }

        .info-card:hover {
            border-color: #666;
        }

        .info-card-body {
            /* text-align: center; */
            display: flex !important;
            flex-direction: column !important;
            justify-content: space-between !important;
            height: 100%;
        }

        .info-card-title {
            color: #475569;
            font-size: 1.3rem;
            font-weight: 500;
            text-align: start;
        }

        .banner {
            margin-bottom: 20px;
            border-radius: 15px;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .chart-container {
            position: relative;
            margin: auto;
            height: 300px;
            width: 100%;
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

                <!-- Dashboard Content -->
                <div class="container mt-4">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="banner" style="background-image: url('./assets/banner-final.jpg'); background-repeat: no-repeat; background-size: cover; height: 200px">
                                <div class="card-body h-100 p-4 d-flex flex-column justify-content-between align-items-end">
                                    <h2 class="font-weight-bold text-white">Welcome! To our platform</h2>
                                    <p class="font-weight-light" style=" font-size: 1rem; width: 25rem; color: #f1f5f9; text-align:end;">Explore our platform to discover new opportunities for growth and learning</p>
                                </div>
                            </div>
                        </div>
                        <!-- info card -->
                        <div class="col-md-12">
                            <div class="row gap-2">
                                <div class="col-md-4">
                                    <div class="info-card">
                                        <div class="info-card-body">
                                            <h5 class="info-card-title">Total Courses</h5>
                                            <p class="p-3 font-weight-bold h2 text-right" style="color: #3498db;">42</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="info-card">
                                        <div class="info-card-body">
                                            <h5 class="info-card-title">Enrolled Learners</h5>
                                            <p class="p-3 font-weight-bold h2 text-right" style="color: #e74c3c;">1200</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="info-card">
                                        <div class="info-card-body">
                                            <h5 class="info-card-title">Total Lessons</h5>
                                            <p class="p-3 font-weight-bold h2 text-right" style="color: #2ecc71;">250</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- go to course card -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">My Courses</h5>
                                    <p class="card-text">View and manage your enrolled courses.</p>
                                    <a href="courses.php" class="btn btn-primary">Go to Courses</a>
                                </div>
                            </div>
                        </div>
                        <!-- reports and analytics -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Reports and Analytics</h5>
                                    <div class="chart-container">
                                        <canvas id="performanceChart" style="width: 100%; height: 100%"></canvas>
                                    </div>
                                    <a href="reports.php" class="btn btn-primary w-100">Go to Reports</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <!-- <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">My Development Plans</h5>
                                    <p class="card-text">Track and manage your personal development plans.</p>
                                    <a href="development_plan.php" class="btn btn-primary">Go to Development Plans</a>
                                </div>
                            </div>
                        </div> -->

                    </div>

                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Sample data for the chart
        var performanceData = {
            labels: ['January', 'February', 'March', 'April', 'May', 'June'],
            datasets: [{
                label: 'Performance',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                data: [65, 59, 80, 81, 56, 55],
            }]
        };

        // Chart initialization
        var ctx = document.getElementById('performanceChart').getContext('2d');
        var performanceChart = new Chart(ctx, {
            type: 'line',
            data: performanceData,
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>
</body>

</html>