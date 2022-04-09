<?php
require_once "validateToken.php";
use Firebase\JWT\JWT;

require_once  __DIR__ . '/vendor/autoload.php';

//db password is Giraffe400
// using camelcase
$dsn = 'mysql:dbname=2021_comp10120_z20;host=dbhost.cs.man.ac.uk'; // DSN - Data source name - server name is local host, can also use IP
$DBusername = 'z70503ns'; // username and pw for user that connects to DB
$DBpassword = 'Giraffe400';

#$commenterName = "jallepeno";

$headers = apache_request_headers();

$jwt =  $headers["Authorization"];
$validJwt = validHeader($jwt);

$userId = extractId($jwt);
$email = extractEmail($jwt);
$username = extractUsername($jwt);
$tag= $_POST["data"];

$pdo = new PDO($dsn, $DBusername, $DBpassword);
$sql = "INSERT INTO userLikedTags (userId,tagId) values (:uid , (SELECT id from tags where tagName=:tagnam))";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'tagnam' => $tag,
    'uid' => $userId
]);

?>
