<?php
class BaseController
{
    private $baseModel;

    public function __construct()
    {
        global $mysqli;
        $this->baseModel = new Base($mysqli);
    }

    public function showBaseCreationForm()
    {
        include 'app/views/create-base.php';
    }

    public function getBaseData($query)
    {
        header('Content-Type: application/json');

        if (isset($query['base_id'])) {
            $baseId = intval($query['base_id']);
            $baseData = $this->baseModel->getBaseById($baseId);

            if ($baseData) {
                echo json_encode($baseData); // Return JSON data
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Base not found']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid base ID']);
        }
    }

    public function getAllBases()
    {
        $baseData = $this->baseModel->getAllBases();

        if ($baseData) {
            header('Content-Type: application/json');
            echo json_encode($baseData);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'No bases found']);
        }
    }

    public function processBaseCreationForm()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get user input from the form
            $name = $_POST['name'] ?? '';
            $type = 1; // TODO: CHANGE THIS
            $motherboard_id = intval($_POST['motherboard_id']) ?? '';
            $memory_id = intval($_POST['memory_id']) ?? '';
            $storage_id = intval($_POST['storage_id']) ?? '';
            $processor_id = intval($_POST['processor_id']) ?? '';
            $graphics_card_id = intval($_POST['graphics_card_id']) ?? '';
            $cooling_id = intval($_POST['cooling_id']) ?? '';
            $screen_id = intval($_POST['screen_id']) ?? '';
            $os_id = intval($_POST['os_id']) ?? '';

            try {
                // Register the user
                $result = $this->baseModel->create(
                    $name,
                    $type,
                    $motherboard_id,
                    $memory_id,
                    $storage_id,
                    $processor_id,
                    $graphics_card_id,
                    $cooling_id,
                    $screen_id,
                    $os_id
                );
                if ($result) {
                    header("Location: /dashboard");
                }
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
    }
}
