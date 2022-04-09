<?php
function getPdo(){
    $dsn = 'mysql:dbname=2021_comp10120_z20;host=dbhost.cs.man.ac.uk'; // DSN - Data source name - server name is local host, can also use IP
    $username = 'z70503ns'; // username and pw for user that connects to DB
    $password = 'Giraffe400';
    $pdo = new PDO($dsn, $username, $password);
    return $pdo;
}

function getPostsForUser($userId){
    $pdo = getPdo();
    $sql = "SELECT users.username, sites.name, articles.url, articles.id
    FROM users, articles, sites, userLikedTags, articleTags
    WHERE sites.id = articles.siteId AND users.id = articles.authorId AND userLikedTags.userId = 2 AND userLikedTags.tagId = articleTags.tagId AND articleTags.articleId = articles.id AND articleTags.major = TRUE
    ORDER BY articles.date DESC;";
    $stmt = $pdo->prepare($sql);
    //if theres an error here itll be due to how the tags are checked
    $stmt->execute(["userId"=>$userId]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $json = json_encode($results);
    return $json;
}

function getPosts(){
    $pdo = getPdo();
    // whoah big sql query OwO
    $sql = "SELECT users.username, sites.name, articles.url, articles.id, articles.credValue
        FROM users, articles, sites  where sites.id = articles.siteId and users.id=articles.authorId
        ORDER BY articles.date DESC;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $json = json_encode($results);
    return $json;
}

function upvotePost($postId,$username,$positive){
    //upvote or downvote
    
    $pdo = getPdo();
    $change = -1;
    if ($positive) {
        $change = +1;
    }

    //how credable is the user
    $sql = "SELECT credValue from users where username= :username;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["username"=>$username]);
    $row = $stmt->fetch();
    //add one to ensure 0 value users arent valueless
    $change *= (int)($row['credValue']+1)/10;

    // update article and site cred
    $sql = "UPDATE articles, sites set articles.credValue=articles.credValue + " . strval($change) . ",sites.credValue = sites.credValue + ". strval($change/10) ." where articles.id = :postId and sites.id=articles.siteId";
    $pdo = getPdo();
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["postId"=>$postId]);

    //get credvalue for post
    $sql = "SELECT credValue from articles where id = :postId";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["postId"=>$postId]);
    $row = $stmt->fetch();
    $change = 0.1;

    //judge users sins
    if (($row['credValue']>10 and $positive == FALSE) or ($row['credValue']<-10 and $positive == TRUE)){
        $change =-0.1;
    }
    //update them
    $sql = "UPDATE 'users' set 'users.credValue'=users.credValue + ". strval($change) ."  where users.username== :username";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["username"=>$username]);
}

function getTree($comId,$artId){
    $sql = "SELECT comments.id, users.username, comments.content from comments,users,commentRelation where commentRelation.headId = :headId and commentRelation.tailId =comments.id and comments.articleId = :articleId and comments.userId = users.id and baseComment=False";
    $pdo = getPdo();
    $stmt = $pdo->prepare($sql);
    $stmt-> execute([
        "headId" => $comId,
        "articleId" => $artId
    ]);
    $r = array();
    $rows = $stmt->fetchAll();
    foreach ($rows as $row) {
        $a = array(array($row["id"],$row["username"],$row["content"]),array());
        $a[1] = getTree($row["id"],$artId);
        array_push($r,$a);
    }
    return $r;
}

function commentTrees($postId) {
    $sql = "SELECT users.username, comments.id, comments.content from comments, users where baseComment = True and articleId = :id and users.id=comments.userId";
    $pdo = getPdo();
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["id"=>$postId]);
    $rows = $stmt->fetchAll();
    $r = array();
    foreach ($rows as $row) {
        $a = array(array($row["id"],$row["username"],$row["content"]),array());
        $a[1] = getTree($row["id"],$postId);
        array_push($r,$a);
        // Do work here ..
    }
    return json_encode($r);
}


?>
