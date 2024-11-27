<?php
// index.php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include necessary files
require_once 'app/models/User.php';
require_once 'app/models/Device.php';
require_once 'app/models/Part.php';

require_once 'app/controllers/RegisterController.php';
require_once 'app/controllers/LoginController.php';
require_once 'app/controllers/LogoutController.php';
require_once 'app/controllers/DashboardController.php';
require_once 'app/controllers/DeviceController.php';
require_once 'app/controllers/DeviceController.php';
require_once 'app/controllers/PartController.php';


require_once 'config/config.php';
require_once 'app/utils/utils.php';


// Create a mysqli connection for database
$mysqli = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);

// Check for connection errors
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
$userModel = new User($mysqli);

$registerController = new RegisterController($userModel);
$loginController = new LoginController($userModel);
$dashboardController = new DashboardController();
$logoutController = new LogoutController();
$deviceController = new DeviceController();
$partController = new PartController();

$request = parse_url(trim($_SERVER['REQUEST_URI'], '/'), PHP_URL_PATH); // Parse the URI path
$query = $_GET; // Capture query parameters like 'id'

$routes = [
    'register' => ['handler' => [$registerController, 'showRegisterForm'], 'roles' => []],                    // Accessible to everyone
    'register/submit' => ['handler' => [$registerController, 'processRegisterForm'], 'roles' => []],          // Accessible to everyone
    'login' => ['handler' => [$loginController, 'showLoginForm'], 'roles' => []],                             // Accessible to everyone
    'login/submit' => ['handler' => [$loginController, 'processLoginForm'], 'roles' => []],                   // Accessible to everyone
    'logout' => ['handler' => [$logoutController, 'logout'], 'roles' => []],                                  // Accessible to everyone
    'dashboard' => ['handler' => [$dashboardController, 'showDashboard'], 'roles' => []],                     // Accessible to everyone
    //'create-device' => ['handler' => [$deviceController, 'showDeviceCreationForm'], 'roles' => [2]],          // Technician only
    'create-device' => ['handler' => [$deviceController, 'showDeviceCreationForm'], 'roles' => [2]],                // Technician only
    'create-device/submit' => ['handler' => [$deviceController, 'processDeviceCreationForm'], 'roles' => [2]],      // Technician only
    'create-part' => ['handler' => [$partController, 'showPartCreationForm'], 'roles' => [2]],                // Technician only
    'create-part/submit' => ['handler' => [$partController, 'processPartCreationForm'], 'roles' => [2]],      // Technician only
    'get-base' => ['handler' => [$deviceController, 'getDeviceData'], 'roles' => [1, 2]],                         // Admin and Technician
    'get-parts' => ['handler' => [$partController, 'getPartsList'], 'roles' => [1, 2]],                       // Admin and Technician
    'get-part' => ['handler' => [$partController, 'getPartById'], 'roles' => [1, 2]],                         // Admin and Technician
    'get-device-list' => ['handler' => [$deviceController, 'getAllDevices'], 'roles' => [1, 2]],                    // Admin and Technician
    'check-parts-availability' => ['handler' => [$partController, 'checkPartsAvailability'], 'roles' => [2]], // Technician
    'my-devices' => ['handler' => [$deviceController, 'showTechniciansDevices'], 'roles' => [2]], // Technician
    'delete-device' => ['handler' => [$deviceController, 'deleteDeviceById'], 'roles' => [2]], // Technician
    'edit-devices' => ['handler' => [$deviceController, 'showDeviceEditForm'], 'roles' => [2]], // Technician
];

if (array_key_exists($request, $routes)) {
    $route = $routes[$request];
    $handler = $route['handler'];
    $allowedRoles = $route['roles'];

    if (empty($allowedRoles) || isUserLoggedIn() && isUserInRole($allowedRoles)) {
        call_user_func($handler, $query);
    } else {
        http_response_code(403);
        echo "403 Forbidden: You do not have access to this route.";
    }
} else if ($request === "index.php" || empty($request)) {
    header('Location: /dashboard');
} else {
    http_response_code(404);
    echo "404 Not Found";
}
