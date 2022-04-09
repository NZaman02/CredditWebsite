<?php


function UsernameFree($username, $pdo){
    $sql = "SELECT 1 FROM users WHERE username = :username";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        "username"=> $username
    ]);
    
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $row = $stmt->fetch();
    return $row == null; // returns 1 for true
}

function EmailFree($email, $pdo){
    $sql = "SELECT 1 FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        "email"=> $email
    ]);

    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $row = $stmt->fetch();
    return $row == null;
}

?>