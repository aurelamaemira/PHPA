<?php

$user="root";
$pass="";
$server="localhost";
$dbname="products";

try{
    $conn= new PDO("mysql:host=$server;dbname=$products",$products.$pass);
}catch(PDOException $e){

    echo "Error" . $e->getMessage();
}