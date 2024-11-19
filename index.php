<?php
// index.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include necessary files
require_once 'app/models/User.php';
require_once 'app/controllers/RegisterController.php';
require_once 'app/controllers/LoginController.php';
require_once 'config/config.php';
require_once 'app/utils/utils.php';


// Create a mysqli connection for database
$mysqli = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);

// Check for connection errors
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Create a User model instance
$userModel = new User($mysqli);

// Create a RegisterController instance
$registerController = new RegisterController($userModel);
$loginController = new LoginController($userModel);

// Routing logic (simplified)
$request = trim($_SERVER['REQUEST_URI'], '/');

$routes = [
    'register' => [$registerController, 'showRegisterForm'],
    'register/submit' => [$registerController, 'processRegisterForm'],
    'login' => [$loginController, 'showLoginForm'],
    'login/submit' => [$loginController, 'processLoginForm'],
];

if (array_key_exists($request, $routes)) {
    call_user_func($routes[$request]);
} else {
    http_response_code(404);
    echo "404 Not Found";
}
