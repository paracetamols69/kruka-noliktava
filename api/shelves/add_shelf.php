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

$shelf_id = intval($data["shelf_id"]);
$product_id = intval($data["product_id"]);
$stock = intval($data["stock"]);


$checkStmt = $conn->prepare("SELECT stock FROM products WHERE id = ?");
$checkStmt->bind_param("i", $product_id);
$checkStmt->execute();
$prodResult = $checkStmt->get_result()->fetch_assoc();

if (!$prodResult) {
    http_response_code(404);
    echo json_encode(["error" => "Product not found"]);
    exit;
}

if ($prodResult['stock'] < $stock) {
    http_response_code(400);
    echo json_encode(["error" => "Noliktavā nav tik daudz preču! Pieejams: " . $prodResult['stock']]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO shelves (id, product_id, stock) VALUES (?, ?, ?)");
$stmt->bind_param("iii", $shelf_id, $product_id, $stock);

if ($stmt->execute()) {
    $updateStmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
    $updateStmt->bind_param("ii", $stock, $product_id);
    $updateStmt->execute();

    echo json_encode(["success" => "vajadzetu but ok"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Database error: failed to add shelf"]);
}
?>