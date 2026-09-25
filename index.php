<?php
/**
 * School Digital Hub v2 - Homepage
 * Redirect automatico al login o alla dashboard
 */

session_start();

// Se loggato, redirect alla dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: backend/dashboard.php');
    exit;
}

// Altrimenti redirect al login
header('Location: login.html');
exit;
?>
