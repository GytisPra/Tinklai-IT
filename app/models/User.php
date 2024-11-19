<?php

class User
{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

    // Method to register a user
    public function register($name, $lastname, $phone_number, $email, $username, $password)
    {
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

        // Hash the password before saving it

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $currDate = date('Y-m-d H:i:s');

        echo $hashedPassword . "<br>";

        // Prepare and execute the SQL query to insert the user into the database
        $stmt = $this->mysqli->prepare(
            "INSERT INTO user (name, lastname, phone_number, email, username, password, createdAt, updatedAt) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );

        // Check if the statement was prepared successfully
        if (!$stmt) {
            throw new Exception("Failed to prepare the query: " . $this->mysqli->error);
        }

        // Bind the parameters to the prepared statement
        $stmt->bind_param('ssssssss', $name, $lastname, $phone_number, $email, $username, $hashedPassword, $currDate, $currDate);


        if ($stmt->execute()) {
            return true; // Registration successful
        } else {
            throw new Exception("Error registering user.");
        }
    }

    // Method to check if a username exists
    public function usernameExists($username)
    {
        $count = 0;

        $stmt = $this->mysqli->prepare("SELECT COUNT(*) FROM user WHERE username = ?");
        $stmt->bind_param("s", $username); // 's' for string
        $stmt->execute();

        $stmt->bind_result($count);
        $stmt->fetch();

        return $count > 0;
    }

    // Method to authenticate a user (login)
    public function authenticate($username, $password)
    {
        $hash = '';

        // Fetch the stored hashed password from the database
        $stmt = $this->mysqli->prepare("SELECT password FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($hash);
        $stmt->fetch();
        $stmt->close();

        if (password_verify($password, $hash)) {
            return true; // Authentication successful
        } else {
            return false; // Authentication failed
        }
    }


    // Get user by ID
    public function getUserById($id)
    {
        $stmt = $this->mysqli->prepare("SELECT * FROM user WHERE id = ?");
        $stmt->bind_param("i", $id); // 'i' for integer
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
