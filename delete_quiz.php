<?php
require_once './config/database.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $quiz_id = $_GET['id'];

    $conn = getDatabaseConnection();

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Step 1: Delete answers related to the questions of the quiz
        $stmt = $conn->prepare("DELETE a FROM answers a 
                                 JOIN questions q ON a.question_id = q.question_id 
                                 WHERE q.quiz_id = ?");
        $stmt->bind_param("i", $quiz_id);

        if (!$stmt->execute()) {
            throw new Exception("Error deleting answers: " . $stmt->error);
        }

        $stmt->close();

        // Step 2: Delete questions related to the quiz
        $stmt = $conn->prepare("DELETE FROM questions WHERE quiz_id = ?");
        $stmt->bind_param("i", $quiz_id);

        if (!$stmt->execute()) {
            throw new Exception("Error deleting questions: " . $stmt->error);
        }

        $stmt->close();

        // Step 3: Delete the quiz
        $stmt = $conn->prepare("DELETE FROM quizzes WHERE quiz_id = ?");
        $stmt->bind_param("i", $quiz_id);

        if (!$stmt->execute()) {
            throw new Exception("Error deleting quiz: " . $stmt->error);
        }

        $stmt->close();

        // Commit transaction
        $conn->commit();

        echo '<script>window.location.href = "manage_quizzes.php";</script>';
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();
        echo '<script>alert("Error deleting quiz: ' . htmlspecialchars($e->getMessage()) . '"); window.location.href = "manage_quizzes.php";</script>';
    }

    $conn->close();
} else {
    echo '<script>alert("Invalid quiz ID."); window.location.href = "manage_quizzes.php";</script>';
}
