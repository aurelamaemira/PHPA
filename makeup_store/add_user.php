<?php
include_once("config.php");

if(isset($_POST['add_user'])){
    $name=$_POST['name'];
    $surname=$_POST['surname'];
    $email=$_POST['email'];

    $sql= "INSERT INTO user (name, surname, email) VALUES (:name, :surname, :email)";
    $stmt=$conn->prepare($sql);
    $stmt->bindParam(":name",$name);
    $stmt->bindParam(":surname",$surname);
    $stmt->bindParam(":email",$email);

    $stmt->execute();

    header("Location: dashboard.php");

}
?>