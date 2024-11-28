<?php
class DeviceController
{
    private $deviceModel;
    private $partModel;

    public function __construct()
    {
        global $mysqli;
        $this->deviceModel = new Device($mysqli);
        $this->partModel = new Part($mysqli);
    }

    public function showDeviceCreationForm()
    {
        include 'app/views/device.php';
    }

    public function showTechniciansDevices()
    {
        include 'app/views/my-devices.php';
    }

    public function showUserAssemblies()
    {
        $userId = intval(getUserId());

        $assemblyData = $this->deviceModel->getUserAssemblies($userId);

        if ($assemblyData) {
            $this->renderView('my-assemblies', ['assemblyData' => $assemblyData]);
        } else {
            $this->renderView('my-assemblies', []);
        }
    }

    public function showAssemblyEditForm($query)
    {
        // Check if 'device_id' is provided in the query parameters
        if (isset($query['assembly_id'])) {
            $assemblyId = intval($query['assembly_id']);
            $assemblyData = $this->deviceModel->getAssemblyById($assemblyId);

            $deviceData = $this->deviceModel->getDeviceById($assemblyData['device_id']);

            if ($deviceData) {
                // Pass the device data to the view
                $this->renderView('assemble-device', ['device' => $deviceData, 'assembly' => $assemblyData]);
            } else {
                // Handle case where the device is not found
                http_response_code(404);
                echo "Assembly not found. Return to <a href='dashboard'>dashboard</a>";
            }
        } else {
            // Handle case where 'device_id' is not provided
            http_response_code(400);
            echo "Invalid request: Device ID is required.";
        }
    }

    public function showDeviceEditForm($query)
    {
        // Check if 'device_id' is provided in the query parameters
        if (isset($query['device_id'])) {
            $deviceId = intval($query['device_id']);

            // Fetch the device data using the model
            $deviceData = $this->deviceModel->getDeviceById($deviceId);

            if ($deviceData) {
                // Pass the device data to the view
                $this->renderView('device', ['device' => $deviceData]);
            } else {
                // Handle case where the device is not found
                http_response_code(404);
                echo "Device not found. Return to <a href='dashboard'>dashboard</a>";
            }
        } else {
            // Handle case where 'device_id' is not provided
            http_response_code(400);
            echo "Invalid request: Device ID is required.";
        }
    }

    public function showDeviceAssembleForm($query)
    {
        // Check if 'device_id' is provided in the query parameters
        if (isset($query['device_id'])) {
            $deviceId = intval($query['device_id']);

            // Fetch the device data using the model
            $deviceData = $this->deviceModel->getDeviceById($deviceId);

            if ($deviceData) {
                // Pass the device data to the view
                $this->renderView('assemble-device', ['device' => $deviceData]);
            } else {
                // Handle case where the device is not found
                http_response_code(404);
                echo "Device not found. Return to <a href='dashboard'>dashboard</a>";
            }
        } else {
            // Handle case where 'device_id' is not provided
            http_response_code(400);
            echo "Invalid request: Device ID is required.";
        }
    }

    public function renderView($viewName, $data = [])
    {
        // Extract data array to individual variables
        extract($data);

        // Include the view file
        include 'app/views/' . $viewName . '.php';
    }

