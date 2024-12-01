<?php

class User
{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function register($name, $lastname, $phone_number, $email, $username, $password, $role)
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
        if (empty($role)) {
            throw new Exception("role cannot be empty.");
        }

        // Hash the password before saving it

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $currDate = date('Y-m-d H:i:s');

        // Prepare and execute the SQL query to insert the user into the database
        $stmt = $this->mysqli->prepare(
            "INSERT INTO user (name, lastname, phone_number, email, username, password, role) 
            VALUES (?, ?, ?, ?, ?, ?, ?)"
        );

        // Check if the statement was prepared successfully
        if (!$stmt) {
            throw new Exception("Failed to prepare the query: " . $this->mysqli->error);
        }

        // Bind the parameters to the prepared statement
        $stmt->bind_param('ssssssi', $name, $lastname, $phone_number, $email, $username, $hashedPassword, $role);


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
        // Declare variables to avoid IDE warnings
        $id = null;
        $fetchedUsername = null;
        $hashedPassword = null;
        $role = null;

        // Prepare the SQL statement
        $stmt = $this->mysqli->prepare("SELECT id, username, password, role FROM user WHERE username = ?");
        if (!$stmt) {
            throw new Exception("Database query error: " . $this->mysqli->error);
        }

        // Bind and execute the query
        $stmt->bind_param("s", $username);
        $stmt->execute();

        // Bind result variables
        $stmt->bind_result($id, $fetchedUsername, $hashedPassword, $role);
        if ($stmt->fetch()) {
            // Check the password against the hash
            if ($hashedPassword && password_verify($password, $hashedPassword)) {
                $stmt->close();
                return [
                    'id' => $id,
                    'username' => $fetchedUsername,
                    'role' => $role
                ];
            }
        }

        // Close the statement and return false if authentication failed
        $stmt->close();
        return false;
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
