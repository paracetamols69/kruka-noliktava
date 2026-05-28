<?php 
session_start();

if (!isset($_SESSION["user_id"])) {
    echo "No session.";
    exit;
}

if ($_SESSION["user_role"] == 1) {
    header("Location: /sorter");
    exit;
}

if ($_SESSION["user_role"] == 2) {
    header("Location: /worker");
    exit;
}

if ($_SESSION["user_role"] == 3) {
    header("Location: /admin");
    exit;
}
?>

<h1>Tev vēl nav piešķirts role.</h1>

<h3>Sazinies ar Kruku</h3>