    public function getDeviceData($query)
    {
        header('Content-Type: application/json');

        if (isset($query['device_id'])) {
            $deviceId = intval($query['device_id']);
            $deviceData = $this->deviceModel->getDeviceById($deviceId);

            if ($deviceData) {
                echo json_encode($deviceData); // Return JSON data
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Device not found']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid device ID']);
        }
    }

    public function getDeviceById($query)
    {
        header('Content-Type: application/json');

        if (isset($query['device_id'])) {
            $deviceId = intval($query['device_id']);
            $deviceData = $this->deviceModel->getDeviceById($deviceId);

            if ($deviceData) {
                echo json_encode($deviceData); // Return JSON data
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Device not found']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid device ID']);
        }
    }

    public function getAllDevices()
    {
        $deviceData = $this->deviceModel->getAllDevices();

        if ($deviceData) {
            return $deviceData;
        } else {
            echo 'error No devices found';
        }
    }

    public function deleteDeviceById()
    {
        // Check if the request is a POST and contains the necessary data
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get raw POST data
            $data = json_decode(file_get_contents("php://input"));

            // Check if deviceId is provided
            if (isset($data->deviceId)) {
                // Try to delete the device
                $deviceId = $data->deviceId;
                $result = $this->deviceModel->deleteDeviceById($deviceId);

                // Respond accordingly
                if ($result) {
                    // Send success response
                    echo json_encode(['status' => 'success']);
                } else {
                    http_response_code(400);
                    echo json_encode(['status' => 'error', 'message' => 'Device could not be deleted']);
                }
            } else {
                echo "ERROR";
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'No deviceId provided']);
            }
        } else {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
        }
    }

    public function getAllUserDevices($userId)
    {
        if ($userId) {
            $deviceData = $this->deviceModel->getAllUserDevices($userId);

            if ($deviceData) {
                return $deviceData; // Return JSON data
            }
        } else {
            echo 'error Invalid device ID not found';
        }
    }

    public function processDeviceCreationForm()
    {
        // Ensure it's an AJAX request
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        // Validation and error handling
        $errorMessages = [];

        try {
            // Input validation
            $name = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8');
            $price = isset($_POST['price']) ? floatval(str_replace(',', '.', $_POST['price'])) : 0.0; // Handle commas
            $selectedParts = $_POST['selectedParts'] ?? [];

            // Check if at least one part is selected
            if (empty($selectedParts)) {
                throw new Exception("Turi būti pasirinktas bent vienas komponentas.");
            }

            $createdBy = getUserId(); // Assuming this function returns the current user's ID

            // Attempt to create the device
            $deviceData = $this->deviceModel->create(
                $name,
                $price,
                $createdBy
            );

            $deviceId = $deviceData['id'];
            foreach ($selectedParts as $partId) {
                try {
                    $this->deviceModel->linkDevicePart($deviceId, $partId);
                } catch (Exception $e) {
                    if ($isAjax) {
                        header('Content-Type: application/json');
                        http_response_code(400); // Bad Request
                        echo json_encode([
                            'success' => false,
                            'message' => $e->getMessage()
                        ]);
                        exit;
                    } else {
                        // Traditional error handling (for regular form submission)
                        $errorMessages[] = $e->getMessage();
                        $this->renderView('device', [
                            'errorMessages' => $errorMessages,
                            'formData' => $_POST
                        ]);
                    }
                }
            }

            // Prepare response
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Įrenginys sėkmingai sukurtas',
                    'device_id' => $deviceId // Return the new device's ID
                ]);
                exit;
            } else {
                // Traditional form submission
                header("Location: /dashboard");
                exit;
            }
        } catch (Exception $e) {
            // Handle errors
            if ($isAjax) {
                header('Content-Type: application/json');
                http_response_code(400); // Bad Request
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
                exit;
            } else {
                // Traditional error handling (for regular form submission)
                $errorMessages[] = $e->getMessage();
                $this->renderView('device', [
                    'errorMessages' => $errorMessages,
                    'formData' => $_POST
                ]);
            }
        }
    }


    public function processDeviceEdit()
    {
        // Ensure it's an AJAX request
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        // Validation and error handling
        $errorMessages = [];

        try {
            // Input validation
            $name = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8');
            $price = isset($_POST['price']) ? floatval(str_replace(',', '.', $_POST['price'])) : 0.0; // Handle commas
            $deviceId = $_POST['device_id'] ?? '';
            $selectedParts = $_POST['selectedParts'] ?? [];

            // Check if at least one part is selected
            if (empty($selectedParts)) {
                throw new Exception("Turi būti pasirinktas bent vienas komponentas.");
            }

            // Attempt to create the device
            $this->deviceModel->update(
                $name,
                $price,
                $selectedParts,
                $deviceId
            );

            // Prepare response
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Įrenginys sėkmingai atnaujintas',
                    'device_id' => $deviceId // Return the new device's ID
                ]);
                exit;
            } else {
                // Traditional form submission
                header("Location: /dashboard");
                exit;
            }
        } catch (Exception $e) {
            // Handle errors
            if ($isAjax) {
                header('Content-Type: application/json');
                http_response_code(400); // Bad Request
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
                exit;
            } else {
                // Traditional error handling (for regular form submission)
                $errorMessages[] = $e->getMessage();
                $this->renderView('device', [
                    'errorMessages' => $errorMessages,
                    'formData' => $_POST
                ]);
            }
        }
    }

    public function processAssemblyEdit()
    {
        // Ensure it's an AJAX request
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        // Validation and error handling
        $errorMessages = [];

        try {
            $assemblyId = $_POST['assembly_id'] ?? '';
            $name = $_POST['name'] ?? '';
            $processor_id = $_POST['processor_id'] ?? '';
            $motherboard_id = $_POST['motherboard_id'] ?? '';
            $screen_id = $_POST['screen_id'] ?? '';
            $memory_id = $_POST['memory_id'] ?? '';
            $graphics_card_id = $_POST['graphics_card_id'] ?? '';
            $storage_id = $_POST['storage_id'] ?? '';
            $cooling_id = $_POST['cooling_id'] ?? '';
            $os_id = $_POST['os_id'] ?? '';

            // Attempt to update the assembly
            $this->deviceModel->updateAssembly(
                $assemblyId,
                $name,
                $processor_id,
                $motherboard_id,
                $screen_id,
                $memory_id,
                $graphics_card_id,
                $storage_id,
                $cooling_id,
                $os_id
            );

            // Prepare response
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Komplektas sėkmingai atnaujintas',
                    'assembly_id' => $assemblyId // Return the new device's ID
                ]);
                exit;
            } else {
                // Traditional form submission
                header("Location: /dashboard");
                exit;
            }
        } catch (Exception $e) {
            // Handle errors
            if ($isAjax) {
                header('Content-Type: application/json');
                http_response_code(400); // Bad Request
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
                exit;
            } else {
                // Traditional error handling (for regular form submission)
                $errorMessages[] = $e->getMessage();
                $this->renderView('assemble-device', [
                    'errorMessages' => $errorMessages,
                    'formData' => $_POST
                ]);
            }
        }
    }

    public function processDeviceAssembly()
    {
        // Ensure it's an AJAX request
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        // Validation and error handling
        $errorMessages = [];

        try {
            $deviceId = $_POST['device_id'] ?? '';
            $assemblyName = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? '';
            $processor_id = $_POST['processor_id'] ?? '';
            $motherboard_id = $_POST['motherboard_id'] ?? '';
            $screen_id = $_POST['screen_id'] ?? '';
            $memory_id = $_POST['memory_id'] ?? '';
            $graphics_card_id = $_POST['graphics_card_id'] ?? '';
            $storage_id = $_POST['storage_id'] ?? '';
            $cooling_id = $_POST['cooling_id'] ?? '';
            $os_id = $_POST['os_id'] ?? '';
            $belongsTo = getUserId(); // Assuming this function returns the current user's ID

            //Attempt to create the device
            $assemblyData = $this->deviceModel->createUserAssembly(
                $belongsTo,
                $assemblyName,
                $price,
                $deviceId,
                $processor_id,
                $motherboard_id,
                $screen_id,
                $memory_id,
                $graphics_card_id,
                $storage_id,
                $cooling_id,
                $os_id
            );

            // Prepare response
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Komplektas sėkmingai sukurtas',
                    'assembly_id' => $assemblyData['id'] // Return the new device's ID
                ]);
                exit;
            } else {
                // Traditional form submission
                header("Location: /dashboard");
                exit;
            }
        } catch (Exception $e) {
            // Handle errors
            if ($isAjax) {
                header('Content-Type: application/json');
                http_response_code(400); // Bad Request
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
                exit;
            } else {
                // Traditional error handling (for regular form submission)
                $errorMessages[] = $e->getMessage();
                $this->renderView('device', [
                    'errorMessages' => $errorMessages,
                    'formData' => $_POST
                ]);
            }
        }
    }

    public function getUserAssemblies($userId) {}
}
