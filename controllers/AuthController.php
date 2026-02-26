<?php
class AuthController {
    
    // Default hardcoded credentials for demo
    private $valid_username = "admin";
    private $valid_password = "password123";

    public function login() {
        session_start();

        // Already logged in
        if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
            header("location: index.php?route=dashboard");
            exit;
        }

        $error = '';
        if (isset($_SESSION['timeout_message'])) {
            $error = $_SESSION['timeout_message'];
            unset($_SESSION['timeout_message']);
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if ($username === $this->valid_username && $password === $this->valid_password) {
                // Login successful
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['last_activity'] = time(); // Init timer
                header("Location: index.php?route=dashboard");
                exit;
            } else {
                // Login failed
                $error = "Invalid username or password.";
            }
        }

        // Load the login view
        require_once __DIR__ . '/../views/Login.php';
    }

    public function logout() {
        session_start();
        $_SESSION = array();
        session_destroy();
        header("location: index.php?route=login");
        exit;
    }
}
?>
