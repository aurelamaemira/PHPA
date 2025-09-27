<?php

$host= "localhost";
$db="db";
$users="root";
$pass="";

try{
$pdo=new PDO("mysql:host=$host;dbname=$db", $users,$pass);
$sql="CREATE TABLE users (id INT(6) NOT NULL AUTO_INCREMENT PRIMARY KEY,
USERNAME VARCHAR (30) not null,
password varchar (50) not null)";

$pdo-> exec($sql);

echo "Table created successfully";

}catch(Exception $e){
    echo "Error creating table: " . $e->getMessage();

}

?>
