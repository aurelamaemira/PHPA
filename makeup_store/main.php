<!DOCTYPE html>
<html>
<head>
    <title>My Makeup Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body, html {
            height: 100%;
            font-family: 'Poppins', Arial, sans-serif;
        }
        body {
            position: relative;
            padding-bottom: 250px;
        }
        .bg-img {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }
        .overlay {
            position: relative;
            width: 100%;
            min-height: calc(100vh - 250px);
            background-color: rgba(0,0,0,0.4);
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            text-align: center;
        }
        h1 {
            color: #fff;
            font-size: 60px;
            margin-bottom: 20px;
        }
        p {
            color: #fff;
            font-size: 20px;
            margin-bottom: 40px;
        }
        footer p {
            font-size: 14px;
            margin-bottom: 8px;
        }
        .btn {
            display: inline-block;
            padding: 15px 40px;
            margin: 10px;
            background-color: #ff3399;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: 0.3s;
        }
        .btn:hover {
            background-color: #e60073;
        }
        footer {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.95);
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 14px;
            z-index: 1000;
        }
        footer a {
            color: #ff3399;
            text-decoration: none;
            margin: 0 10px;
        }
        footer a:hover {
            text-decoration: underline;
        }
        @media (max-width: 768px) {
            h1 { font-size: 40px; }
            p { font-size: 16px; }
            .btn { padding: 12px 30px; }
            footer { font-size: 12px; padding: 15px; }
        }
    </style>
</head>
<body>
    <img src="images/makeupp.jpg" class="bg-img" alt="Makeup Background">
    <div class="overlay">
        <h1>Welcome to Aurela's Beauty</h1>
        <p>Track your purchases and explore our products!</p>
        <a class="btn" href="login.php">Login</a>
        <a class="btn" href="register.php">Register</a>
    </div>
    <footer>
        <p>&copy; 2026 Aurela's Beauty. All Rights Reserved.</p>
        <p>
            Email: <a href="mailto:contact@aurelabeauty.com">contact@aurelabeauty.com</a> | 
            Phone: <a href="tel:+1234567890">+383 (234) 567-890</a>
        </p>
        <p>Follow us: 
            <a href="https://www.facebook.com/aurelabeauty">Facebook</a> | 
            <a href="https://www.instagram.com/aurelaa_n/">Instagram</a>
        </p>
    </footer>
</body>
</html>
<?php