<?php
include_once("config.php");

if(isset($_POST['update'])){
    $id=$_POST['id'];
    $name=$_POST['name'];
    $surname=$_POST['surname'];
    $email=$_POST['email'];

    $sql= "UPDATE user SET name=:name, surname=:surname, email=:email WHERE id=:id";
    $stmt=$conn->prepare($sql);
    $stmt->bindParam(":id",$id);
    $stmt->bindParam(":name",$name);
    $stmt->bindParam(":surname",$surname);
    $stmt->bindParam(":email",$email);

    $prep->execute();

    header("Location: dashboard.php");

}
?>