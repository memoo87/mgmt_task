<?php
require "config.php";
require "vendor/autoload.php";

use Firebase\JWT\JWT;

$secret_key = "your_secret_key";
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->username) && !empty($data->password)) {
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $data->username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && password_verify($data->password, $hashed_password)) {
        $payload = [
            "iss" => "localhost",
            "iat" => time(),
            "exp" => time() + 600,
            "user_id" => $id
        ];
        $jwt = JWT::encode($payload, $secret_key, 'HS256');

        echo json_encode(["token" => $jwt]);
    } else {
        echo json_encode(["error" => "Invalid credentials"]);
    }
} else {
    echo json_encode(["error" => "Invalid input"]);
}
?>
