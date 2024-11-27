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
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        try {
            $result = $this->userModel->authenticate(
                $_POST['username'] ?? '',
                $_POST['password'] ?? '',
            );

            // Prepare response
            if ($isAjax && $result) {
                header('Content-Type: application/json');
                echo json_encode([
                    'authenticated' => true,
                    'success' => true,
                ]);
                $_SESSION['user'] = $result;
                exit;
            } elseif (!$result) {
                header('Content-Type: application/json');
                echo json_encode([
                    'authenticated' => false,
                    'success' => false,
                ]);
                exit;
            } else {
                // Traditional form submission
                header("Location: /dashboard");
                exit;
            }
        } catch (Exception $e) {
            if ($isAjax) {
                header('Content-Type: application/json');
                http_response_code(400); // Bad Request
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
                exit;
            }
        }
    }
}
