<?php
session_start();
require 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $message = "Passwords do not match!";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $message = "Email already registered!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $hashed_password]);
            $message = "Registration successful! You can now <a href='login.php'>login</a>.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Makeup Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            height: 100vh;
            background: linear-gradient(135deg, #ffb3d9, #ffe6f0);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .register-box {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 50px 40px;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            width: 400px;
            text-align: center;
            transition: transform 0.3s ease;
        }
        .register-box:hover {
            transform: translateY(-5px);
        }
        h2 {
            color: #ff3399;
            margin-bottom: 25px;
            font-size: 32px;
        }
        p.welcome {
            color: #666;
            font-size: 16px;
            margin-bottom: 30px;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 14px;
            margin: 12px 0;
            border-radius: 10px;
            border: 1px solid #ddd;
            font-size: 16px;
            transition: 0.3s;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #ff3399;
            outline: none;
            box-shadow: 0 0 8px rgba(255, 51, 153, 0.3);
        }
        .btn {
            background-color: #ff3399;
            color: white;
            border: none;
            padding: 15px 25px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            width: 100%;
            font-size: 16px;
            margin-top: 15px;
            transition: 0.3s;
        }
        .btn:hover {
            background-color: #e60073;
        }
        .message {
            margin-bottom: 15px;
            color: red;
            font-weight: 500;
        }
        .login-link {
            display: block;
            margin-top: 20px;
            color: #ff3399;
            text-decoration: none;
            font-weight: 500;
        }
        .login-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-box">
        <h2>Welcome babes!</h2>
        <p class="welcome">Create your account to explore our makeup store and track your purchases.</p>
        <?php if ($message): ?>
            <div class="message"><?= $message ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit" class="btn">Register</button>
        </form>
        <a href="login.php" class="login-link">Already have an account? Log In</a>
    </div>
</body>
</html>
<?php