<?php
session_start();

if (!isset($_SESSION["user_id"]) && $_SESSION["user_role"] != 2) {
    header("Location: /auth");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../main.css">
    <script src="../js/products.js" defer></script>
    <script src="worker.js" defer></script>
    <title>Worker panel</title>
</head>

<body>

    <div id="sidebar">
        <h2><i>Worker panel</i></h2>
        <div class="table-select-button" id="products-button" data-table-id="products-table-container" onclick="ToggleTable(this)">Products</div>
        <div class="table-select-button" id="orders-button" data-table-id="orders-table-container" onclick="ToggleTable(this)">Orders</div>
        <a id="logoutButton" href="/logout.php">Logout</a>
    </div>

    <div id="main-content">
        <div id="products-table-container" class="table-container">
            <table border="1" class="active">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Stock</th>
                        <th>Options</th>
                    </tr>
                </thead>

                <tbody id="products"></tbody>
            </table>

            <button id="new-product-btn" onclick="ToggleNewProductForm()">New</button>
        </div>
    </div>

    <div id="overlay-container">
        <div id="new-product-form" class="overlay">
            <input id="new-product-name" type="text" placeholder="Product name" />
            <input id="new-product-count" type="number" placeholder="Count" />
            <button onclick="AddNewProduct()">Add</button>
            <button onclick="ToggleNewProductForm()">Cancel</button>
        </div>
    </div>
</body>

</html>