<?php
header("Content-Type: application/json");
session_start();

require "../../includes/db.php";

if (!isset($_SESSION["user_id"])) {
    http_response_code(403);
    echo json_encode(["error" => "Not logged in"]);
    exit;
}

if ($_SESSION["user_role"] != 3 && $_SESSION["user_role"] != 1) {
    http_response_code(403);
    echo json_encode(["error" => "Insufficient permissions"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$shelf_id = $data["shelf_id"];
$product_id = $data["product_id"];
$stock = $data["stock"];

$stmt = $conn->prepare("INSERT INTO shelves (id, product_id stock) VALUES (?, ?, ?)");
$stmt->bind_param("iii", $shelf_id, $product_id, $stock);
$stmt->execute();

echo json_encode(["success" => "vajadzetu but ok"]);