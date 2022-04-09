<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/vendor/autoload.php';


function validHeader($jwt){
    $secretKey = "bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew";

    $token = JWT::decode($jwt, new Key($secretKey, "HS512"));

    if (is_object($token)){
        return true;
    } else {
        return false;
    }
}

/////// change how the keys are stored !! DONT FORGET

function extractEmail($jwt){    
    $secretKey = "bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew";

    $token = JWT::decode($jwt, new Key($secretKey, "HS512"));

    return $token->email;
}

function extractUsername($jwt){
    $secretKey = "bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew";

    $token = JWT::decode($jwt, new Key($secretKey, "HS512"));

    return $token->username;
}

function extractId($jwt){
    $secretKey = "bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew";

    $token = JWT::decode($jwt, new Key($secretKey, "HS512"));

    return $token->id;
}

?>