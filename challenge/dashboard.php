<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<?php
include_once("config.php");
$getProducts=$conn->prepare("SELECT * FROM products");
$getProducts->execute();
$products=$getProducts->fetchAll();

?>

<table>
    <tr>
        <th>ID</th>
        <th>title</th>
        <th>description</th>
        <th>quantity</th>
        <th>price</th>
    </tr>
    <?php
    foreach($products as $products){
    ?>
    <tr>
        <td> <?= $products['id']?></td>
        <td> <?= $products['title']?></td>
        <td> <?= $products['description']?></td>
        <td> <?= $products['quantity']?></td>
        <td> <?= $products['price']?></td>
        <td><?= "<a href= 'delete.php?id=$products[id]'>Delete </a>  | <a href= 'editproduct.php?id=$products[id]'>Update </a>"?></td>
        <a href="add.php"><button>Add Product</button></a>
    </tr>
    <?php
    }
    ?>
</table>
</body>
</html>