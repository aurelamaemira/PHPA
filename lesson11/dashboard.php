<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<?php
include_once("config.php");
$getUsers=$conn->prepare("SELECT * FROM user");
$getUsers->execute();
$user=$getUsers->fetchAll();

?>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Surname</th>
        <th>Email</th>
    </tr>
    <?php
    foreach($user as $users){
    ?>
    <tr>
        <td> <?= $users['id']?></td>
        <td> <?= $users['name']?></td>
        <td> <?= $users['surname']?></td>
        <td> <?= $users['email']?></td>
        <td><?= "<a href= 'delete.php?id=$users[id]'>Delete </a>  | <a href= 'edit.php?id=$users[id]'>Update </a>"?></td>
    </tr>
    <?php
    }
    ?>
</table>
</body>
</html>