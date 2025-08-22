<?php
session_start();
include '../config/db.php';

if ($_SESSION['user']['role'] !== 'teacher') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $submission_id => $marks) {
        $stmt = $pdo->prepare("UPDATE submissions SET is_evaluated = 1, marks = ? WHERE id = ?");
        $stmt->execute([$marks, $submission_id]);
    }
    echo "<p class='alert alert-success'>Marks submitted successfully!</p>";
}

$stmt = $pdo->query("SELECT submissions.id AS submission_id, users.name AS student_name, questions.question_text, submissions.answer 
    FROM submissions 
    JOIN users ON submissions.student_id = users.id 
    JOIN questions ON submissions.question_id = questions.id 
    WHERE is_evaluated = 0");
$submissions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evaluate Answers</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #ff7e5f, #feb47b);
            color: #444;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1100px;
            margin: 50px auto;
            padding: 30px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        h2 {
            text-align: center;
            font-size: 2.5rem;
            color: #ff7e5f;
            margin-bottom: 30px;
            text-transform: uppercase;
        }
        .submission-box {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease-in-out;
        }
        .submission-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        .submission-box p {
            margin: 10px 0;
            font-size: 1.1rem;
            line-height: 1.6;
        }
        .submission-box strong {
            color: #ff7e5f;
            font-weight: bold;
        }
        input[type="number"] {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            font-size: 1.1rem;
            border: 2px solid #ddd;
            border-radius: 8px;
            transition: border-color 0.3s ease;
        }
        input[type="number"]:focus {
            border-color: #feb47b;
            outline: none;
        }
        button {
            background-color: #ff7e5f;
            color: white;
            padding: 15px 25px;
            border-radius: 50px;
            border: none;
            width: 100%;
            font-size: 1.2rem;
            cursor: pointer;
            margin-top: 30px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        button:hover {
            background-color: #feb47b;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }
        .alert {
            text-align: center;
            font-size: 1.1rem;
            padding: 15px;
            margin-top: 30px;
            border-radius: 8px;
        }
        .alert-success {
            background-color: #28a745;
            color: white;
        }
        .alert-danger {
            background-color: #dc3545;
            color: white;
        }
        .alert-warning {
            background-color: #ffc107;
            color: black;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Evaluate Answers</h2>
    <form method="post">
        <?php foreach ($submissions as $submission): ?>
            <div class="submission-box">
                <p><strong>Student:</strong> <?= $submission['student_name'] ?></p>
                <p><strong>Question:</strong> <?= $submission['question_text'] ?></p>
                <p><strong>Answer:</strong> <?= nl2br(htmlspecialchars($submission['answer'])) ?></p>
                <input type="number" name="<?= $submission['submission_id'] ?>" placeholder="Enter Marks" required>
            </div>
        <?php endforeach; ?>
        <button type="submit">Submit Marks</button>
    </form>
    <a href="/dashboard/teacher.php" class="button">Back to Dashboard</a>
</div>
</body>
</html>
