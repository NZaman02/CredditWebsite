<?php
function checkPassword($pdo, $email, $userPassword){
    $sql = "SELECT passHash FROM users WHERE email = :email;";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
    "email"=>$email
    ]);

    $passHash = $stmt->fetch();
    
    return $passHash[0] == hash("sha256", $userPassword);
}
?>