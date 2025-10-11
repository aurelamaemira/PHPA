<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        table,td,th{
            border:1px solid black;
            border-collapse:collapse;
        }
        td,th{
            padding:10px 20px;
        }
    </style>
</head>
<body>
    <?php
include_once("config.php");
$sql="SELECT * FROM users";
$getUsers=$conn->prepare($sql);
$getUsers->execute();
$user=$getUsers->fetchAll();
    ?>
    <table>
        <thead>
            <th>ID</th>
            <th>name</th>
            <th>username</th>
            <th>email</th>
        </thead>
        <tbody>
            <?php
            foreach($user as $users){
                ?>
            <tr>
                <td><?= $users['id']?></td>
                <td><?= $users['name']?></td>
                <td><?= $users['username']?></td>
                <td><?= $users['email']?></td>
            </tr>
            <?php
            }
            ?>

        </tbody>
    </table>
</body>
</html>