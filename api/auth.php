<?php
require_once '../config/database.php';
require_once '../models/User.php';

$database = new Database();
$db = $database->connect();

$user = new User($db);
$data = json_decode(file_get_contents("php://input"));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_GET['action'] === 'register') {
        $user->name = $data->name;
        $user->email = $data->email;
        $user->password = $data->password;

        if ($user->register()) {
            echo json_encode(["message" => "User registered successfully."]);
        } else {
            echo json_encode(["message" => "Unable to register user."]);
        }
    } elseif ($_GET['action'] === 'login') {
        $user->email = $data->email;
        $user->password = $data->password;

        if ($user->login()) {
            echo json_encode(["message" => "Login successful.", "user_id" => $user->id]);
        } else {
            echo json_encode(["message" => "Invalid credentials."]);
        }
    }
}
?>