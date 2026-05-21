<?php

$conn = new mysqli("localhost", "root", "", "kruks");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}