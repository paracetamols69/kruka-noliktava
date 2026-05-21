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