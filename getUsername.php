<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . '/vendor/autoload.php';
require_once 'validateToken.php';


$headers = apache_request_headers();

$jwt =  $headers["Authorization"];

if (validHeader($jwt)){
    $secretKey = "bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew";    
    echo json_encode(JWT::decode($jwt, new Key($secretKey, "HS512")));
} else {
    echo json_encode(null);
}
?>