<?php
include 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $unique_id = $_POST['unique_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validate email format using regex
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } else {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            $error = "Email is already registered.";
        } else {
            // Validate password strength
            if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/", $password)) {
                $error = "Password must be at least 8 characters long, contain at least one letter and one number.";
            } else {
                // Hash the password
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                // Insert the new user into the database
                $stmt = $pdo->prepare("INSERT INTO users (unique_id, name, email, password, role) VALUES (?, ?, ?, ?, ?)");
                try {
                    $stmt->execute([$unique_id, $name, $email, $hashedPassword, $role]);
                    header("Location: login.php?success=1");
                    exit();
                } catch (Exception $e) {
                    $error = "Error: Could not register. Please try again.";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(to right,rgb(3, 184, 220),rgb(246, 55, 87));
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
        }

        .container {
            background: rgba(255, 255, 255, 0.2);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }

        .container h2 {
            margin-bottom: 1.5rem;
            font-size: 2rem;
            color: #fff;
        }

        .container form input[type="text"],
        .container form input[type="email"],
        .container form input[type="password"],
        .container form select {
            width: 100%;
            padding: 0.8rem;
            margin: 0.5rem 0;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }

        .container form button {
            background-color:rgb(130, 2, 39);
            color: #fff;
            padding: 0.8rem;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
            transition: background 0.3s;
        }

        .container form button:hover {
            background-color:rgb(244, 143, 170);
        }

        .container p {
            margin-top: 1rem;
            font-size: 0.9rem;
        }

        .container p a {
            color:rgb(10, 212, 227);
            text-decoration: none;
        }

        .container p a:hover {
            text-decoration: underline;
        }

        .error-message {
            background: rgba(255, 0, 0, 0.1);
            color: red;
            padding: 0.5rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Register</h2>
    <?php if (isset($error)) echo "<div class='error-message'>$error</div>"; ?>
    <form method="post" id="registerForm">
        <input type="text" name="unique_id" placeholder="ID" required>
        <input type="text" name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <select name="role" required>
            <option value="" disabled selected>Select Role</option>
            <option value="student">Student</option>
            <option value="teacher">Teacher</option>
        </select>
        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login Here</a></p>
</div>

<script>
    // Client-side validation for email and password
    document.getElementById('registerForm').addEventListener('submit', function (event) {
        var email = document.querySelector('input[name="email"]').value;
        var password = document.querySelector('input[name="password"]').value;

        var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        var passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;

        if (!emailRegex.test(email)) {
            alert("Please enter a valid email address!");
            event.preventDefault();
        } else if (!passwordRegex.test(password)) {
            alert("Password must be at least 8 characters long, contain at least one letter and one number.");
            event.preventDefault();
        }
    });
</script>
</body>
</html>
