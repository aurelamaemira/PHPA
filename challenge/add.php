<?php
include_once("config.php");

if (isset($_POST['add'])) {

    $title = $_POST['title'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    $sql = "INSERT INTO products (title, description, quantity, price)
            VALUES (:title, :description, :quantity, :price)";

    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':price', $price);

    $stmt->execute();

    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
</head>
<body>

<h2>Add New Product</h2>

<form action="add.php" method="POST">

    <label>Title:</label><br>
    <input type="text" name="title" required><br><br>

    <label>Description:</label><br>
    <textarea name="description" required></textarea><br><br>

    <label>Quantity:</label><br>
    <input type="number" name="quantity" required><br><br>

    <label>Price:</label><br>
    <input type="number" name="price" required><br><br>

    <button type="submit" name="add">Add Product</button>

</form>

</body>
</html>
