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
    <link rel="stylesheet" href="main.css">
    <script src="sorter.js" defer></script>
    <title>Sorter panel</title>
</head>

<body>

    <div id="sidebar">
        <h2><i>Sorter panel</i></h2>
        <button onclick="ToggleTable()">Shelves</button>
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
        </div>
    </div>
</body>

</html>