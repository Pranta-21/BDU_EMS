<?php
session_start();
include 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $rememberMe = isset($_POST['remember_me']) ? true : false; // Handle Remember Me

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;

        // If Remember Me is checked, set a cookie with a long expiry
        if ($rememberMe) {
            setcookie('user_id', $user['id'], time() + 3600 * 24 * 30, '/'); // 30 days
            setcookie('user_email', $user['email'], time() + 3600 * 24 * 30, '/'); // 30 days
        }

        header("Location: dashboard/" . $user['role'] . ".php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #6a11cb, rgb(117, 119, 2));
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
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
            margin-bottom: 1rem;
            font-size: 2rem;
            color: #fff;
        }

        .container form input[type="email"],
        .container form input[type="password"],
        .container form button {
            width: 100%;
            padding: 0.8rem;
            margin: 0.5rem 0;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        }

        .container form button {
            background-color: #00c9a7;
            color: #fff;
            cursor: pointer;
            transition: background 0.3s;
        }

        .container form button:hover {
            background-color: #3be8b0;
        }

        .container p {
            margin-top: 1rem;
            font-size: 0.9rem;
        }

        .container p a {
            color: #00c9a7;
            text-decoration: none;
        }

        .container p a:hover {
            text-decoration: underline;
        }

        .container .remember-me {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin: 0.5rem 0;
        }

        .container .remember-me input {
            margin-right: 0.5rem;
        }

        .error-message {
            background: rgba(255, 0, 0, 0.1);
            color: red;
            padding: 0.5rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        .show-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #fff;
        }

        .loading-spinner {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }

        @media (max-width: 600px) {
            .container h2 {
                font-size: 1.5rem;
            }

            .container form input[type="email"],
            .container form input[type="password"] {
                font-size: 0.9rem;
            }

            .container form button {
                padding: 0.7rem;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Login</h2>
    <?php if (isset($error)) echo "<div class='error-message'>$error</div>"; ?>
    <form method="post" id="loginForm">
        <input type="email" name="email" placeholder="Email" required>
        <div class="password-container">
            <input type="password" name="password" id="password" placeholder="Password" required>
            <span class="show-password" onclick="togglePassword()"></span>
        </div>
        <div class="remember-me">
            <input type="checkbox" name="remember_me" id="remember_me">
            <label for="remember_me">Remember Me</label>
        </div>
        <button type="submit" onclick="showLoading()">Login</button>
    </form>
    <div class="loading-spinner" id="loadingSpinner"></div>
    <p>Don't have an account? <a href="register.php">Register Here</a></p>
</div>

<script>
    // Toggle password visibility
    function togglePassword() {
        var passwordField = document.getElementById('password');
        var passwordIcon = document.querySelector('.show-password');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            passwordIcon.textContent = '';
        } else {
            passwordField.type = 'password';
            passwordIcon.textContent = '';
        }
    }

    // Show loading spinner during form submission
    function showLoading() {
        document.getElementById('loadingSpinner').style.display = 'block';
    }

    // Form validation
    document.getElementById('loginForm').addEventListener('submit', function (event) {
        var email = document.querySelector('input[name="email"]').value;
        var password = document.querySelector('input[name="password"]').value;
        if (!email || !password) {
            alert("Both email and password are required!");
            event.preventDefault();
        }
    });
</script>
</body>
</html>
