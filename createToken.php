<?php
// login php

declare(strict_types=1);

use Firebase\JWT\JWT;
require __DIR__ . '/vendor/autoload.php';

require "login.php";

$dsn = 'mysql:dbname=2021_comp10120_z20;host=dbhost.cs.man.ac.uk'; // DSN - Data source name - server name is local host, can also use IP
$username = 'z70503ns'; // username and pw for user that connects to DB
$password = 'Giraffe400';

$email = $_POST["email"];
$userPassword = $_POST["password"];
// $email = "123";//$_POST["email"];
// $userPassword = "123";//$_POST["password"];

$pdo = new PDO($dsn, $username, $password);

function keyGen(){
    return "bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew";// return md5(uniqid("", true));
}

function getId($email){
    $dsn = 'mysql:dbname=2021_comp10120_z20;host=dbhost.cs.man.ac.uk'; // DSN - Data source name - server name is local host, can also use IP
    $username = 'z70503ns'; // username and pw for user that connects to DB
    $password = 'Giraffe400';

    $pdo = new PDO($dsn, $username, $password);
    $sql = "SELECT id FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        "email"=> $email
    ]);
    
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $row = $stmt->fetch();
    return $row["id"]; // returns 1 for true
}

function getUsername($email){
    $dsn = 'mysql:dbname=2021_comp10120_z20;host=dbhost.cs.man.ac.uk'; // DSN - Data source name - server name is local host, can also use IP
    $username = 'z70503ns'; // username and pw for user that connects to DB
    $password = 'Giraffe400';

    $pdo = new PDO($dsn, $username, $password);
    $sql = "SELECT username FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        "email"=> $email
    ]);
    
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $row = $stmt->fetch();
    return $row["username"]; // returns 1 for true
}

function getToken($email){
    //$tokenId = base64_encode(random_bytes(16));
    $issuedAt = new DateTimeImmutable();
    $expire = $issuedAt->modify("+120 minutes")->getTimestamp();
    $serverName = "dbhost.cs.man.ac.uk";
    $username = getUsername($email);
    $id = getId($email);

    $data = [
        'iat'  => $issuedAt->getTimestamp(),    // Issued at: time when the token was generated
        //'jti'  => $tokenId,                     // Json Token Id: an unique identifier for the token
        'iss'  => $serverName,                  // Issuer
        'nbf'  => $issuedAt->getTimestamp(),    // Not before
        'exp'  => $expire,                      // Expire
        'email' => $email,
        'username' => $username,
        'id' => $id   // User name
    ];
    return $data;
}

$validLogin = checkPassword($pdo, $email, $userPassword);
//echo($validLogin);
if ($validLogin == 1){
    $secretKey = keyGen();
    $data = getToken($email);

    echo (JWT::encode(
        $data,
        $secretKey,
        "HS512",

    ));
} else {
    echo "failed login";
}

?>