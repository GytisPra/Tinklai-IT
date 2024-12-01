<?php
class PartController
{
    private $partModel;

    public function __construct()
    {
        global $mysqli;
        $this->partModel = new Part($mysqli);
    }

    public function showPartCreationForm()
    {
        include 'app/views/create-part.php';
    }

    public function getPartById($query)
    {
        header('Content-Type: application/json');

        if (isset($query['part_id'])) {
            $partId = intval($query['part_id']);
            $partData = $this->partModel->getPartById($partId);

            if ($partData) {
                echo json_encode($partData); // Return JSON data
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Part not found']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid part ID']);
        }
    }

    public function checkPartsAvailability()
    {
        try {
            // Get the raw POST data
            $rawInput = file_get_contents('php://input');
            $partIds = json_decode($rawInput, true);
            // Validate input
            if ($partIds === null) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid JSON']);
                exit;
            }

            // Use the model method to check parts availability
            $unavailableParts = [];

            foreach ($partIds as $part) {
                $partId = intval($part['partValue']);
                if (!$this->partModel->isPartAvailable($partId)) {
                    $unavailableParts[] = $part['partId'];
                }
            }

            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'unavailableParts' => $unavailableParts
            ]);
            exit;
        } catch (Exception $e) {
            // Handle any unexpected errors
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Server error: ' . $e->getMessage()
            ]);
            exit;
        }
    }

    public function getPartsList($query)
    {
        header('Content-Type: application/json');

        if (isset($query['part_type'])) {
            $partType = $query['part_type'];
            $partsData = $this->partModel->getPartsList($partType);

            if ($partsData) {
                echo json_encode($partsData); // Return JSON data
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Parts not found']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid part type']);
        }
    }

    public function getPartPrice($query)
    {
        header('Content-Type: application/json');

        if (isset($query['part_id'])) {
            $partId = $query['part_id'];
            $partsPrice = $this->partModel->getPartPriceById($partId);

            if ($partsPrice) {
                echo json_encode($partsPrice); // Return JSON data
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Part not found']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid part id']);
        }
    }

    public function getAllPartsList()
    {
        header('Content-Type: application/json');

        $partsData = $this->partModel->getAllPartsList();

        if ($partsData) {
            echo json_encode($partsData); // Return JSON data
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Parts not found']);
        }
    }

    public function processPartCreationForm()
    {
        // Ensure it's an AJAX request
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        try {
            $result = $this->partModel->create(
                $_POST['name'],
                intval($_POST['price']),
                intval($_POST['amount']),
                intval($_POST['part_type'])
            );

            // Prepare response
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Detalė sėkmingai sukurta',
                    // Optional: include any additional data
                ]);

                exit;
            } else {
                // Traditional form submission
                header("Location: /dashboard");
                exit;
            }
        } catch (Exception $e) {
            if ($isAjax) {
                header('Content-Type: application/json');
                http_response_code(400); // Bad Request
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
                exit;
            }
        }
    }
}
