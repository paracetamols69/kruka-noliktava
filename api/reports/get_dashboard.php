<?php
header("Content-Type: application/json");
session_start();
require "../../includes/db.php";
require "../../includes/check_session.php";


if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != 3) {
    http_response_code(403);
    echo json_encode(["error" => "Pieeja liegta"]);
    exit;
}

$report = [];


$stmt = $conn->prepare("SELECT id FROM shelves WHERE stock = 0");
$stmt->execute();
$report['empty_shelves'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);


$stmt = $conn->prepare("SELECT id, stock FROM shelves WHERE stock > 0 AND stock <= 3 ORDER BY stock ASC");
$stmt->execute();
$report['low_shelves'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);


$stmt = $conn->prepare("SELECT id, product_name FROM products WHERE stock = 0");
$stmt->execute();
$report['out_of_stock_products'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);


$stmt = $conn->prepare("SELECT product_name, stock FROM products WHERE stock > 0 AND stock <= 10 ORDER BY stock ASC");
$stmt->execute();
$report['low_stock_products'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);


$stmt = $conn->prepare("
    SELECT p.product_name, SUM(o.count) AS total_sold 
    FROM orders o 
    JOIN products p ON o.product_id = p.id 
    GROUP BY o.product_id 
    ORDER BY total_sold DESC 
    LIMIT 5
");
$stmt->execute();
$report['top_products'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$report['stats']['total_products'] = $conn->query("SELECT COUNT(*) FROM products")->fetch_row()[0];
$report['stats']['total_shelves'] = $conn->query("SELECT COUNT(*) FROM shelves")->fetch_row()[0];
$report['stats']['total_orders'] = $conn->query("SELECT COUNT(*) FROM orders")->fetch_row()[0];

$stmt->close();

echo json_encode($report);
?>