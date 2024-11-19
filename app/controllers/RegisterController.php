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
        include 'app/views/register.html';
    }

    // Handle the form submission
    public function processRegisterForm()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get user input from the form
            $name = $_POST['name'] ?? '';
            $lastname = $_POST['lastname'] ?? '';
            $username = $_POST['username'] ?? '';
            $phone_number = $_POST['phone_number'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Validate input
            if (empty($username)) {
                throw new Exception("Username cannot be empty.");
            }

            if (empty($lastname)) {
                throw new Exception("Lastname cannot be empty.");
            }
            if (empty($phone_number)) {
                throw new Exception("Phone_number cannot be empty.");
            }
            if (empty($email)) {
                throw new Exception("Email cannot be empty.");
            }
            if (empty($username)) {
                throw new Exception("Username cannot be empty.");
            }
            if (empty($password)) {
                throw new Exception("password cannot be empty.");
            }

            try {
                // Check if the username already exists
                if ($this->userModel->usernameExists($username)) {
                    echo "Username already taken!";
                    return;
                }

                // Register the user
                $this->userModel->register($name, $lastname, $phone_number, $email, $username, $password);
                echo "Registration successful!";
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
    }
}
