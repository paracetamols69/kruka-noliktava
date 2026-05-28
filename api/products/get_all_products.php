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

if ($_SESSION["user_role"] == 0) {
    http_response_code(403);
    echo json_encode(["error" => "Insufficient permissions"]);
    exit;
}

$stmt = $conn->prepare("SELECT id, product_name, stock FROM products");
$stmt->execute();

$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode($products);
