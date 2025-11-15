<?php
include_once("config.php");

if(isset($_POST['update'])){
    $id=$_POST['id'];
    $title=$_POST['title'];
    $description=$_POST['description'];
    $quantity=$_POST['quantity'];
    $price=$_POST['price'];

    $sql= "UPDATE products SET title=:title, description=:description , quantity=:quantity , price=:price WHERE id=:id";
    $stmt=$conn->prepare($sql);
    $stmt->bindParam(":id",$id);
    $stmt->bindParam(":title",$title);
    $stmt->bindParam(":description",$description);
    $stmt->bindParam(":quantity",$quantity);
    $stmt->bindParam(":price",$price);

    $prep->execute();

    header("Location: dashboard.php");

}
?>