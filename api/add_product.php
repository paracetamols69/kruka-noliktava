<?php
header("Content-Type: application/json");
session_start();
require "../includes/db.php";

if (!isset($_SESSION["user_id"])) {
    http_response_code(403);
    echo json_encode(["error" => "Not logged in"]);
    exit;
}

if ($_SESSION["user_role"] != 3 && $_SESSION["user_role"] != 2) {
    http_response_code(403);
    echo json_encode(["error" => "Insufficient permissions"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$product_name = $data["product_name"];
$count = $data["count"];

$stmt = $conn->prepare("INSERT INTO products (product_name, stock) VALUES (?, ?)");
$stmt->bind_param("si", $product_name, $count);
$stmt->execute();

if ($conn->errno === 1062) {
    http_response_code(409); // 409 - conflict
    echo json_encode(["error" => "Database conflict: entry with same product name already exists"]);
    exit;
}

echo json_encode(["success" => "all good nemiz"]);