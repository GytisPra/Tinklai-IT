<?php
class PartController
{
    private $partModel;

    public function __construct()
    {
        global $mysqli;
        $this->partModel = new Part($mysqli);
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
}
