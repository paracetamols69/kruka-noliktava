<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");
session_start();
require "../../includes/db.php";

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

$product_id = intval($data["product_id"]);
$count = intval($data["count"]);


if ($count <= 0) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid order count"]);
    exit;
}

$totalStockStmt = $conn->prepare("SELECT SUM(stock) AS total_stock FROM shelves WHERE product_id = ?");
$totalStockStmt->bind_param("i", $product_id);
$totalStockStmt->execute();
$totalStockResult = $totalStockStmt->get_result()->fetch_assoc();
$total_available = $totalStockResult['total_stock'] ? intval($totalStockResult['total_stock']) : 0;
$totalStockStmt->close();

if ($total_available < $count) {
    http_response_code(400);
    echo json_encode(["error" => "Not enough items in shelves. Available in total: " . $total_available]);
    exit;
}

$shelvesStmt = $conn->prepare("SELECT id, stock FROM shelves WHERE product_id = ? AND stock > 0 ORDER BY id ASC");
$shelvesStmt->bind_param("i", $product_id);
$shelvesStmt->execute();
$shelvesResult = $shelvesStmt->get_result();

$remaining_to_deduct = $count;

$conn->begin_transaction();

try {
    while ($shelf = $shelvesResult->fetch_assoc()) {
        if ($remaining_to_deduct <= 0) {
            break;
        }

        $shelf_id = intval($shelf['id']);
        $shelf_stock = intval($shelf['stock']);

        if ($shelf_stock >= $remaining_to_deduct) {
            $deduct_from_this_shelf = $remaining_to_deduct;
            $remaining_to_deduct = 0;
        } else {
            $deduct_from_this_shelf = $shelf_stock;
            $remaining_to_deduct -= $shelf_stock;
        }

        $updateShelf = $conn->prepare("UPDATE shelves SET stock = stock - ? WHERE id = ?");
        $updateShelf->bind_param("ii", $deduct_from_this_shelf, $shelf_id);
        $updateShelf->execute();
        $updateShelf->close();
    }
    $shelvesStmt->close();


    $stmt2 = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
    $stmt2->bind_param("ii", $count, $product_id);
    $stmt2->execute();
    $stmt2->close();

    $created_at = (new DateTime('now', new DateTimeZone('Europe/Riga')))->format('Y-m-d H:i:s');
    $stmt4 = $conn->prepare("INSERT INTO orders (product_id, count, created_at) VALUES (?, ?, ?)");
    $stmt4->bind_param("iis", $product_id, $count, $created_at);
    $stmt4->execute();
    $stmt4->close();

    $conn->commit();

    echo json_encode(["success" => "Order added"]);

} catch (Exception $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(["error" => "Transaction failed: " . $e->getMessage()]);
}
?>