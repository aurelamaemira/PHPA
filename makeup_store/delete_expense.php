<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['id'])) {
    $expense_id = (int)$_GET['id'];
    
   
    $stmt = $pdo->prepare("DELETE FROM purchases WHERE id = ? AND user_id = ?");
    $stmt->execute([$expense_id, $user_id]);
}

header('Location: dashboard.php');
exit();
?>
