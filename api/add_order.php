<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

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

$product_id = $data["product_id"];
$count = $data["count"];

$stmt1 = $conn->prepare("SELECT id, stock FROM shelves WHERE product_id = ?");
$stmt1->bind_param("i", $product_id);
$stmt1->execute();

$stmt1->bind_result($shelf_id, $stock);
$stmt1->fetch();
$stmt1->close();

if ($stock - $count < 0) {
    http_response_code(400);
    echo json_encode(["error" => "not enough items in shelves"]);
    exit;
}

$stmt2 = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
$stmt2->bind_param("ii", $count, $product_id);
$stmt2->execute();

$stmt3 = $conn->prepare("UPDATE shelves SET stock = stock - ? WHERE id = ?");
$stmt3->bind_param("ii", $count, $shelf_id);
$stmt3->execute();

$created_at = time();

$stmt4 = $conn->prepare("INSERT INTO orders (product_id, count, created_at) VALUES (?, ?, ?)");
$stmt4->bind_param("iii", $product_id, $count, $created_at);
$stmt4->execute();



echo json_encode(["success" => "vajadzetu but ok"]);

