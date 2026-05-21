<?php
header("Content-Type: application/json");

session_start();
require "../includes/db.php";

if (!isset($_SESSION["user_id"])) {
    http_response_code(403);
    echo json_encode(["error" => "Not logged in"]);
    exit;
}

if ($_SESSION["user_role"] != 3) {
    http_response_code(403);
    echo json_encode(["error" => "Insufficient permissions"]);
    exit;
}

$stmt = $conn->prepare("SELECT id, username, role, created_at FROM users");
$stmt->execute();

$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($users);
