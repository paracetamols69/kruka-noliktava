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
    <link rel="stylesheet" href="worker.css">
    <script src="worker.js" defer></script>
    <title>Worker panel</title>
</head>

<body>

    <div id="sidebar">
        <a href="/logout.php">Logout</a>
        <button onclick="ToggleTable()">Products</button>
    </div>

    <div id="main-content">
        <table border="1" class="active">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Stock</th>
                </tr>
            </thead>

            <tbody id="products"></tbody>
        </table>
    </div>
</body>

</html>