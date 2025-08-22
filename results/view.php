<?php
session_start();
include '../config/db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: ../login.php");
    exit();
}

$student_id = $_SESSION['user']['id'];

// Fetch the results for the logged-in student
$stmt = $pdo->prepare("
    SELECT 
        q.question_text, 
        q.question_type, 
        s.answer, 
        s.marks, 
        s.is_evaluated 
    FROM submissions s
    JOIN questions q ON s.question_id = q.id
    WHERE s.student_id = ?
    ORDER BY s.submitted_at DESC
");
$stmt->execute([$student_id]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Results</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            width: 90%;
            max-width: 900px;
            text-align: center;
        }

        h2 {
            color: #fff;
            font-size: 2.5rem;
            margin-bottom: 2rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #6a11cb;
            color: white;
            font-size: 1.2rem;
        }

        td {
            background-color: #f4f4f4;
            font-size: 1rem;
        }

        tr:nth-child(even) td {
            background-color: #e9e9e9;
        }

        tr:hover td {
            background-color: #dcdcdc;
        }

        .status {
            font-weight: bold;
            text-transform: capitalize;
        }

        .status.evaluated {
            color: green;
        }

        .status.not-evaluated {
            color: red;
        }

        .button {
            background-color: #2575fc;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2rem;
            text-decoration: none;
            margin-top: 2rem;
            display: inline-block;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #6a11cb;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1.5rem;
            }

            h2 {
                font-size: 2rem;
            }

            table {
                font-size: 0.9rem;
            }

            .button {
                font-size: 1rem;
                padding: 8px 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>My Exam Results</h2>
        <?php if (count($results) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Question</th>
                        <th>Type</th>
                        <th>Your Answer</th>
                        <th>Marks</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $result): ?>
                        <tr>
                            <td><?= htmlspecialchars($result['question_text']) ?></td>
                            <td><?= ucfirst($result['question_type']) ?></td>
                            <td><?= htmlspecialchars($result['answer']) ?></td>
                            <td><?= $result['is_evaluated'] ? $result['marks'] : 'Pending' ?></td>
                            <td>
                                <span class="status <?= $result['is_evaluated'] ? 'evaluated' : 'not-evaluated' ?>">
                                    <?= $result['is_evaluated'] ? 'Evaluated' : 'Not Yet Evaluated' ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="color: white;">No results found.</p>
        <?php endif; ?>

        <a href="/dashboard/student.php" class="button">Back to Dashboard</a>
    </div>
</body>
</html>
