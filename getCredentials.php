<?php


declare(strict_types=1);

use Firebase\JWT\JWT;

require_once  __DIR__ . '/vendor/autoload.php';
require_once  "validateToken.php";

$headers = apache_request_headers();

$jwt =  $headers["Authorization"];

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

if (validHeader($jwt) == 1){
    //echo "this is working here";
    $email = extractEmail($jwt);
    $username = getUsername($email);
    $data = array(
        "email" => $email,
        "username" => $username
    );
    echo json_encode($data);
}



?>