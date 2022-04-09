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
$comment = $_POST['comment'];
$postId = $_POST['postId'];
$parentId = $_POST["parentId"];
$isBase = $_POST["isBase"];
echo $isBase;

$headers = apache_request_headers();

$jwt =  $headers["Authorization"];
$validJwt = validHeader($jwt);

$userId = extractId($jwt);
$email = extractEmail($jwt);
$username = extractUsername($jwt);

if ($validJwt){
  $pdo = new PDO($dsn, $DBusername, $DBpassword);

  $sql = "INSERT INTO comments (articleId, userId, number, credValue, likeability, content, baseComment) 
          VALUES (:articleId, :userId, :number, :credValue, :likeability, :content, :baseComment);";

  $stmt = $pdo->prepare($sql);

  $stmt->execute([
    'articleId' => $postId,
    'userId' => $userId,
    'number' => 0,
    'credValue' => 0,
    'likeability' => 0,
    'content' => $comment,
    'baseComment' => $isBase
  ]);

  if (!$isBase){
    $id = $pdo->lastInsertId();
    $sql = "INSERT INTO commentRelation (headId, tailId) VALUES (:headId, :tailId)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
      "headId" => $parentId,
      "tailId" => $id
    ]);
  }
}
?>