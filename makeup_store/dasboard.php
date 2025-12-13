<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

$categories = ['Makeup', 'Skincare', 'Hair Care', 'Body Care', 'Nails'];

$totals = [];
foreach ($categories as $category) {
    $stmt = $pdo->prepare("SELECT SUM(price) as total FROM purchases WHERE user_id = ? AND category = ?");
    $stmt->execute([$user_id, $category]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $totals[$category] = $row['total'] ?? 0;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Makeup Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background: linear-gradient(135deg, #ffb3d9, #ffe6f0);
        }
        nav {
            background-color: #ff3399;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        nav h1 {
            margin: 0;
            font-size: 24px;
        }
        nav a {
            color: white;
            text-decoration: none;
            font-weight: 600;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }
        h2 {
            color: #ff3399;
        }
        .cards {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }
        .card {
            background-color: white;
            flex: 1 1 calc(33% - 20px);
            padding: 30px 20px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            text-align: center;
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card h3 {
            margin: 0 0 10px 0;
            color: #ff3399;
            font-size: 22px;
        }
        .card p {
            font-size: 20px;
            font-weight: 600;
            color: #333;
        }
        @media (max-width: 700px) {
            .card {
                flex: 1 1 100%;
            }
        }
    </style>
</head>
<body>
    <nav>
        <h1>Makeup Store Dashboard</h1>
        <a href="logout.php">Logout</a>
    </nav>

    <div class="container">
        <h2>Welcome, <?= htmlspecialchars($user_name) ?>!</h2>
        <p>Here’s a summary of your expenses by category:</p>

        <div class="cards">
            <?php foreach ($totals as $category => $total): ?>
                <div class="card">
                    <h3><?= $category ?></h3>
                    <p>$<?= number_format($total, 2) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
<?php