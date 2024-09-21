<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Destroy session if active
if (session_status() == PHP_SESSION_ACTIVE) {
    session_destroy();
}
header('location: index.php');
?>
