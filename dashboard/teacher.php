<?php
session_start();
if ($_SESSION['user']['role'] !== 'teacher') {
    header("Location: ../login.php");
    exit();
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right,rgb(113, 179, 8),rgb(241, 202, 173));
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: rgba(255, 255, 255, 0.2);
            padding: 2rem;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 500px;
        }

        h1 {
            margin-bottom: 1.5rem;
            font-size: 2rem;
        }

        a {
            display: block;
            margin: 1rem 0;
            padding: 0.8rem 1.5rem;
            background: #4e54c8;
            color: #fff;
            text-decoration: none;
            font-size: 1rem;
            border-radius: 5px;
            transition: all 0.3s;
        }

        a:hover {
            background: #3a41b2;
            transform: scale(1.05);
        }

        a:active {
            transform: scale(0.98);
        }

        .logout {
            background: #ff4d4d;
        }

        .logout:hover {
            background: #e04343;
        }

        @media (max-width: 600px) {
            h1 {
                font-size: 1.5rem;
            }

            a {
                font-size: 0.9rem;
                padding: 0.7rem 1.2rem;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Welcome, Teacher</h1>
    <a href="../exams/create.php">Create Exam</a>
    <a href="../results/evaluate.php">Evaluate Answers</a>
    <a href="../profiles/view.php">View Profile</a>
    <a href="submissions.php">View Submissions</a>
    <a href="?logout=1" class="logout">Logout</a>
</div>
</body>
</html>
