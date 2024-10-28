<?php
// Include database configuration
require_once './config/database.php';

// Check if the ID is set in the query parameters
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $certificate_id = $_GET['id'];

    // Create a database connection
    $conn = getDatabaseConnection();

    // Prepare the DELETE statement to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM certificates WHERE id = ?");
    $stmt->bind_param("i", $certificate_id);

    // Execute the statement
    if ($stmt->execute()) {
        // Successful deletion
        echo '<script>window.location.href = "manage_certificates.php";</script>';
    } else {
        // Error during deletion
        echo '<script>alert("Error deleting certificate: ' . htmlspecialchars($stmt->error) . '"); window.location.href = "manage_certificates.php";</script>';
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Invalid ID or not set
    echo '<script>alert("Invalid certificate ID."); window.location.href = "manage_certificates.php";</script>';
}
?>
