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

if (!isset($data["shelf_id"]) || !isset($data["product_id"]) || !isset($data["stock"])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing required fields"]);
    exit;
}

$shelf_id = intval($data["shelf_id"]);
$new_product_id = intval($data["product_id"]);
$new_stock = intval($data["stock"]);

if ($new_stock < 0) {
    http_response_code(400);
    echo json_encode(["error" => "Stock skaits nevar būt negatīvs!"]);
    exit;
}


$oldShelfStmt = $conn->prepare("SELECT product_id, stock FROM shelves WHERE id = ?");
$oldShelfStmt->bind_param("i", $shelf_id);
$oldShelfStmt->execute();
$oldShelf = $oldShelfStmt->get_result()->fetch_assoc();

if (!$oldShelf) {
    http_response_code(404);
    echo json_encode(["error" => "Plaukts netika atrasts"]);
    exit;
}

$old_product_id = intval($oldShelf['product_id']);
$old_stock = intval($oldShelf['stock']);


$prodStmt = $conn->prepare("SELECT stock FROM products WHERE id = ?");
$prodStmt->bind_param("i", $new_product_id);
$prodStmt->execute();
$newProductData = $prodStmt->get_result()->fetch_assoc();

if (!$newProductData) {
    http_response_code(404);
    echo json_encode(["error" => "Jaunais produkts netika atrasts"]);
    exit;
}

if ($old_product_id === $new_product_id) {
    $stock_difference = $new_stock - $old_stock;
} else {
    $stock_difference = $new_stock;
}


if ($newProductData['stock'] < $stock_difference) {
    http_response_code(400);
    echo json_encode(["error" => "Noliktavā nepietiek preču! Pieejams: " . $newProductData['stock']]);
    exit;
}

$stmt = $conn->prepare("UPDATE shelves SET product_id = ?, stock = ? WHERE id = ?");
$stmt->bind_param("iii", $new_product_id, $new_stock, $shelf_id);

if ($stmt->execute()) {
    
    if ($old_product_id === $new_product_id) {
        $updateProdStmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $updateProdStmt->bind_param("ii", $stock_difference, $new_product_id);
        $updateProdStmt->execute();
    } else {
        $returnOldStmt = $conn->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
        $returnOldStmt->bind_param("ii", $old_stock, $old_product_id);
        $returnOldStmt->execute();

        $takeNewStmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $takeNewStmt->bind_param("ii", $new_stock, $new_product_id);
        $takeNewStmt->execute();
    }

    echo json_encode(["success" => "all good nemiz"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Database error failed to update shelf"]);
}
?>