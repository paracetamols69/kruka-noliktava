<?php
header("Content-Type: application/json");
session_start();

require "../../includes/db.php";

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


$data = json_decode(file_get_contents("php://input"), true);

if ($_SESSION["user_id"] == $data["user_id"]) {
    http_response_code(400);
    echo json_encode(["error" => "cant delete self"]);
    exit;
}

$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $data["user_id"]);
$stmt->execute();

echo json_encode(["success" => "User deleted"]);