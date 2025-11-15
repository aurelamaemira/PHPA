<?php

$user = "root";
$pass = "";
$server = "localhost";
$dbname = "products";

try {
    $conn = new PDO("mysql:host=$server;dbname=$dbname;charset=utf8", $user, $pass);


    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo "Database Connection Error: " . $e->getMessage();
    exit();
}