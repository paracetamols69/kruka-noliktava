<?php
header("Content-Type: application/json");
session_start();
require "../../includes/db.php";
require "../../includes/check_session.php";

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

$order_id = $data["order_id"];
$product_id = $data["product_id"];
$count = $data["new_count"];
$status = $data["new_status"];

if ($count <= 0) {
    echo json_encode(["error" => "Cannot make empty or negative order"]);
    exit;
} 

if ($status < 0 || $status > 3) {
    echo json_encode(["error" => "Invalid order status"]);
    exit;
}

$stmt = $conn->prepare("UPDATE orders SET count = ?, status = ?, product_id = ? WHERE id = ?");
$stmt->bind_param("iiii", $count, $status, $product_id, $order_id);
$stmt->execute();

echo json_encode(["success" => "Order modified"]);



