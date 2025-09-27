<?php 

try{
    $pdo=new PDO("mysql:host=localhost;dbname=db", "root", "");
    $sql="DROP TABLE users";

    $pdo-> exce($sql);

    echo "Table dropped";

}catch (PDOException $e){
echo $e.getMessage ();
}

?>