<?php
session_start();

if (!isset($_SESSION["user_id"]) && $_SESSION["user_role"] != 3) {
    header("Location: /auth");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <script src="admin.js" defer></script>
    <title>Admin panel</title>
</head>

<body>
    <a href="../logout.php">Logout</a>
    <div id="sidebar">
        <button onclick="ToggleUsersTable()">Users</button>
    </div>

    <div id="main-content">
        <table border="1" class="active">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Created at</th>
                    <th>Options</th>
                </tr>
            </thead>

            <tbody id="users"></tbody>
        </table>
    </div>
</body>
</html>