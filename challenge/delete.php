<?php
include_once("config.php");

$id=$_GET['id'];

$sql= "DELETE FROM products WHERE id=:id";

$getProducts=$conn-> prepare ($sql);

$getProducts->bindParam(":id",$id);

$getProducts->execute();

header("Location: dashboard.php");

?>