<?php
function validate_username($username) {
    $errors = [];

    $username = trim($username);

    if (empty($username)) {
        $errors[] = "Username is required";
        return $errors;
    }

    if (strlen($username) < 3 || strlen($username) > 15) {
        $errors[] = "Username must be 3–15 characters long";
    }

    if (!preg_match('/^[a-zA-Z0-9_.]+$/', $username)) {
        $errors[] = "Username can only contain letters, numbers, underscores, and periods";
    }

    return $errors;
}

function validate_password($password) {
    $errors = [];

    if (empty($password)) {
        $errors[] = "Password is required";
        return $errors;
    }

    if (strlen($password) < 8) {
        $errors[] = "Password must be atleast 8 characters";
    }

    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Password must contain at least one uppercase letter";
    }

    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = "Password must contain at least one lowercase letter";
    }

    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Password must contain at least one number";
    }

    if (!preg_match('/[\W_]/', $password)) {
        $errors[] = "Password must contain at least one special character";
    }

    return $errors;
}
?>