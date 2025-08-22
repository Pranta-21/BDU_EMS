<?php
session_start();
include '../config/db.php';

if ($_SESSION['user']['role'] !== 'teacher') {
    header("Location: ../login.php");
    exit();
}

$teacher_id = $_SESSION['user']['id'];

// Handle deletion of questions
if (isset($_POST['delete_question'])) {
    $question_id = $_POST['delete_question'];
    $stmt = $pdo->prepare("DELETE FROM questions WHERE id = ? AND teacher_id = ?");
    $stmt->execute([$question_id, $teacher_id]);
    $success_message = "Question deleted successfully!";
}

// Handle adding new questions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['delete_question'])) {
    if (isset($_POST['questions']) && is_array($_POST['questions'])) {
        foreach ($_POST['questions'] as $question) {
            $question_text = $question['question_text'];
            $question_type = $question['question_type'];
            $options = isset($question['options']) ? json_encode($question['options']) : null;
            $correct_answer = isset($question['correct_answer']) ? $question['correct_answer'] : null;

            $stmt = $pdo->prepare("INSERT INTO questions (teacher_id, question_text, question_type, options, correct_answer) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$teacher_id, $question_text, $question_type, $options, $correct_answer]);
        }
        $success_message = "Questions added successfully!";
    } else {
        $error_message = "Please add at least one question.";
    }
}

// Fetch existing questions
$stmt = $pdo->prepare("SELECT * FROM questions WHERE teacher_id = ?");
$stmt->execute([$teacher_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Questions</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background: linear-gradient(135deg, #6dd5ed, #2193b0);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            background: #fff;
            border-radius: 15px;
            padding: 2rem;
            width: 90%;
            max-width: 900px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            margin: 20px 0;
        }
        h2 {
            text-align: center;
            color: #2193b0;
            margin-bottom: 1.5rem;
            font-size: 2rem;
            font-weight: bold;
        }
        .success-message, .error-message {
            text-align: center;
            font-weight: bold;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .success-message {
            color: #155724;
            background-color: #d4edda;
        }
        .error-message {
            color: #721c24;
            background-color: #f8d7da;
        }
        .question-list {
            margin: 20px 0;
        }
        .question-item {
            border: none;
            background: #f4f4f9;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .question-item h4 {
            color: #333;
            font-size: 1.1rem;
        }
        .question-item form {
            margin: 0;
        }
        button {
            background: linear-gradient(135deg, #43cea2, #185a9d);
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: transform 0.2s, box-shadow 0.3s;
        }
        button:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }
        .delete-btn {
            background: linear-gradient(135deg, #ff416c, #ff4b2b);
        }
        .delete-btn:hover {
            transform: scale(1.05);
        }
        form#question-form {
            margin-top: 20px;
        }
        input[type="text"] {
            width: calc(100% - 20px);
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        .add-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }

        /* Modal Style */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Questions</h2>
        <?php if (isset($success_message)): ?>
            <p class="success-message"><?= htmlspecialchars($success_message) ?></p>
        <?php endif; ?>
        <?php if (isset($error_message)): ?>
            <p class="error-message"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>

        <!-- Display Existing Questions -->
        <div class="question-list">
            <h3>Existing Questions</h3>
            <?php foreach ($questions as $question): ?>
                <div class="question-item">
                    <div>
                        <h4><?= htmlspecialchars(ucfirst($question['question_type'])) ?>: <?= htmlspecialchars($question['question_text']) ?></h4>
                        <?php if ($question['question_type'] === 'mcq'): ?>
                            <p><strong>Options:</strong> <?= implode(', ', json_decode($question['options'], true)) ?></p>
                            <p><strong>Correct Answer:</strong> <?= htmlspecialchars($question['correct_answer']) ?></p>
                        <?php endif; ?>
                    </div>
                    <form method="POST">
                        <input type="hidden" name="delete_question" value="<?= $question['id'] ?>">
                        <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this question?')">Delete</button>
                    </form>
                </div>
            <?php endforeach; ?>
            <?php if (empty($questions)): ?>
                <p>No questions added yet.</p>
            <?php endif; ?>
        </div>

        <!-- Form to Add New Questions -->
        <form method="POST" id="question-form">
            <div id="questions-container"></div>
            <div class="add-buttons">
                <button type="button" id="add-mcq">Add MCQ Question</button>
                <button type="button" id="add-written">Add Written Question</button>
                <button type="submit">Submit Questions</button>
            </div>
        </form>
    </div>

    <!-- Preview Modal -->
    <div id="previewModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Question Preview</h2>
            <p id="preview-content"></p>
        </div>
    </div>

    <script>
        let questionCount = 0;
        const container = document.getElementById('questions-container');

        document.getElementById('add-mcq').addEventListener('click', () => {
            const mcqHTML = `
                <div class='question-item'>
                    <h4>MCQ Question</h4>
                    <input type="text" name="questions[${questionCount}][question_text]" placeholder="Question text" required>
                    <input type="text" name="questions[${questionCount}][options][]" placeholder="Option 1">
                    <input type="text" name="questions[${questionCount}][options][]" placeholder="Option 2">
                    <input type="text" name="questions[${questionCount}][options][]" placeholder="Option 3">
                    <input type="text" name="questions[${questionCount}][options][]" placeholder="Option 4">
                    <input type="text" name="questions[${questionCount}][correct_answer]" placeholder="Correct Answer" required>
                    <input type="hidden" name="questions[${questionCount}][question_type]" value="mcq">
                </div>`;
            container.insertAdjacentHTML('beforeend', mcqHTML);
            questionCount++;
        });

        document.getElementById('add-written').addEventListener('click', () => {
            const writtenHTML = `
                <div class='question-item'>
                    <h4>Written Question</h4>
                    <input type="text" name="questions[${questionCount}][question_text]" placeholder="Question text" required>
                    <input type="hidden" name="questions[${questionCount}][question_type]" value="written">
                </div>`;
            container.insertAdjacentHTML('beforeend', writtenHTML);
            questionCount++;
        });

        // Preview modal logic
        const previewModal = document.getElementById('previewModal');
        const closeModal = document.getElementsByClassName("close")[0];
        
        function previewQuestion(content) {
            document.getElementById('preview-content').innerText = content;
            previewModal.style.display = "block";
        }

        closeModal.onclick = function() {
            previewModal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target === previewModal) {
                previewModal.style.display = "none";
            }
        }
    </script>
</body>
</html>
