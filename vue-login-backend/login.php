<?php
// login.php
include'db.php';

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");



// Get the JSON input from Vue
$input = json_decode(file_get_contents("php://input"), true);
$userEmail = $input['email'];
$userPassword = $input['password'];

// Validate input
if (!empty($userEmail) && !empty($userPassword)) {
    // Query to check for user
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $userEmail);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check password (assuming passwords are hashed in the database)
    if ($user && password_verify($userPassword, $user['password'])) {
        echo json_encode(["success" => true, "message" => "Login successful."]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid email or password."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Please provide both email and password."]);
}

