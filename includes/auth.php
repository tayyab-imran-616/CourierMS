<?php
require_once __DIR__ . '/config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function currentRole() {
    return $_SESSION['role'] ?? null;
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}
?>
