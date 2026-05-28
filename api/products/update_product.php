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

if (!isset($data["product_id"]) || !isset($data["product_name"]) || !isset($data["count"])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing required fields"]);
    exit;
}

$product_id = intval($data["product_id"]);
$product_name = trim($data["product_name"]);
$count = intval($data["count"]);

if (empty($product_name)) {
    http_response_code(400);
    echo json_encode(["error" => "Produkta nosaukums nevar būt tukšs!"]);
    exit;
}

if ($count < 0) {
    http_response_code(400);
    echo json_encode(["error" => "Produkta skaits nevar būt negatīvs!"]);
    exit;
}

$stmt = $conn->prepare("UPDATE products SET product_name = ?, stock = ? WHERE id = ?");
$stmt->bind_param("sii", $product_name, $count, $product_id);

try {
    $stmt->execute();
    echo json_encode(["success" => "Product updated"]);
} catch (mysqli_sql_exception $e) {
    
    if ($e->getCode() === 1062) {
        http_response_code(409);
        echo json_encode(["error" => "Database conflict: entry with same product name already exists"]);
        exit;
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Unknown database error"]);
        exit;
    }
}
?>