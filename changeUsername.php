<?php
require_once  "checkFree.php";
require_once  "validateToken.php";
require_once "getCredentials.php";

$dsn = 'mysql:dbname=2021_comp10120_z20;host=dbhost.cs.man.ac.uk'; // DSN - Data source name - server name is local host, can also use IP
$username = 'z70503ns'; // username and pw for user that connects to DB
$password = 'Giraffe400';


$newUsername = $_POST['username'];

$headers = apache_request_headers();

$jwt =  $headers["Authorization"];


$pdo = new PDO($dsn, $username, $password);

$validJwt = validHeader($jwt);

$usernameFree = UsernameFree($newUsername, $pdo);

if ($usernameFree){
    $email = extractEmail($jwt);
    $oldUsername = getUsername($email);
    $sql = "UPDATE users SET username = :newUsername WHERE username = :oldUsername";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        "oldUsername" => $oldUsername,
        "newUsername" => $newUsername
    ]);
    echo ("success");
} else {
    echo ("failed to changed username");
}

?>