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

if (!isset($data['user_id']) || !isset($data['role'])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing required fields"]);
    exit;
}

$userIdToEdit = intval($data['user_id']);
$newRole = intval($data['role']);
$currentAdminId = intval($_SESSION["user_id"]);

if ($userIdToEdit === $currentAdminId) {
    http_response_code(400);
    echo json_encode(["error" => "You cant change your own role!"]);
    exit;
}


$stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
$stmt->bind_param("ii", $newRole, $userIdToEdit);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Database error failed to update role"]);
}
?>