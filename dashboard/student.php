<?php
session_start();

// Regenerate session ID to prevent session hijacking
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
} else {
    session_regenerate_id(true);
}

if ($_SESSION['user']['role'] !== 'student') {
    header("Location: ../login.php");
    exit();
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../login.php");
    exit();
}

$student_name = $_SESSION['user']['name']; // Retrieve the student's name from session
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right,rgb(150, 64, 242),rgb(244, 168, 54));
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
            background:rgb(235, 36, 56);
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

        .activity-log {
            margin-top: 2rem;
            background: rgba(255, 255, 255, 0.3);
            padding: 1rem;
            border-radius: 10px;
            text-align: left;
        }

        .activity-log ul {
            list-style-type: none;
            padding: 0;
        }

        .activity-log li {
            margin: 0.5rem 0;
            color: #fff;
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
    <h1>Welcome, <?php echo htmlspecialchars($student_name); ?></h1> <!-- Display the student's name -->
    <a href="../exams/take.php">Attend Exam</a>
    <a href="../profiles/view.php">View Profile</a>
    <a href="../results/view.php">View My Results</a>
    <a href="?logout=1" class="logout">Logout</a>

</div>
</body>
</html>
