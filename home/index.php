<?php 
session_start();

if (!isset($_SESSION["user_id"])) {
    echo "No session.";
    exit;
}

echo "Welcome" . $_SESSION["username"];

?>