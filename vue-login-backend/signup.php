<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include 'db.php';

$data = json_decode(file_get_contents("php://input"));

$firstname = $data->firstname;
$lastname = $data->lastname;
$username = $data->username;
$email = $data->email;
$password = password_hash($data->password, PASSWORD_DEFAULT); // Hash the password
$role = $data->role;

// Database query to insert new user
$query = "INSERT INTO users (firstname, lastname, username, email, password, role) VALUES ('$firstname', '$lastname', '$username', '$email', '$password', '$role')";

if ($db->query($query) === TRUE) {
    echo json_encode(['success' => true, 'message' => 'Signup successful!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Signup failed. Please try again.']);
}
