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
    header('Location: wishlist.php');
    exit();
}

$item_id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM wishlist WHERE id = ? AND user_id = ?");
$stmt->execute([$item_id, $user_id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    header('Location: wishlist.php');
    exit();
}

$categories = ['Makeup', 'Skincare', 'Hair Care', 'Body Care', 'Nails'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'] ?? '';
    $category = $_POST['category'] ?? '';
    $estimated_price = $_POST['estimated_price'] ?? '';
    $image_url = $_POST['image_url'] ?? '';
    $notes = $_POST['notes'] ?? '';

    if ($product_name && $category && $estimated_price) {
        $stmt = $pdo->prepare("UPDATE wishlist SET product_name = ?, category = ?, estimated_price = ?, image_url = ?, notes = ? WHERE id = ?");
        $stmt->execute([$product_name, $category, $estimated_price, $image_url, $notes, $item_id]);
        $edit_message = "Wishlist item updated successfully!";
        $stmt = $pdo->prepare("SELECT * FROM wishlist WHERE id = ? AND user_id = ?");
        $stmt->execute([$item_id, $user_id]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $edit_message = "Please fill in all required fields!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Wishlist Item</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #ffb3d9, #ffe6f0);
            margin: 0;
            padding: 20px;
        }
        .form-box {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 500px;
        }
        h2 {
            color: #ff3399;
            margin-bottom: 20px;
        }
        input, select, textarea {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
        }
        input:focus, select:focus, textarea:focus {
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
            font-size: 16px;
        }
        button:hover {
            background-color: #e60073;
        }
        .message {
            color: green;
            margin-bottom: 15px;
            font-weight: 500;
            background: #e8f5e9;
            padding: 10px;
            border-radius: 5px;
        }
        a {
            display: block;
            margin-top: 15px;
            text-align: center;
            color: #ff3399;
            text-decoration: none;
            font-weight: 600;
        }
        a:hover {
            text-decoration: underline;
        }
        .image-preview {
            margin-top: 10px;
            text-align: center;
        }
        .image-preview img {
            max-width: 100%;
            max-height: 200px;
            border-radius: 5px;
            display: block;
        }
        .image-preview .placeholder {
            max-width: 100%;
            max-height: 200px;
            background: linear-gradient(135deg, #f5f5f5, #e0e0e0);
            border-radius: 5px;
            display: none;
            align-items: center;
            justify-content: center;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="form-box">
        <h2>Edit Wishlist Item</h2>
        <?php if ($edit_message): ?>
            <div class="message"><?= htmlspecialchars($edit_message) ?></div>
        <?php endif; ?>
        
        <form method="post" action="">
            <input type="text" name="product_name" value="<?= htmlspecialchars($item['product_name']) ?>" placeholder="Product Name" required>
            <select name="category" required>
                <option value="">Select Category</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat ?>" <?= $item['category'] === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="estimated_price" value="<?= htmlspecialchars($item['estimated_price']) ?>" placeholder="Estimated Price" step="0.01" required>
            <input type="url" name="image_url" value="<?= htmlspecialchars($item['image_url']) ?>" placeholder="Image URL">
            <textarea name="notes" placeholder="Notes or description (optional)" rows="3"><?= htmlspecialchars($item['notes']) ?></textarea>
            <?php if ($item['image_url']): ?>
                <div class="image-preview">
                    <p>Current Image:</p>
                    <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="Preview" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="placeholder">Image unavailable</div>
                </div>
            <?php endif; ?>
            <button type="submit">Update Item</button>
        </form>
        <a href="wishlist.php">Back to Wishlist</a>
    </div>
</body>
</html>
