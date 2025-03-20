<?php
require "vendor/autoload.php";
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secret_key = "your_secret_key";

function verifyToken() {
    $headers = apache_request_headers();
    if (!isset($headers["Authorization"])) {
        echo json_encode(["error" => "Token required"]);
        exit;
    }

    $token = str_replace("Bearer ", "", $headers["Authorization"]);

    try {
        $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
        return $decoded->user_id;
    } catch (Exception $e) {
        echo json_encode(["error" => "Invalid token"]);
        exit;
    }
}
?>
