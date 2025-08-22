<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BDU Examination Management System</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #ff7f50, #ff6347, #1cc88a);
            margin: 0;
            padding: 0;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            animation: fadeIn 1.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .container {
            width: 100%;
            max-width: 1000px;
            padding: 30px;
            text-align: center;
            background: rgba(0, 0, 0, 0.7);
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            animation: fadeInUp 1s ease-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header {
            margin-bottom: 30px;
            text-align: center;
        }

        .header img {
            width: 100px;
            height: auto;
            margin-bottom: 20px;
            transition: transform 0.3s;
        }

        .header img:hover {
            transform: scale(1.1);
        }

        .header h1 {
            font-size: 3rem;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: color 0.3s ease-in-out;
        }

        .header h1:hover {
            color: #ffeb3b; /* Yellow hover effect on title */
        }

        .notice {
            background-color: #ffeb3b;
            color: #333;
            padding: 20px 30px;
            border-radius: 8px;
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 40px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .notice p {
            margin: 0;
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-top: 30px;
        }

        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 15px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .btn:hover {
            background-color: #45a049;
            transform: scale(1.05);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .btn-register {
            background-color: #f44336;
        }

        .btn-register:hover {
            background-color: #e53935;
        }

        .footer {
            text-align: center;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 15px 0;
            position: absolute;
            bottom: 0;
            color: white;
            font-size: 1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header h1 {
                font-size: 2.5rem;
            }

            .notice {
                font-size: 1.1rem;
                padding: 15px 20px;
            }

            .btn {
                padding: 12px 30px;
                font-size: 1rem;
            }

            .footer {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Header Section -->
    <div class="header">
        <!-- Logo -->
        <img src="/image/bdu1.png" alt="BDU Logo"> <!-- Replace with the path to your logo -->
        <h1>BDU Examination Management System</h1>
    </div>

    <!-- Notice Section -->
    <div class="notice">
        <p>Welcome to the online system for managing BDU examinations. <br>
           Please login or register to get started.</p>
    </div>

    <!-- Button Section -->
    <div class="button-container">
        <a href="login.php" class="btn">Login</a>
        <a href="register.php" class="btn btn-register">Register</a>
    </div>
</div>

<!-- Footer Section -->
<div class="footer">
    <p>&copy; 2024 BDU Examination Management System | All Rights Reserved</p>
</div>

</body>
</html>
