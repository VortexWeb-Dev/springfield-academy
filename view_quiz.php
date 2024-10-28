<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once './config/database.php';
$conn = getDatabaseConnection();

$quiz_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

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

    // Fetch related questions and answers
    $question_query = "SELECT q.question_id, q.question_text, a.answer_id, a.answer_text, a.is_correct
                       FROM questions q
                       LEFT JOIN answers a ON q.question_id = a.question_id
                       WHERE q.quiz_id = ?";
    $question_stmt = $conn->prepare($question_query);
    $question_stmt->bind_param('i', $quiz_id);
    $question_stmt->execute();
    $question_result = $question_stmt->get_result();

    // Organize questions and their answers
    $questions = [];
    while ($row = $question_result->fetch_assoc()) {
        $questions[$row['question_id']]['question_text'] = $row['question_text'];
        $questions[$row['question_id']]['answers'][] = [
            'answer_id' => $row['answer_id'],
            'answer_text' => $row['answer_text'],
            'is_correct' => $row['is_correct']
        ];
    }

    $question_stmt->close();
} else {
    echo '<p>Quiz not found.</p>';
    $quiz = null;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Academy | View Quiz</title>
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

        .correct-answer {
            color: green;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php include './inc/sidebar.php'; ?>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4 main-content">
                <?php include './inc/navbar.php'; ?>

                <div class="container mt-4">
                    <h1>View Quiz</h1>
                    <hr>

                    <div class="row align-items-center mb-5">
                        <form class="col-12">
                            <div class="form-group">
                                <label for="title">Quiz Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($quiz['title']); ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" disabled><?php echo htmlspecialchars($quiz['description']); ?></textarea>
                            </div>

                            <h2>Questions</h2>
                            <?php if (!empty($questions)): ?>
                                <?php foreach ($questions as $question): ?>
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo htmlspecialchars($question['question_text']); ?></h5>
                                            <ul class="list-group list-group-flush">
                                                <?php foreach ($question['answers'] as $answer): ?>
                                                    <li class="list-group-item">
                                                        <?php echo htmlspecialchars($answer['answer_text']); ?>
                                                        <?php if ($answer['is_correct']): ?>
                                                            <span class="correct-answer">(Correct Answer)</span>
                                                        <?php endif; ?>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No questions found for this quiz.</p>
                            <?php endif; ?>

                            <div class="d-flex">
                                <a href="manage_quizzes.php" class="btn btn-secondary mr-2">Back</a>
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