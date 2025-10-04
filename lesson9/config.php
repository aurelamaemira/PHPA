<?php
sessio_start();

$user="root";
$pass="";
$server+"localhost";
$dbname="testdbb";

try{
    $conn=new PDO ("mysql:host=$server;dbname=$dbame", $user, $pass);

}catch (PDOException $e){
    echo "Error:" . $e->getMessage();
}