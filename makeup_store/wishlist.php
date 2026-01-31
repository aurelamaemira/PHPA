<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

$wishlist_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_wishlist'])) {
    $product_name = $_POST['product_name'] ?? '';
    $category = $_POST['category'] ?? '';
    $estimated_price = $_POST['estimated_price'] ?? '';
    $image_url = $_POST['image_url'] ?? '';
    $notes = $_POST['notes'] ?? '';

    if ($product_name && $category && $estimated_price) {
        $stmt = $pdo->prepare("INSERT INTO wishlist (user_id, product_name, category, estimated_price, image_url, notes) VALUES (?, ?, ?, ?, ?, ?)");
        try {
            $stmt->execute([$user_id, $product_name, $category, $estimated_price, $image_url, $notes]);
            $wishlist_message = "Item added to wishlist!";
        } catch (PDOException $e) {
            $wishlist_message = "Error adding to wishlist: " . $e->getMessage();
        }
    } else {
        $wishlist_message = "Please fill in required fields!";
    }
}

$categories = ['Makeup', 'Skincare', 'Hair Care', 'Body Care', 'Nails'];

try {
    $stmt = $pdo->prepare("SELECT id, product_name, category, estimated_price, image_url, notes FROM wishlist WHERE user_id = ? ORDER BY id DESC");
    $stmt->execute([$user_id]);
    $wishlist_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $wishlist_items = [];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Wishlist - Makeup Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; margin: 0; background: linear-gradient(135deg, #ffb3d9, #ffe6f0); }
        nav { background-color: #ff3399; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        nav h1 { margin: 0; font-size: 24px; }
        nav a { color: white; text-decoration: none; font-weight: 600; margin-left: 20px; }
        .container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        h2, h3 { color: #ff3399; }
        .add-wishlist-form { background: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .add-wishlist-form input, .add-wishlist-form select, .add-wishlist-form textarea { width: 100%; padding: 10px; margin: 8px 0; border-radius: 5px; border: 1px solid #ccc; box-sizing: border-box; font-size: 14px; }
        .add-wishlist-form button { background-color: #ff3399; color: white; border: none; padding: 12px 20px; border-radius: 5px; cursor: pointer; font-weight: 600; width: 100%; margin-top: 10px; }
        .add-wishlist-form button:hover { background-color: #e60073; }
        .message { color: green; margin-bottom: 15px; font-weight: 500; background: #e8f5e9; padding: 10px; border-radius: 5px; }
        .wishlist-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; margin-top: 30px; }
        .wishlist-card { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: transform 0.3s; }
        .wishlist-card:hover { transform: translateY(-5px); }
        .wishlist-card img { width: 100%; height: 200px; object-fit: cover; }
        .wishlist-card-content { padding: 15px; }
        .wishlist-card h4 { margin: 0 0 5px 0; color: #ff3399; }
        .wishlist-card p { margin: 5px 0; color: #666; font-size: 14px; }
        .wishlist-card .price { font-weight: 600; color: #333; font-size: 16px; }
        .wishlist-card .notes { font-size: 13px; color: #777; margin-top: 8px; font-style: italic; }
        .wishlist-actions { display: flex; gap: 5px; margin-top: 10px; }
        .action-btn { flex: 1; padding: 8px; border: none; border-radius: 5px; color: white; cursor: pointer; text-decoration: none; font-size: 13px; font-weight: 600; }
        .edit-btn { background-color: #4CAF50; } .delete-btn { background-color: #f44336; }
        .edit-btn:hover { background-color: #45a049; } .delete-btn:hover { background-color: #da190b; }
        .empty-message { text-align: center; color: #999; padding: 40px 0; }
        .image-placeholder { width: 100%; height: 200px; background: linear-gradient(135deg, #f5f5f5, #e0e0e0); display: flex; align-items: center; justify-content: center; color: #999; font-size: 14px; }
    </style>
</head>
<body>
    <nav>
        <h1>💕 My Wishlist</h1>
        <div>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>

    <div class="container">
        <h2>Your Wishlist</h2>
        
        <h3>Add Item to Wishlist</h3>
        <div class="add-wishlist-form">
            <?php if ($wishlist_message) echo "<div class='message " . (strpos($wishlist_message, '✗') !== false ? 'error' : '') . "'>" . htmlspecialchars($wishlist_message) . "</div>"; ?>
            <p style="font-size: 13px; color: #666; margin: 0 0 15px 0;">
                <strong>Tip:</strong> For image URLs, try Pinterest, Amazon, or Sephora product links
            </p>
            <form method="post" action="">
                <input type="hidden" name="add_wishlist" value="1">
                <input type="text" name="product_name" placeholder="Product Name" required>
                <select name="category" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat ?>"><?= $cat ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="number" name="estimated_price" placeholder="Estimated Price" step="0.01" required>
                <input type="url" name="image_url" placeholder="Image URL (optional)">
                <textarea name="notes" placeholder="Notes or description (optional)" rows="3"></textarea>
                <button type="submit">✨ Add to Wishlist</button>
            </form>
        </div>

        <h3>Wishlist Items</h3>
        <?php if (!empty($wishlist_items)): ?>
            <div class="wishlist-grid" id="wishlistGrid">
                <?php foreach ($wishlist_items as $item): ?>
                    <div class="wishlist-card draggable scroll-reveal" draggable="true" data-id="<?= $item['id'] ?>">
                        <?php if ($item['image_url']): ?>
                            <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="image-placeholder" style="display:none;">Image unavailable</div>
                        <?php else: ?>
                            <div class="image-placeholder">No Image</div>
                        <?php endif; ?>
                        <div class="wishlist-card-content">
                            <h4><?= htmlspecialchars($item['product_name']) ?></h4>
                            <p><?= htmlspecialchars($item['category']) ?></p>
                            <p class="price">$<?= number_format($item['estimated_price'], 2) ?></p>
                            <?php if ($item['notes']): ?>
                                <p class="notes"><?= htmlspecialchars($item['notes']) ?></p>
                            <?php endif; ?>
                            <div class="wishlist-actions">
                                <a href="edit_wishlist.php?id=<?= $item['id'] ?>" class="action-btn edit-btn">Edit</a>
                                <a href="delete_wishlist.php?id=<?= $item['id'] ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure?');">Delete</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-message">
                <p>Your wishlist is empty. Add items you want to buy! 🛍️</p>
            </div>
        <?php endif; ?>
    </div>


