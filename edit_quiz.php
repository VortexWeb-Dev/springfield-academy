<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once './config/database.php';
$conn = getDatabaseConnection();

$quiz_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// Fetch quiz details
$query = "SELECT * FROM quizzes WHERE quiz_id = ?";
$stmt = $conn->prepare($query);
if ($stmt === false) {
    die("Prepare failed: " . htmlspecialchars($conn->error));
}
$stmt->bind_param('i', $quiz_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $quiz = $result->fetch_assoc();
} else {
    echo '<p>Quiz not found.</p>';
    $quiz = null;
}
$stmt->close();

// Fetch questions and answers for the quiz
$questions_query = "SELECT q.*, a.answer_id, a.answer_text, a.is_correct
                    FROM questions q 
                    LEFT JOIN answers a ON q.question_id = a.question_id
                    WHERE q.quiz_id = ?";
$stmt = $conn->prepare($questions_query);
$stmt->bind_param('i', $quiz_id);
$stmt->execute();
$questions_result = $stmt->get_result();

$questions = [];
while ($row = $questions_result->fetch_assoc()) {
    $question_id = $row['question_id'];
    if (!isset($questions[$question_id])) {
        $questions[$question_id] = [
            'question_id' => $question_id,
            'question_text' => $row['question_text'],
            'question_type' => $row['question_type'],
            'answers' => []
        ];
    }
    $questions[$question_id]['answers'][] = [
        'answer_id' => $row['answer_id'],
        'answer_text' => $row['answer_text'],
        'is_correct' => $row['is_correct']
    ];
}
$stmt->close();

// Handle form submission for quiz update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Update quiz details
    $update_query = "UPDATE quizzes SET title = ?, description = ? WHERE quiz_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param('ssi', $title, $description, $quiz_id);
    $update_stmt->execute();
    $update_stmt->close();

    // Update questions and answers
    foreach ($_POST['questions'] as $question_id => $question_data) {
        $question_text = $question_data['question_text'];
        $question_type = $question_data['question_type'];

        // Update question
        $update_question_query = "UPDATE questions SET question_text = ?, question_type = ? WHERE question_id = ?";
        $update_stmt = $conn->prepare($update_question_query);
        $update_stmt->bind_param('ssi', $question_text, $question_type, $question_id);
        $update_stmt->execute();
        $update_stmt->close();

        // Update or insert answers
        foreach ($question_data['answers'] as $answer_id => $answer_data) {
            $answer_text = $answer_data['answer_text'];
            $is_correct = isset($answer_data['is_correct']) ? 1 : 0;

            if ($answer_id == 'new') {
                $insert_answer_query = "INSERT INTO answers (question_id, answer_text, is_correct) VALUES (?, ?, ?)";
                $insert_stmt = $conn->prepare($insert_answer_query);
                $insert_stmt->bind_param('isi', $question_id, $answer_text, $is_correct);
                $insert_stmt->execute();
                $insert_stmt->close();
            } else {
                $update_answer_query = "UPDATE answers SET answer_text = ?, is_correct = ? WHERE answer_id = ?";
                $update_stmt = $conn->prepare($update_answer_query);
                $update_stmt->bind_param('sii', $answer_text, $is_correct, $answer_id);
                $update_stmt->execute();
                $update_stmt->close();
            }
        }
    }

    echo '<script>window.location.href = "manage_quizzes.php";</script>';
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Academy | Edit Quiz</title>
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
        <!-- Sidebar and Navbar -->
        <?php include './inc/sidebar.php'; ?>
        <?php include './inc/navbar.php'; ?>

        <div class="container mt-4">
            <h1>Edit Quiz</h1>
            <hr>

            <form action="./edit_quiz.php?id=<?php echo $quiz_id; ?>" method="post">
                <!-- Quiz Details -->
                <div class="form-group">
                    <label for="title">Quiz Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($quiz['title']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($quiz['description']); ?></textarea>
                </div>

                <!-- Questions and Answers -->
                <?php foreach ($questions as $question): ?>
                    <div class="card mt-3">
                        <div class="card-body">
                            <h5>Question</h5>
                            <div class="form-group">
                                <input type="text" class="form-control" name="questions[<?php echo $question['question_id']; ?>][question_text]" value="<?php echo htmlspecialchars($question['question_text']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Question Type</label>
                                <select class="form-control" name="questions[<?php echo $question['question_id']; ?>][question_type]" required>
                                    <option value="multiple_choice" <?php echo $question['question_type'] == 'multiple_choice' ? 'selected' : ''; ?>>Multiple Choice</option>
                                    <option value="true_false" <?php echo $question['question_type'] == 'true_false' ? 'selected' : ''; ?>>True/False</option>
                                    <option value="short_answer" <?php echo $question['question_type'] == 'short_answer' ? 'selected' : ''; ?>>Short Answer</option>
                                </select>
                            </div>
                            <div id="answers-container-<?php echo $question['question_id']; ?>">
                                <?php foreach ($question['answers'] as $answer): ?>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="questions[<?php echo $question['question_id']; ?>][answers][<?php echo $answer['answer_id']; ?>][answer_text]" value="<?php echo htmlspecialchars($answer['answer_text']); ?>" required>
                                        <label class="ml-2">
                                            <input type="checkbox" name="questions[<?php echo $question['question_id']; ?>][answers][<?php echo $answer['answer_id']; ?>][is_correct]" <?php echo $answer['is_correct'] ? 'checked' : ''; ?>> Correct
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addAnswer(<?php echo $question['question_id']; ?>)">Add Answer</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary mt-3">Update Quiz</button>
            </form>
        </div>
    </div>

    <script>
        function addAnswer(questionId) {
            const answerContainer = document.getElementById('answers-container-' + questionId);
            const answerHTML = `
                <div class="form-group">
                    <input type="text" class="form-control" name="questions[${questionId}][answers][new][answer_text]" placeholder="Answer Text" required>
                    <label class="ml-2">
                        <input type="checkbox" name="questions[${questionId}][answers][new][is_correct]"> Correct
                    </label>
                </div>
            `;
            answerContainer.insertAdjacentHTML('beforeend', answerHTML);
        }
    </script>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>