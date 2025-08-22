<?php
session_start();
include '../config/db.php';

if ($_SESSION['user']['role'] !== 'teacher') {
    header("Location: ../login.php");
    exit();
}

// Fetch all submissions
$stmt = $pdo->query("
    SELECT users.id AS student_id, users.name AS student_name, 
           questions.question_text, submissions.answer, submissions.marks, submissions.is_evaluated
    FROM submissions 
    JOIN users ON submissions.student_id = users.id 
    JOIN questions ON submissions.question_id = questions.id 
    ORDER BY users.name, submissions.id
");

$submissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group submissions by student
$grouped_submissions = [];
foreach ($submissions as $submission) {
    $grouped_submissions[$submission['student_name']][] = $submission;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Submissions</title>
    <style>
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', Arial, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #1f4037, #99f2c8);
            color: #333;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #fff;
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        /* Student Section Card */
        .student-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            overflow: hidden;
            transition: transform 0.3s ease-in-out;
        }

        .student-card:hover {
            transform: translateY(-10px);
        }

        .student-header {
            background: linear-gradient(90deg, #4facfe, #00f2fe);
            color: #fff;
            padding: 15px;
            font-size: 1.2rem;
            text-align: center;
            font-weight: bold;
        }

        .student-body {
            padding: 10px 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        table th, table td {
            text-align: left;
            padding: 10px;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #f4f4f4;
            color: #333;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            font-size: 0.9rem;
            border-radius: 5px;
            font-weight: bold;
        }

        .status.pending {
            background-color: #ffcc00;
            color: #fff;
        }

        .status.evaluated {
            background-color: #28a745;
            color: #fff;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #fff;
            font-size: 1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .student-card {
                width: 100%;
            }

            h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <h1>Student Submissions</h1>
    <div class="container">
        <?php if (!empty($grouped_submissions)): ?>
            <?php foreach ($grouped_submissions as $student_name => $student_submissions): ?>
                <div class="student-card">
                    <div class="student-header">
                        <?= htmlspecialchars($student_name) ?>
                    </div>
                    <div class="student-body">
                        <table>
                            <thead>
                                <tr>
                                    <th>Question</th>
                                    <th>Answer</th>
                                    <th>Marks</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($student_submissions as $submission): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($submission['question_text']) ?></td>
                                        <td><?= htmlspecialchars($submission['answer']) ?></td>
                                        <td><?= $submission['marks'] ?? 'Not Graded' ?></td>
                                        <td>
                                            <span class="status <?= $submission['is_evaluated'] ? 'evaluated' : 'pending' ?>">
                                                <?= $submission['is_evaluated'] ? 'Evaluated' : 'Pending' ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color: white; text-align: center;">No submissions found.</p>
        <?php endif; ?>
    </div>
    <div class="footer">
        <p>&copy; <?= date("Y") ?> BDU Examination Management System</p>
    </div>
</body>
</html>
