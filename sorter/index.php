<?php
session_start();

if (!isset($_SESSION["user_id"]) && $_SESSION["user_role"] != 1) {
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
    <script src="sorter.js" defer></script>
    <title>Sorter panel</title>
</head>

<body>
    <a href="/logout.php">Logout</a>
</body>

</html>