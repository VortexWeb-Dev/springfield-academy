<?php
require_once '../config/database.php';

$conn = getDatabaseConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_name = $_POST['course_name'];
    $course_code = $_POST['course_code'];
    $description = $_POST['description'];
    $youtube_link = $_POST['youtube_link'];

    // Prepare the SQL statement
    $query = "INSERT INTO courses (course_name, course_code, description, youtube_link) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    // Check if the statement was prepared correctly
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    // Bind parameters to the statement
    $stmt->bind_param("ssss", $course_name, $course_code, $description, $youtube_link);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New course added successfully";
    } else {
        echo "Error: " . htmlspecialchars($stmt->error);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // Redirect to the courses page
    header("Location: ../courses.php");
    exit();
}
