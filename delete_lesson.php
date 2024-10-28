<?php
// Include database configuration
require_once './config/database.php';

// Check if the ID is set in the query parameters
if (isset($_GET['id']) && isset($_GET['pdf_path']) && is_numeric($_GET['id'])) {
    $lesson_id = $_GET['id'];
    $pdf_path = $_GET['pdf_path'];

    // Create a database connection
    $conn = getDatabaseConnection();

    // Prepare the DELETE statement to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM lessons WHERE id = ?");
    $stmt->bind_param("i", $lesson_id);

    // Execute the statement
    if ($stmt->execute()) {
        // Successful deletion

        // Delete the PDF file if it exists
        if (file_exists($pdf_path)) {
            unlink($pdf_path);
        }

        echo '<script>window.location.href = "manage_lessons.php";</script>';
    } else {
        // Error during deletion
        echo '<script>alert("Error deleting lesson: ' . htmlspecialchars($stmt->error) . '"); window.location.href = "manage_lessons.php";</script>';
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Invalid ID or not set
    echo '<script>alert("Invalid lesson ID."); window.location.href = "manage_lessons.php";</script>';
}
