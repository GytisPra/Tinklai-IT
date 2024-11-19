<?php
class LoginController
{
    private $userModel;

    public function __construct($userModel)
    {
        $this->userModel = $userModel;
    }

    // Display the registration form
    public function showLoginForm()
    {
        if (isUserLoggedIn()) {
            header("Location: /dashboard");
            exit;
        }

        include 'app/views/login.html';
    }

    public function processLoginForm()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get user input from the form
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($username)) {
                throw new Exception("Username cannot be empty.");
            }

            if (empty($password)) {
                throw new Exception("Password cannot be empty.");
            }

            try {
                $authResult = $this->userModel->authenticate($username, $password);
                if ($authResult) {
                    // Store user info in session
                    $_SESSION['user'] = $authResult;
                    header("Location: /dashboard");
                    exit;
                } else {
                    // Handle failed authentication
                    $_SESSION['error'] = "Invalid username or password";
                    header("Location: /login");
                    exit;
                }
            } catch (Exception $e) {
                // Log error and show a user-friendly message
                error_log($e->getMessage());
                $_SESSION['error'] = "An error occurred. Please try again later.";
                header("Location: /login");
                exit;
            }
        }
    }
}
