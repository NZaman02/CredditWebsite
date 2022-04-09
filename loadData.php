<?php
    use Dusterio\LinkPreview\Client;
    
    require_once "mainFuncs.php";
    require __DIR__ . '/vendor/autoload.php';

    $posts = json_decode(getPosts(), true);
    $baseIndex = $_GET["baseInd"];
    $endIndex = $_GET["endInd"];
    $postsInfo = array();


    for($i = $baseIndex; ($i < $endIndex && $i < count($posts)-1); $i++){
        $url = $posts[$i]["url"];
        $id = $posts[$i]["id"];
        $cred = $posts[$i]["credValue"];
        $previewClient = new Client($url);
        $previews = $previewClient->getPreviews();
        $preview = $previewClient->getPreview('general');
        $preview = $preview->toArray();
        $postInfo = array(
            "preview" => $preview,
            "id" => $id,
            "url" => $url,
            "credValue" => $cred,
            "comments" => json_decode(commentTrees($id))
        );
        array_push($postsInfo, $postInfo);
    }

    array_reverse($postsInfo);
    echo json_encode($postsInfo);
?>