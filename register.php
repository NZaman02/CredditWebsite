<?php

require "checkFree.php";


function createAccountRecord($email, $username, $password, $pdo){
    $sql = "INSERT INTO users (username, email, credValue, passHash)
    VALUES (:username, :email, :cred, :passHash)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        "username"=> $username,
        "email"=> $email,
        "cred"=> 0,
        "passHash"=> hash("sha256", $password)

    ]);
}

function register(){
    $dsn = 'mysql:dbname=2021_comp10120_z20;host=dbhost.cs.man.ac.uk'; // DSN - Data source name - server name is local host, can also use IP
    $username = 'z70503ns'; // username and pw for user that connects to DB
    $password = 'Giraffe400';

    $user_password = $_POST['password_1'];
    $user_username = $_POST['username'];
    $user_email = $_POST['email'];

    $pdo = new PDO($dsn, $username, $password);
    
    $usernameFree = UsernameFree($user_username, $pdo);
    $emailFree = EmailFree($user_email, $pdo);

    $login_conditions = array(
        "success" => true,
        "username free" => $usernameFree,
        "email free" => $emailFree
    );

    if ($usernameFree == 1 && $emailFree == 1){
        createAccountRecord($user_email, $user_username, $user_password, $pdo);
    } else {
        $login_conditions = array_replace($login_conditions, array("success" => false));
    }

    
    echo(json_encode($login_conditions));

}

register();
?>
