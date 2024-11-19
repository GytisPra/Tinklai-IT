<?php
class LogoutController
{
    public function __construct() {}

    public function logout()
    {
        if (!isUserLoggedIn()) {
            header("Location: /dashboard");
            exit;
        }
        session_unset(); // Clear all session variables
        session_destroy(); // Destroy the session
        header("Location: /dashboard");
        exit;
    }
}
