<?php
session_start();
include '../config/db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

$user = $_SESSION['user'];

// Fetch user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user['id']]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 3rem;
            width: 90%;
            max-width: 600px;
            text-align: center;
        }

        h2 {
            color: #6a11cb;
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
        }

        p {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            color: #333;
        }

        p strong {
            color: #2575fc;
        }

        a {
            display: inline-block;
            background-color: #2575fc;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1.1rem;
            text-decoration: none;
            margin-top: 2rem;
            transition: background-color 0.3s;
        }

        a:hover {
            background-color: #6a11cb;
        }

        @media (max-width: 768px) {
            .container {
                padding: 2rem;
                width: 95%;
            }

            h2 {
                font-size: 2rem;
            }

            p {
                font-size: 1rem;
            }

            a {
                font-size: 1rem;
                padding: 8px 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">

        <h2>Your Profile</h2>
        <p><strong>Unique ID:</strong> <?= htmlspecialchars($userData['unique_id']) ?></p>
        <p><strong>Name:</strong> <?= htmlspecialchars($userData['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($userData['email']) ?></p>
        <p><strong>Role:</strong> <?= ucfirst(htmlspecialchars($userData['role'])) ?></p>
        <a href="edit.php">Edit Profile</a>


    </div>
</body>
</html>
