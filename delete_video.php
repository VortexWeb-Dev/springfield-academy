<?php
// Include database configuration
require_once './config/database.php';

// Check if the ID is set in the query parameters
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $video_id = $_GET['id'];

    // Create a database connection
    $conn = getDatabaseConnection();

    // Prepare the DELETE statement to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM videos WHERE id = ?");
    $stmt->bind_param("i", $video_id);

    // Execute the statement
    if ($stmt->execute()) {
        // Successful deletion
        echo '<script>window.location.href = "manage_video_lessons.php";</script>';
    } else {
        // Error during deletion
        echo '<script>alert("Error deleting course: ' . htmlspecialchars($stmt->error) . '"); window.location.href = "manage_video_lessons.php";</script>';
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Invalid ID or not set
    echo '<script>alert("Invalid course ID."); window.location.href = "manage_video_lessons.php";</script>';
}
?>