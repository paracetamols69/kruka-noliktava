<?php
// includes/check_session.php

if (isset($_SESSION["user_id"])) {
    $current_uid = intval($_SESSION["user_id"]);

    $checkUserStmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
    $checkUserStmt->bind_param("i", $current_uid);
    $checkUserStmt->execute();
    $userResult = $checkUserStmt->get_result()->fetch_assoc();
    $checkUserStmt->close();

    // Ja lietotājs vairs neeksistē vai viņa loma ir mainīta
    if (!$userResult || intval($userResult['role']) !== intval($_SESSION["user_role"])) {
        session_unset();
        session_destroy();
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        // Atdodam oficiālo "Sesija beigusies" statusu un uzreiz apstājamies
        http_response_code(401);
        exit; 
    }
}
?>