<?php 

try{
    $pdo=new PDO("mysql:host=localhost;dbname=db", "root", "");
    $sql="ALTER TABLE users DROP COLUMN username";

    $pdo-> exec($sql);

    echo "Column dropped";

}catch (PDOException $e){
echo $e.getMessage ();
}

?>
