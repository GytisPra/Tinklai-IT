<?php
// index.php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include necessary files
require_once 'app/models/User.php';
require_once 'app/models/Base.php';
require_once 'app/models/Part.php';

require_once 'app/controllers/RegisterController.php';
require_once 'app/controllers/LoginController.php';
require_once 'app/controllers/LogoutController.php';
require_once 'app/controllers/DashboardController.php';
require_once 'app/controllers/DeviceController.php';
require_once 'app/controllers/BaseController.php';
require_once 'app/controllers/PartController.php';


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
$dashboardController = new DashboardController();
$logoutController = new LogoutController();
$deviceController = new DeviceController();
$baseController = new BaseController();
$partController = new PartController();



$request = parse_url(trim($_SERVER['REQUEST_URI'], '/'), PHP_URL_PATH); // Parse the URI path
$query = $_GET; // Capture query parameters like 'id'

$routes = [
    'register' => [$registerController, 'showRegisterForm'],
    'register/submit' => [$registerController, 'processRegisterForm'],
    'login' => [$loginController, 'showLoginForm'],
    'login/submit' => [$loginController, 'processLoginForm'],
    'logout' => [$logoutController, 'logout'],
    'dashboard' => [$dashboardController, 'showDashboard'],
    'create-device' => [$deviceController, 'showDeviceCreationForm'],
    'create-base' => [$baseController, 'showBaseCreationForm'],
    'create-base/submit' => [$baseController, 'processBaseCreationForm'],
    'get-base' => [$baseController, 'getBaseData'],
    'get-parts' => [$partController, 'getPartsList'],
    'get-base-list' => [$baseController, 'getAllBases'],
];

if (array_key_exists($request, $routes)) {
    call_user_func($routes[$request], $query);
} else if ($request === "index.php" || empty($request)) {
    header('Location: /dashboard');
} else {
    http_response_code(404);
    echo "404 Not Found";
}
