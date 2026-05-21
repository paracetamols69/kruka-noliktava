<?php
header("Content-Type: application/json");
session_start();

require "../includes/db.php";
require "../includes/data_validation.php";

$data = json_decode(file_get_contents("php://input"), true);

$username = trim($data["username"]);
$password = $data["password"];
$password2 = $data["password2"];
$created_at = time();

if ($password !== $password2) {
    echo json_encode(["error" => "Passwords do not match"]);
    exit;
}
$u_errors = validate_username($username);
$p_errors = validate_password($password);

if (!empty($u_errors)) {
    echo json_encode(["error" => "Username criteria not met"]);
    exit;
}

if (!empty($p_errors)) {
    echo json_encode(["error" => "Password criteria not met"]);
    exit;
}

$password_hash = password_hash($password, PASSWORD_BCRYPT);

$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["error" => "Username is taken"]);
    exit;
}
else {
    $stmt = $conn->prepare("INSERT INTO users (username, password_hash, created_at) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $username, $password_hash, $created_at);
    $stmt->execute();

    $user_id = $conn->insert_id;

    session_regenerate_id(true);
    $_SESSION["user_id"] = $user_id;
    $_SESSION["username"] = $username;

    echo json_encode(["success" => true, "redirect" => "/home"]);
    exit;
}