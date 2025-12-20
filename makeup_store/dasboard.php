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
    try {
        $stmt->execute([$user_id, $category]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $totals[$category] = $row['total'] ?? 0;
    } catch (PDOException $e) {
        $totals[$category] = 0;
    }
}

$add_user_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($name && $email && $password) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $add_user_message = "Email already exists!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $hashed_password]);
            $add_user_message = "User added successfully!";
        }
    } else {
        $add_user_message = "Please fill in all fields!";
    }
}

try {
    $stmt = $pdo->query("SELECT id, name, email FROM users ORDER BY id ASC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $users = [];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Makeup Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; margin: 0; background: linear-gradient(135deg, #ffb3d9, #ffe6f0); }
        nav { background-color: #ff3399; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        nav h1 { margin: 0; font-size: 24px; }
        nav a { color: white; text-decoration: none; font-weight: 600; }
        .container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        h2, h3 { color: #ff3399; }
        .cards { display: flex; flex-wrap: wrap; gap: 20px; margin-top: 20px; }
        .card { background-color: white; flex: 1 1 calc(33% - 20px); padding: 30px 20px; border-radius: 15px; box-shadow: 0 8px 20px rgba(0,0,0,0.15); text-align: center; transition: transform 0.3s; }
        .card:hover { transform: translateY(-5px); }
        .card h3 { margin: 0 0 10px 0; color: #ff3399; font-size: 22px; }
        .card p { font-size: 20px; font-weight: 600; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 30px; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #ff3399; color: white; }
        tr:hover { background-color: #ffe6f0; }
        .action-btn { padding: 6px 12px; margin-right: 5px; border: none; border-radius: 5px; color: white; cursor: pointer; text-decoration: none; font-size: 14px; }
        .edit-btn { background-color: #4CAF50; } .delete-btn { background-color: #f44336; }
        .edit-btn:hover { background-color: #45a049; } .delete-btn:hover { background-color: #da190b; }
        .add-user-form { background: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .add-user-form input { padding: 10px; margin: 5px; border-radius: 5px; border: 1px solid #ccc; }
        .add-user-form button { background-color: #ff3399; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: 600; }
        .add-user-form button:hover { background-color: #e60073; }
        .message { color: red; margin-bottom: 10px; }
        @media (max-width: 700px) { .card { flex: 1 1 100%; } }
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
                    <h3><?= htmlspecialchars($category) ?></h3>
                    <p>$<?= number_format($total, 2) ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <h3>Add New User</h3>
        <div class="add-user-form">
            <?php if ($add_user_message) echo "<div class='message'>" . $add_user_message . "</div>"; ?>
            <form method="post" action="">
                <input type="hidden" name="add_user" value="1">
                <input type="text" name="name" placeholder="Full Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Add User</button>
            </form>
        </div>

        <h3>Users List</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th><th>Name</th><th>Email</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id']) ?></td>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td>
                                <a href="edit_user.php?id=<?= $user['id'] ?>" class="action-btn edit-btn">Edit</a>
                                <a href="delete_user.php?id=<?= $user['id'] ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4">No users found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
