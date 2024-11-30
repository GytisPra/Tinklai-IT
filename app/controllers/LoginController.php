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
                $_POST['password'] ?? ''
            );

            // If authentication is successful
            if ($result) {
                $_SESSION['user'] = $result;

                if ($isAjax) {
                    $redirectUrl = isset($_SESSION['redirect_after_login']) ? $_SESSION['redirect_after_login'] : '';
                    unset($_SESSION['redirect_after_login']);

                    header('Content-Type: application/json');
                    echo json_encode([
                        'authenticated' => true,
                        'success' => true,
                        'redirectTo' => $redirectUrl
                    ]);
                    exit;
                } else {
                    if (isset($_SESSION['redirect_after_login'])) {
                        $redirectUrl = $_SESSION['redirect_after_login'];
                        unset($_SESSION['redirect_after_login']); // Clear session variable
                        header("Location: $redirectUrl");
                    } else {
                        header("Location: /dashboard");
                    }
                    exit;
                }
            }

            // If authentication fails
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'authenticated' => false,
                    'success' => false,
                    'message' => 'Neteisingi prisijungimo duomenys',
                ]);
            } else {
                $_SESSION['login_error'] = "Neteisingi prisijungimo duomenys.";
                header("Location: /login");
            }
            exit;
        } catch (Exception $e) {
            if ($isAjax) {
                // AJAX error response
                header('Content-Type: application/json');
                http_response_code(400); // Bad Request
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage(),
                ]);
            } else {
                // Traditional form error handling
                $_SESSION['login_error'] = $e->getMessage();
                header("Location: /login");
            }
            exit;
        }
    }
}
