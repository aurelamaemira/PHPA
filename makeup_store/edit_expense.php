<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$edit_message = '';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: dashboard.php');
    exit();
}

$expense_id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM purchases WHERE id = ? AND user_id = ?");
$stmt->execute([$expense_id, $user_id]);
$expense = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$expense) {
    header('Location: dashboard.php');
    exit();
}

$categories = ['Makeup', 'Skincare', 'Hair Care', 'Body Care', 'Nails'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_name = $_POST['item_name'] ?? '';
    $category = $_POST['category'] ?? '';
    $price = $_POST['price'] ?? '';
    $purchase_date = $_POST['purchase_date'] ?? '';

    if ($item_name && $category && $price && $purchase_date) {
        $stmt = $pdo->prepare("UPDATE purchases SET item_name = ?, category = ?, price = ?, purchase_date = ? WHERE id = ?");
        $stmt->execute([$item_name, $category, $price, $purchase_date, $expense_id]);
        $edit_message = "Expense updated successfully!";
        $stmt = $pdo->prepare("SELECT * FROM purchases WHERE id = ? AND user_id = ?");
        $stmt->execute([$expense_id, $user_id]);
        $expense = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $edit_message = "Please fill in all fields!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Expense</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #ffb3d9, #ffe6f0);
            margin: 0;
        }
        .form-box {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            width: 400px;
        }
        h2 {
            color: #ff3399;
            margin-bottom: 20px;
        }
        input, select {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #ff3399;
            box-shadow: 0 0 8px rgba(255, 51, 153, 0.3);
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #ff3399;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            margin-top: 10px;
        }
        button:hover {
            background-color: #e60073;
        }
        .message {
            color: green;
            margin-bottom: 15px;
            font-weight: 500;
        }
        a {
            display: block;
            margin-top: 15px;
            text-align: center;
            color: #ff3399;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>Edit Expense</h2>
        <?php if ($edit_message): ?>
            <div class="message"><?= $edit_message ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <input type="text" name="item_name" value="<?= htmlspecialchars($expense['item_name']) ?>" placeholder="Item Name" required>
            <select name="category" required>
                <option value="">Select Category</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat ?>" <?= $expense['category'] === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="price" value="<?= htmlspecialchars($expense['price']) ?>" placeholder="Price" step="0.01" required>
            <input type="date" name="purchase_date" value="<?= htmlspecialchars($expense['purchase_date']) ?>" required>
            <button type="submit">Update Expense</button>
        </form>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
