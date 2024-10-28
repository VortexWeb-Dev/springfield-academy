<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once './config/database.php';
$conn = getDatabaseConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';

    // Start transaction
    $conn->begin_transaction();

    try {
        // Insert the quiz
        $query = "INSERT INTO quizzes (title, description) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ss', $title, $description);

        if (!$stmt->execute()) {
            throw new Exception("Quiz insertion failed: " . $stmt->error);
        }

        $quiz_id = $stmt->insert_id;
        $stmt->close();

        // Initialize total questions counter
        $total_questions = 0;

        // Insert questions and answers
        foreach ($_POST['questions'] as $index => $question) {
            $question_text = $question['question_text'];
            $question_type = $question['question_type'];

            $query = "INSERT INTO questions (quiz_id, question_text, question_type) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('iss', $quiz_id, $question_text, $question_type);

            if (!$stmt->execute()) {
                throw new Exception("Question insertion failed: " . $stmt->error);
            }

            $question_id = $stmt->insert_id;
            $stmt->close();

            // Increment total questions counter
            $total_questions++;

            // Insert answers for each question
            foreach ($question['answers'] as $answer) {
                $answer_text = $answer['answer_text'];
                $is_correct = isset($answer['is_correct']) ? 1 : 0;

                $query = "INSERT INTO answers (question_id, answer_text, is_correct) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('isi', $question_id, $answer_text, $is_correct);

                if (!$stmt->execute()) {
                    throw new Exception("Answer insertion failed: " . $stmt->error);
                }

                $stmt->close();
            }
        }

        // Update the total_questions field in quizzes
        $query = "UPDATE quizzes SET total_questions = ? WHERE quiz_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ii', $total_questions, $quiz_id);

        if (!$stmt->execute()) {
            throw new Exception("Total questions update failed: " . $stmt->error);
        }

        $stmt->close();

        // Commit transaction
        $conn->commit();

        header("Location: manage_quizzes.php");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Academy | Add Quiz</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

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
            <!-- Sidebar -->
            <?php include './inc/sidebar.php'; ?>

            <!-- Main Content -->
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4 main-content">
                <!-- Navbar -->
                <?php include './inc/navbar.php'; ?>

                <div class="container mt-4">
                    <h1>Add Quiz</h1>
                    <hr>

                    <form action="./add_quiz.php" method="post" id="quizForm">
                        <!-- Quiz Details -->
                        <div class="form-group">
                            <label for="title">Quiz Name</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <!-- Questions and Answers -->
                        <div id="questions-container"></div>

                        <button type="button" class="btn btn-success" onclick="addQuestion()">Add Question</button>

                        <!-- Submit Button -->
                        <div class="d-flex mt-4">
                            <a href="manage_quizzes.php" class="btn btn-secondary mr-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Add Quiz</button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- JavaScript for Dynamic Questions and Answers -->
    <script>
        let questionCount = 0;
        let answerCounts = {}; // Track the answer count for each question

        function addQuestion() {
            questionCount++;
            answerCounts[questionCount] = 0; // Initialize answer count for the new question

            const questionHTML = `
            <div class="card mt-3" id="question-${questionCount}">
                <div class="card-body">
                    <h5>Question ${questionCount}</h5>
                    <div class="form-group">
                        <label>Question Text</label>
                        <input type="text" class="form-control" name="questions[${questionCount}][question_text]" required>
                    </div>
                    <div class="form-group">
                        <label>Question Type</label>
                        <select class="form-control" name="questions[${questionCount}][question_type]" required>
                            <option value="multiple_choice">Multiple Choice</option>
                            <option value="true_false">True/False</option>
                            <option value="short_answer">Short Answer</option>
                        </select>
                    </div>
                    <div id="answers-container-${questionCount}">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addAnswer(${questionCount})">Add Answer</button>
                    </div>
                    <button type="button" class="btn btn-danger mt-2" onclick="removeElement('question-${questionCount}')">Remove Question</button>
                </div>
            </div>
        `;
            $('#questions-container').append(questionHTML);
        }

        function addAnswer(questionId) {
            // Increment answer count for the specific question
            answerCounts[questionId]++;

            const answerHTML = `
            <div class="form-group mt-2" id="answer-${questionId}-${answerCounts[questionId]}">
                <input type="text" class="form-control d-inline-block" name="questions[${questionId}][answers][${answerCounts[questionId]}][answer_text]" placeholder="Answer Text" required>
                <label class="ml-2">
                    <input type="checkbox" name="questions[${questionId}][answers][${answerCounts[questionId]}][is_correct]"> Correct
                </label>
                <button type="button" class="btn btn-link" onclick="removeElement('answer-${questionId}-${answerCounts[questionId]}')">Remove</button>
            </div>
        `;
            $(`#answers-container-${questionId}`).append(answerHTML);
        }

        function removeElement(elementId) {
            $(`#${elementId}`).remove();
        }
    </script>

</body>

</html>