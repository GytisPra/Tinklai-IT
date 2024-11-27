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
        include 'app/views/create-device.php';
    }

    public function showTechniciansDevices()
    {
        include 'app/views/my-devices.php';
    }

    public function showDeviceEditForm()
    {
        include 'app/views/edit-device.php';
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

    public function getAllDevices()
    {
        $deviceData = $this->deviceModel->getAllDevices();

        if ($deviceData) {
            header('Content-Type: application/json');
            echo json_encode($deviceData);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'No devices found']);
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
            $name = $_POST['name'] ?? '';
            $type = $_POST['computerType'] ?? '';
            if (empty($name)) {
                throw new Exception("Pavadinimas yra privalomas");
            }

            // Validate all required fields
            $requiredFields = [
                'motherboard_id',
                'memory_id',
                'storage_id',
                'processor_id',
                'graphics_card_id',
                'cooling_id',
                'screen_id',
                'os_id'
            ];

            foreach ($requiredFields as $field) {
                if (empty($_POST[$field]) || !is_numeric($_POST[$field])) {
                    throw new Exception("Neteisingai pasirinkta " . $field);
                }
            }

            $createdBy = getUserId();


            // Attempt to create device
            $result = $this->deviceModel->create(
                $name,
                $type,
                $_POST['motherboard_id'],
                $_POST['memory_id'],
                $_POST['storage_id'],
                $_POST['processor_id'],
                $_POST['graphics_card_id'],
                $_POST['cooling_id'],
                $_POST['screen_id'],
                $_POST['os_id'],
                $createdBy
            );

            // Prepare response
            if ($isAjax && $result) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Įrenginys sėkmingai sukurtas',
                    // Optional: include any additional data
                ]);
                exit;
            } elseif ($result) {
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
                // Traditional error handling
                $errorMessages[] = $e->getMessage();
                $this->renderView('create-device', [
                    'errorMessages' => $errorMessages,
                    'formData' => $_POST
                ]);
            }
        }
    }
}
