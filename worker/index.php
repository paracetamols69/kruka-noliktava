<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != 2) {
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
    <script src="../js/orders.js" defer></script>
    <script src="worker.js" defer></script>
    
    <title>Worker panel</title>
</head>

<body>
    <div id="toast-container">
        
    </div>

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

        <div id="orders-table-container" class="table-container">
            <table border="1" class="active">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product ID</th>
                        <th>Count</th>
                        <th>Status</th>
                        <th>Created at</th>
                        <th>Options</th>
                    </tr>
                </thead>

                <tbody id="orders"></tbody>
            </table>

            <button id="new-product-btn" onclick="ToggleNewOrderForm()">New</button>
        </div>
    </div>

    <div id="overlay-container">
        <div id="new-product-form" class="overlay">
                <span class="close-btn" onclick="ToggleNewProductForm()">&times;</span>
                <input id="new-product-name" type="text" placeholder="Product name" />
                <input id="new-product-count" type="number" placeholder="Count" />
                <button onclick="AddNewProduct()">Add</button>
            </div>

            <div id="edit-product-form" class="overlay">
                <span class="close-btn" onclick="ToggleEditProductForm()">&times;</span>
                <input type="hidden" id="edit-product-id" />
                <input id="edit-product-name" type="text" placeholder="Product name" />
                <input id="edit-product-count" type="number" placeholder="Count" />
                <button onclick="SaveProductData()">Saglabāt</button>
            </div>



            <div id="new-order-form" class="overlay">
                <span class="close-btn" onclick="ToggleNewOrderForm()">&times;</span>

                <select class="selectBox" id="new-order-product-select" required>
                    <option value="">Izvēlies produktu</option>
                </select>

                <input id="new-order-count" type="number" placeholder="Count" />
                <button onclick="AddNewOrder()">Add</button>
            </div>

            <div id="edit-order-form" class="overlay">
                <span class="close-btn" onclick="ToggleEditOrderForm()">&times;</span>

                <input type="hidden" id="edit-order-id" />
                <select class="selectBox" id="edit-order-product-select" required>
                    <option value="">Izvēlies preci</option>
                </select>

                <select class="selectBox" id="edit-order-status-select" required>
                    <option value="0">Reģistrēts</option>
                    <option value="1">Apstrādāts</option>
                    <option value="2">Nosūtīts</option>
                    <option value="3">Saņemts</option>
                </select>

                <input id="edit-order-count" type="number" placeholder="Count" />
                <button onclick="SaveOrderData()">Add</button>
            </div>
    </div>
</body>

</html>