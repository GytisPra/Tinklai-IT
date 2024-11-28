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
    'register' => [
        'handler' => [$registerController, 'showRegisterForm'],
        'roles' => [],
        'query_params' => []
    ],
    'register/submit' => [
        'handler' => [$registerController, 'processRegisterForm'],
        'roles' => [],
        'query_params' => []
    ],
    'login' => [
        'handler' => [$loginController, 'showLoginForm'],
        'roles' => [],
        'query_params' => []
    ],
    'login/submit' => [
        'handler' => [$loginController, 'processLoginForm'],
        'roles' => [],
        'query_params' => []
    ],
    'logout' => [
        'handler' => [$logoutController, 'logout'],
        'roles' => [],
        'query_params' => []
    ],
    'dashboard' => [
        'handler' => [$dashboardController, 'showDashboard'],
        'roles' => [],
        'query_params' => []
    ],
    'create-device' => [
        'handler' => [$deviceController, 'showDeviceCreationForm'],
        'roles' => [2],
        'query_params' => []
    ],
    'create-device/submit' => [
        'handler' => [$deviceController, 'processDeviceCreationForm'],
        'roles' => [2],
        'query_params' => []
    ],
    'create-part' => [
        'handler' => [$partController, 'showPartCreationForm'],
        'roles' => [2],
        'query_params' => []
    ],
    'create-part/submit' => [
        'handler' => [$partController, 'processPartCreationForm'],
        'roles' => [2],
        'query_params' => []
    ],
    'get-device' => [
        'handler' => [$deviceController, 'getDeviceData'],
        'roles' => [1, 2, 3],
        'query_params' => ['device_id']
    ],
    'get-parts' => [
        'handler' => [$partController, 'getPartsList'],
        'roles' => [1, 2],
        'query_params' => ['part_type']
    ],
    'get-all-parts' => [
        'handler' => [$partController, 'getAllPartsList'],
        'roles' => [1, 2, 3],
        'query_params' => []
    ],
    'get-part' => [
        'handler' => [$partController, 'getPartById'],
        'roles' => [1, 2],
        'query_params' => ['part_id']
    ],
    'check-parts-availability' => [
        'handler' => [$partController, 'checkPartsAvailability'],
        'roles' => [2],
        'query_params' => []
    ],
    'my-devices' => [
        'handler' => [$deviceController, 'showTechniciansDevices'],
        'roles' => [2],
        'query_params' => []
    ],
    'delete-device' => [
        'handler' => [$deviceController, 'deleteDeviceById'],
        'roles' => [2],
        'query_params' => []
    ],
    'edit-device' => [
        'handler' => [$deviceController, 'showDeviceEditForm'],
        'roles' => [2],
        'query_params' => ['device_id']
    ],
    'edit-device/update' => [
        'handler' => [$deviceController, 'processDeviceEdit'],
        'roles' => [2],
        'query_params' => []
    ],
    'assemble-device' => [
        'handler' => [$deviceController, 'showDeviceAssembleForm'],
        'roles' => [3],
        'query_params' => ['device_id']
    ],
    'assemble-device/assemble' => [
        'handler' => [$deviceController, 'processDeviceAssembly'],
        'roles' => [3],
        'query_params' => []
    ],
    'my-assemblies' => [
        'handler' => [$deviceController, 'showUserAssemblies'],
        'roles' => [3],
        'query_params' => []
    ],
    'assembly-edit' => [
        'handler' => [$deviceController, 'showAssemblyEditForm'],
        'roles' => [3],
        'query_params' => ['assembly_id']
    ],
    'assembly-edit/update' => [
        'handler' => [$deviceController, 'processAssemblyEdit'],
        'roles' => [3],
        'query_params' => []
    ],
    'get-part-price' => [
        'handler' => [$partController, 'getPartPrice'],
        'roles' => [3],
        'query_params' => ['part_id']
    ],
];

if (array_key_exists($request, $routes)) {
    $route = $routes[$request];
    $handler = $route['handler'];
    $allowedRoles = $route['roles'];
    $expectedQueryParams = $route['query_params'];

    // Validate query parameters
    $missingParams = array_diff($expectedQueryParams, array_keys($query));
    if (!empty($missingParams)) {
        http_response_code(400);
        echo "400 Bad Request: Missing required query parameters: " . implode(', ', $missingParams);
        exit;
    }

    // Check roles
    if (empty($allowedRoles) || (isUserLoggedIn() && isUserInRole($allowedRoles))) {
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
