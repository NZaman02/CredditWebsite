<?php

require_once  "checkFree.php";
require_once  "validateToken.php";

$dsn = 'mysql:dbname=2021_comp10120_z20;host=dbhost.cs.man.ac.uk'; // DSN - Data source name - server name is local host, can also use IP
$username = 'z70503ns'; // username and pw for user that connects to DB
$password = 'Giraffe400';


$newEmail = $_POST['emailInp'];

$headers = apache_request_headers();

$jwt =  $headers["Authorization"];


$pdo = new PDO($dsn, $username, $password);

$validJwt = validHeader($jwt);


$emailFree = EmailFree($newEmail, $pdo);

if ($emailFree){
    $oldEmail = extractEmail($jwt);

    $sql = "UPDATE users SET email = :newEmail WHERE email = :oldEmail";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        "newEmail" => $newEmail,
        "oldEmail" => $oldEmail
    ]);
    echo("success");
} else {
    echo ("failed");
}

?>