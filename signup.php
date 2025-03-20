<?php
require "config.php";

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->name) && !empty($data->username) && !empty($data->password)) {
    $name = $data->name;
    $username = $data->username;
    $password = password_hash($data->password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (name, username, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $username, $password);

    if ($stmt->execute()) {
        echo json_encode(["message" => "User registered successfully"]);
    } else {
        echo json_encode(["error" => "Registration failed"]);
    }
} else {
    echo json_encode(["error" => "Invalid input"]);
}
?>
