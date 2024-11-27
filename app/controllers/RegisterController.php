<?php
// /app/controllers/RegisterController.php

class RegisterController
{
    private $userModel;

    public function __construct($userModel)
    {
        $this->userModel = $userModel;
    }

    // Display the registration form
    public function showRegisterForm()
    {
        if (isUserLoggedIn()) {
            header("Location: /dashboard");
        }
        include 'app/views/register.html';
    }

    // Handle the form submission
    public function processRegisterForm()
    {
        // Ensure it's an AJAX request
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        try {
            $result = $this->userModel->register(
                $_POST['name'] ?? '',
                $_POST['lastname'] ?? '',
                $_POST['phone_number'] ?? '',
                $_POST['email'] ?? '',
                $_POST['username'] ?? '',
                $_POST['password'] ?? '',
                3
            );

            // Prepare response
            if ($isAjax && $result) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Vartotojas sėkmingai sukurtas',
                    // Optional: include any additional data
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
