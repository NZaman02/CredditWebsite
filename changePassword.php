<?php
require_once  "validateToken.php";

$dsn = 'mysql:dbname=2021_comp10120_z20;host=dbhost.cs.man.ac.uk'; // DSN - Data source name - server name is local host, can also use IP
$username = 'z70503ns'; // username and pw for user that connects to DB
$password = 'Giraffe400';


$newPassword = $_POST['password1'];

$headers = apache_request_headers();

$jwt =  $headers["Authorization"];


$pdo = new PDO($dsn, $username, $password);

$validJwt = validHeader($jwt);

if ($validJwt){
    $email = extractEmail($jwt);
    $sql = "UPDATE users SET passHash = :newPassHash WHERE email = :email";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        "newPassHash"=> hash("sha256", $newPassword),
        "email" => $email
    ]);
    echo "successfully changed password";
} else {
    echo "couldnt change password";
}

?>