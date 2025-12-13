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
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
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
        @media (max-width: 768px) {
            h1 { font-size: 40px; }
            p { font-size: 16px; }
            .btn { padding: 12px 30px; }
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
</body>
</html>
<?php