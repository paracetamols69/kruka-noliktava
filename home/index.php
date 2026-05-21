<?php 
session_start();

if (!isset($_SESSION["user_id"])) {
    echo "No session.";
    exit;
}

if ($_SESSION["user_role"] == 3) {
    header("Location: /admin");
    exit;
}
?>