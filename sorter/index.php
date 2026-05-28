<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_role"] != 1) {
    header("Location: /auth");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="sorter.css">
    <link rel="stylesheet" href="../main.css">
    <script src="../js/shelves.js" defer></script>
    <script src="sorter.js" defer></script>
    <title>Sorter panel</title>
</head>

<body>

    <div id="sidebar">
        <h2><i>Sorter panel</i></h2>
        <button id="shelves-button" class="table-select-button" onclick="ToggleTable(this)" data-table-id="shelves-table-container">Shelves</button>
        <a id="logoutButton" href="/logout.php">Logout</a>
    </div>

    <div id="main-content">
        <div id="shelves-table-container" class="table-container">
            <table border="1" class="active">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Product ID</th>
                        <th>Stock</th>
                    </tr>
                </thead>
    
                <tbody id="shelves"></tbody>
            </table>

            <button id="new-product-btn" onclick="ToggleNewShelfForm()">New</button>
        </div>

        <div id="overlay-container">
            <div id="new-shelf-form" class="overlay">
                <span class="close-btn" onclick="ToggleNewShelfForm()">&times;</span>

                <input id="new-shelf-id" type="number" placeholder="Shelf ID" />

                <select class="selectBox" id="shelf-product-select" required>
                    <option value="">Izvēlies produktu</option>
                </select>

                <input id="shelf-stock" type="number" placeholder="Count" min="0"/>

                <button onclick="AddNewShelf()">Add</button>
            </div>

            <div id="edit-shelf-form" class="overlay">
                <span class="close-btn" onclick="ToggleEditShelfForm()">&times;</span>
                <input type="hidden" id="edit-shelf-id" />
                
                <select class="selectBox" id="edit-shelf-product-select" required>
                    <option value="">Izvēlies produktu</option>
                </select>
                
                <input id="edit-shelf-stock" type="number" placeholder="Count" min="0"/>
                
                <button onclick="SaveShelfData()">Saglabāt</button>
            </div>
        </div>
    </div>
</body>

</html>