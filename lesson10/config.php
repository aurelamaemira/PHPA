<?php

session_start();
$user="root";
$pass="";
$server="localhost";
$dbname= "testddb";

try{

    $conn=new PDO("mysql:host=$server;dbname=$dbname",$user,$pass);

}catch(PDOException $e){
    echo "Error" . $e->getMessage();
}