<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Academy | Video Lessons</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles/app.css">

    <style>
        body {
            background-color: #f4f7fa;
            font-family: 'Arial', sans-serif;
        }

        .video-card {
            transition: transform 0.3s, box-shadow 0.3s;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            background: #ffffff;
            overflow: hidden;
        }

        .video-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .video-card-body {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 20px;
            height: 100%;
        }

        .video-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        }

        .video-card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #343a40;
            margin: 0;
            max-height: 3em;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }

        .video-card-text {
            font-size: 1rem;
            color: #6c757d;
            margin: 10px 0;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 3;
            /* Show only 3 lines */
            transition: max-height 0.3s ease;
        }

        .video-card-text.expanded {
            -webkit-line-clamp: unset;
            /* Remove line clamping */
            max-height: none;
        }

        .error {
            color: red;
            font-weight: bold;
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

                <!-- Content -->
                <div class="container mt-4">
                    <h1 class="header-title">Video Lessons</h1>
                    <hr>
                    <div class="row">
                        <?php
                        require_once 'config/database.php';

                        $conn = getDatabaseConnection();

                        // Prepare the statement
                        $stmt = $conn->prepare("SELECT * FROM videos");
                        if (!$stmt) {
                            echo '<p class="error">Error preparing statement: ' . htmlspecialchars($conn->error) . '</p>';
                            exit;
                        }

                        // Execute the statement
                        if (!$stmt->execute()) {
                            echo '<p class="error">Error executing statement: ' . htmlspecialchars($stmt->error) . '</p>';
                            exit;
                        }

                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            while ($video = $result->fetch_assoc()) {

                                $imageUrl = !empty($video['image_url']) ? htmlspecialchars($video['image_url']) : 'https://cdn.pixabay.com/photo/2018/02/27/10/49/training-3185170_1280.jpg';
                                echo '<div class="col-md-4 mb-4">
                                    <div class="video-card">
                                        <img src="' . $imageUrl . '" alt="video Image" class="card-img-top">
                                        <div class="video-card-body">
                                            <h5 class="video-card-title">' . htmlspecialchars($video['title']) . '</h5>
                                            <p class="video-card-text" id="desc-' . htmlspecialchars($video['id']) . '">' . htmlspecialchars($video['description']) . '</p>
                                            <a href="javascript:void(0);" class="read-more" data-id="' . htmlspecialchars($video['id']) . '">Read more...</a>
                                            <a href="#" class="btn btn-primary play-btn" data-toggle="modal" data-target="#videoModal" data-url="' . htmlspecialchars($video['youtube_link']) . '">Play</a>
                                        </div>
                                    </div>
                                </div>';
                            }
                        } else {
                            echo '<p>No videos available.</p>';
                        }

                        $stmt->close();
                        $conn->close();
                        ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Video Modal -->
    <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="videoModalLabel">Video Player</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe id="videoIframe" class="embed-responsive-item" src="" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#videoModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var videoUrl = button.data('url'); // Extract video URL from data-url attribute

                // Update the iframe src with the video URL
                var iframe = $('#videoIframe');
                iframe.attr('src', videoUrl);
            });

            $('#videoModal').on('hidden.bs.modal', function() {
                // Remove the video URL when the modal is closed
                $('#videoIframe').attr('src', '');
            });
        });

        $(document).ready(function() {
            $('.read-more').on('click', function() {
                var id = $(this).data('id');
                var description = $('#desc-' + id);
                if (description.hasClass('expanded')) {
                    description.removeClass('expanded');
                    $(this).text('Read more...');
                } else {
                    description.addClass('expanded');
                    $(this).text('Read less');
                }
            });
        });
    </script>
</body>

</html>