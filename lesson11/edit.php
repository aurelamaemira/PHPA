<?php
include_once("config.php");

$id=$_GET['id'];
$sql= "SELECT * FROM user WHERE id=:id";


$prep=$conn->prepare($sql);
$prep->bindParam(":id",$id);
$prep->execute();
$data=$prep->fetch();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="update.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $data['id']; ?>"><br>
        <input type="text" name="name" value="<?php echo $data['name']; ?>"><br>
        <input type="text" name="surname" value="<?php echo $data['surname']; ?>"><br>
        <input type="email" name="email" value="<?php echo $data['email']; ?>"><br>

        <button type="submit" name="update">Update</button>

        <a href="dashboard.php">Dashboard</a>
    </form>
</body> 
</html>
