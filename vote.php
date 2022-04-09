<?php
require_once  __DIR__ . '/vendor/autoload.php';
require_once  "validateToken.php";
require_once "mainFuncs.php";

$dsn = 'mysql:dbname=2021_comp10120_z20;host=dbhost.cs.man.ac.uk'; // DSN - Data source name - server name is local host, can also use IP
$username = 'z70503ns'; // username and pw for user that connects to DB
$password = 'Giraffe400';

$pdo = new PDO($dsn, $username, $password);

$upvote = $_POST["vote"];
$articleId = $_POST["postId"];
$upvote= ($upvote==True);
$headers = apache_request_headers();

$jwt =  $headers["Authorization"];

$validToken = validHeader($jwt);

if ($validToken){
    $username = extractUsername($jwt);
    upvotePost($articleId, $username, $upvote);
}

?>