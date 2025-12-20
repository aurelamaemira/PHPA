<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$edit_message = '';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: dashboard.php');
    exit();
}

$user_id_to_edit = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id_to_edit]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($name && $email) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $user_id_to_edit]);
        if ($stmt->fetch()) {
            $edit_message = "Email already exists!";
        } else {
            if (!empty($password)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
                $stmt->execute([$name, $email, $hashed_password, $user_id_to_edit]);
            } else {
                $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
                $stmt->execute([$name, $email, $user_id_to_edit]);
            }
            $edit_message = "User updated successfully!";
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$user_id_to_edit]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    } else {
        $edit_message = "Please fill in all required fields!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User - Makeup Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; margin: 0; background: #ffe6f0; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .edit-box { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.2); width: 400px; }
        h2 { color: #ff3399; margin-bottom: 20px; text-align: center; }
        input { width: 100%; padding: 12px; margin: 10px 0; border-radius: 5px; border: 1px solid #ccc; }
        button { width: 100%; padding: 12px; background: #ff3399; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: 600; }
        button:hover { background: #e60073; }
        .message { color: red; margin-bottom: 10px; text-align: center; }
        .back-link { display: block; text-align: center; margin-top: 15px; color: #ff3399; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="edit-box">
        <h2>Edit User</h2>
        <?php if ($edit_message) echo "<div class='message'>{$edit_message}</div>"; ?>
        <form method="post" action="">
            <input type="text" name="name" placeholder="Full Name" value="<?= htmlspecialchars($user['name']) ?>" required>
            <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($user['email']) ?>" required>
            <input type="password" name="password" placeholder="New Password">
            <button type="submit">Update User</button>
        </form>
        <a href="dashboard.php" class="back-link">Back to Dashboard</a>
    </div>
</body>
</html>
