<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check login
if (!isset($_SESSION['principal_id'])) {
    header("Location: principal_login.php");
    exit();
}
?>
