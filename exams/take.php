<?php
session_start();
include '../config/db.php';

if ($_SESSION['user']['role'] !== 'student') {
    header("Location: ../login.php");
    exit();
}

$student_id = $_SESSION['user']['id'];

// Check if the student has already attempted the exam
$stmt = $pdo->prepare("SELECT COUNT(*) FROM submissions WHERE student_id = ?");
$stmt->execute([$student_id]);
$attempts = $stmt->fetchColumn();

if ($attempts > 0) {
    echo "<p>You have already attempted this exam. You cannot attempt it again.</p>";
    exit();
}

// Fetch questions for the exam
$stmt = $pdo->query("SELECT * FROM questions");
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $question_id => $answer) {
        $stmt = $pdo->prepare("INSERT INTO submissions (student_id, question_id, answer) VALUES (?, ?, ?)");
        $stmt->execute([$student_id, $question_id, $answer]);
    }
    echo "<p>Exam submitted successfully!</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Exam</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            background-color:rgb(168, 168, 247);
            color: #333;
            line-height: 1.6;
        }

        .container {
            width: 90%;
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #0056b3;
        }

        #timer {
            text-align: center;
            font-size: 1.2rem;
            color:rgb(237, 226, 227);
            font-weight: bold;
            margin-bottom: 20px;
            background:rgb(71, 86, 5);
            padding: 10px;
            border-radius: 5px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        form > div {
            margin-bottom: 15px;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        strong {
            font-size: 1rem;
            color: #333;
        }

        label {
            display: block;
            margin: 5px 0;
            cursor: pointer;
        }

        input[type="radio"],
        textarea {
            margin-right: 5px;
        }

        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: none;
        }

        button {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #218838;
        }

        @media (max-width: 600px) {
            body {
                font-size: 0.9rem;
            }

            button {
                font-size: 0.9rem;
                padding: 8px 15px;
            }
        }
    </style>
    <script>
        function startTimer(duration, display) {
            let timer = duration, minutes, seconds;
            const interval = setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = "Time Remaining: " + minutes + ":" + seconds;

                if (--timer < 0) {
                    clearInterval(interval);
                    document.getElementById('exam-form').submit();
                }
            }, 1000);
        }

        window.onload = function () {
            const display = document.getElementById('timer');
            startTimer(300, display); // Timer for 10 minutes
        };
    </script>
</head>
<body>
    <div class="container">
        <h2>Online Examination</h2>
        <p id="timer"></p>
        <form method="post" id="exam-form">
            <?php foreach ($questions as $question): ?>
                <div>
                    <p><strong><?= htmlspecialchars($question['question_text']) ?></strong></p>
                    <?php if ($question['question_type'] === 'mcq'): ?>
                        <?php $options = json_decode($question['options'], true); ?>
                        <?php foreach ($options as $option): ?>
                            <label>
                                <input type="radio" name="<?= htmlspecialchars($question['id']) ?>" value="<?= htmlspecialchars($option) ?>" required>
                                <?= htmlspecialchars($option) ?>
                            </label>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <textarea name="<?= htmlspecialchars($question['id']) ?>" rows="4" required></textarea>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            <button type="submit">Submit Exam</button>
        </form>
    </div>
</body>
</html>