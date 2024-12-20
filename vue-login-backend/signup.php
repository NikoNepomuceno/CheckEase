<?php
header('Content-Type: utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

use \Firebase\JWT\JWT;
require 'vendor/autoload.php';

include 'db.php';

$secret_key = "your_secret_key"; // Keep it secure

$data = json_decode(file_get_contents("php://input"));
$firstname = htmlspecialchars($data->firstname);
$lastname = htmlspecialchars($data->lastname);
$email = htmlspecialchars($data->email);
$password = password_hash($data->password, PASSWORD_DEFAULT); 
$role = htmlspecialchars($data->role);

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => false, 'message' => 'Email already registered']);
        exit;
    }

    $sql = "INSERT INTO users (firstname, lastname, email, password, role) VALUES (:firstname, :lastname, :email, :password, :role)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'firstname' => $firstname,
        'lastname' => $lastname,
        'email' => $email,
        'password' => $password,
        'role' => $role
    ]);

    // Generate JWT token after signup
    $payload = [
        "iss" => "your_website_url",
        "aud" => "your_website_url",
        "iat" => time(),
        "exp" => time() + (60*60), // Token expiration time (1 hour)
        "data" => [
            "firstname" => $firstname,
            "lastname" => $lastname,
            "email" => $email,
            "role" => $role
        ]
    ];

    $jwt = JWT::encode($payload, $secret_key, 'HS256');

    echo json_encode(['success' => true, 'message' => 'User registered successfully', 'token' => $jwt, 'firstname' => $firstname]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Registration failed.']);
}
