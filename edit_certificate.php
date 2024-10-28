<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once './config/database.php';
$conn = getDatabaseConnection();

$certificate_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// Fetch certificate details from the database
$query = "SELECT * FROM certificates WHERE id = ?";
$stmt = $conn->prepare($query);

if ($stmt === false) {
    die("Prepare failed: " . htmlspecialchars($conn->error));
}

$stmt->bind_param('i', $certificate_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $certificate = $result->fetch_assoc();
} else {
    echo '<p>Certificate not found.</p>';
    $certificate = null;
}

$stmt->close();

// Handle form submission for certificate update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $certificate_name = $_POST['certificate_name'];
    $description = $_POST['description'];

    // Check if a new file is uploaded
    if (isset($_FILES['certificate_file']) && $_FILES['certificate_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['certificate_file']['tmp_name'];
        $fileContent = file_get_contents($fileTmpPath);

        if ($fileContent === false) {
            die("Error reading file content.");
        }

        // Update certificate including the new file
        $update_query = "UPDATE certificates SET certificate_name = ?, description = ?, certificate_file = ?, updated_at = NOW() WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);

        if ($update_stmt === false) {
            die("Prepare failed: " . htmlspecialchars($conn->error));
        }

        $update_stmt->bind_param('ssbi', $certificate_name, $description, $fileContent, $certificate_id);
        $update_stmt->send_long_data(2, $fileContent);  // Send binary data
    } else {
        // No new file uploaded, update without file
        $update_query = "UPDATE certificates SET certificate_name = ?, description = ?, updated_at = NOW() WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);

        if ($update_stmt === false) {
            die("Prepare failed: " . htmlspecialchars($conn->error));
        }

        $update_stmt->bind_param('ssi', $certificate_name, $description, $certificate_id);
    }

    $update_success = $update_stmt->execute();

    if ($update_success) {
        echo '<script>window.location.href = "manage_certificates.php";</script>';
    } else {
        echo '<script>alert("Error updating certificate: ' . htmlspecialchars($conn->error) . '");</script>';
    }

    $update_stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Academy | Edit Certificate</title>
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

                <div class="container mt-4">
                    <h1>Edit Certificate</h1>
                    <hr>

                    <div class="row align-items-center mb-5">
                        <form action="./edit_certificate.php?id=<?php echo $certificate_id; ?>" method="post" class="col-12" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="certificate_name">Certificate Name</label>
                                <input type="text" class="form-control" id="certificate_name" name="certificate_name" value="<?php echo htmlspecialchars($certificate['certificate_name']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($certificate['description']); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="certificate_file">Certificate File (Leave blank to keep the existing file)</label>
                                <input type="file" class="form-control-file" id="certificate_file" name="certificate_file">

                                <?php if (!empty($certificate['certificate_file'])): ?>
                                    <div class="mt-2">
                                        <p>Existing Certificate File:</p>
                                        <?php
                                        // Assuming the file is a binary file stored in the database, you'll need to create a download link for it.
                                        // You may also want to store the actual file name or path in the database.
                                        $fileData = base64_encode($certificate['certificate_file']); // Convert binary data to base64
                                        $fileName = 'certificate_' . $certificate_id; // You can use the original filename if stored in the database
                                        ?>

                                        <!-- Provide a download link -->
                                        <a href="data:application/octet-stream;base64,<?php echo $fileData; ?>" download="<?php echo $fileName; ?>">
                                            Download Existing File
                                        </a>

                                        <!-- If it's a PDF, you can provide an embedded viewer -->
                                        <iframe src="data:application/pdf;base64,<?php echo $fileData; ?>" style="width: 100%; height: 400px;" frameborder="0"></iframe>
                                    </div>
                                <?php endif; ?>
                            </div>


                            <div class="d-flex">
                                <a href="manage_certificates.php" class="btn btn-secondary mr-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
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