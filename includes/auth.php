<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requireLogin() {
    if (empty($_SESSION['admin_id'])) {
        header('Location: /qr-attendance/login.php');
        exit;
    }
}
