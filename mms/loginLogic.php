<?php

session_start();

include_once ('config.php');

if(isser($_POST['submit']))
{
    $username= $_POST['username'];
    $password= $_POST['password'];

    if(empty($username) || empty($password)){
        echo "Ju lutem plotesoni hapesirat e zbrazeta!";
}else{
    $sql= "SELECT * FROM id, emri, username, email, password FROM users WHERE username=:username";
    $selectUser = $conn->prepare($sql);
    $selectUser->bindParam(":username", $username);
    $selectUser->execute();
    $data = $selectUser->fetch();
    if($data == false){
        echo "Perdoruesi nuk u gjet!";
    }else{
        if(password_verify($password, $data['password'])){
            $_SESSION['id'] = $data['id'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['emauk'] = $data['email'];
            $_SESSION['emri'] = $data['emri'];
            $_SESSION['is_admin'] = $data['is_admin'];

            header('Location:');
        }else{
            echo "Fjalekalimi eshte gabim!";
        }

    }
}
}
?>