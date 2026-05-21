<?php
header("Content-Type: application/json");

session_start();
require "../includes/db.php";

$data = json_decode(file_get_contents("php://input"), true);

$username = trim($data["username"]);
$password = $data["password"];
$password_hash = password_hash($password, PASSWORD_BCRYPT);

$stmt = $conn->prepare("SELECT id, username, password_hash, role FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user["password_hash"])) {
    session_regenerate_id(true);
    $_SESSION["user_id"] = $user["id"];
    $_SESSION["username"] = $user["username"];
    $_SESSION["user_role"] = $user["role"];

    echo json_encode(["success" => true, "redirect" => "/home"]);
    exit;
}
else {
    echo json_encode(["error" => "Invalid credentials"]);
    exit;
}



