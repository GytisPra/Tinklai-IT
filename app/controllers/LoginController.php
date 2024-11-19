<?php
// /app/controllers/RegisterController.php

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
                if ($this->userModel->authenticate($username, $password)) {
                    echo "Authenticated successfully!";
                } else {
                    echo "Incorrect password or username!";
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
    }
}
