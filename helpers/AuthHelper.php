<?php

class AuthHelper {
    public static function requireLogin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if the user is logged in
        if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
            header("location: index.php?route=login");
            exit;
        }

        // Session timeout logic (5 minutes = 300 seconds)
        $timeout_duration = 300; 
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
            session_unset();
            session_destroy();
            session_start();
            $_SESSION['timeout_message'] = "You have been logged out due to inactivity.";
            header("Location: index.php?route=login");
            exit;
        }

        // Update last activity time stamp
        $_SESSION['last_activity'] = time();
    }
}
?>
