<?php
use Firebase\JWT\JWT;

require_once  __DIR__ . '/vendor/autoload.php';
require_once  "validateToken.php";

$dsn = 'mysql:dbname=2021_comp10120_z20;host=dbhost.cs.man.ac.uk'; // DSN - Data source name - server name is local host, can also use IP
$username = 'z70503ns'; // username and pw for user that connects to DB
$password = 'Giraffe400';

$pdo = new PDO($dsn, $username, $password);

$headers = apache_request_headers();

$jwt =  $headers["Authorization"];

$validToken = validHeader($jwt);

$tags = $_POST["color"];
$major = $_POST["majorTag"];
$url = $_POST["articleLink"];

if ($validToken){

    $email = extractEmail($jwt);
    $siteId = 1;

    $sql = "INSERT INTO articles (authorId, siteId, url, credValue, likeability)
    VALUES ((SELECT id FROM users WHERE email = :email), :siteId, :url, :credValue, :likeability)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        "email"=> $email,
        "siteId"=> $siteId,
        "url"=> $url,
        "credValue"=> 0,
        "likeability"=> 0
    ]);

    $id = $pdo->lastInsertId();

    foreach (range(0, count($tags)-1) as $i){
        $sql = "INSERT INTO articleTags(articleId, tagId, major)
        VALUES (:articleId, (SELECT id FROM tags WHERE tagName = :tagName), :major);";

        $stmt = $pdo->prepare($sql);

        $tagName = $tags[$i];
        echo $tagName;

        $stmt->execute([
            "articleId" => $id,
            "tagName" => $tagName,
            "major" => (int)($tagName == $major)
        ]);

    };

}
echo "success";
?>