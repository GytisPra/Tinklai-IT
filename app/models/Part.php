<?php
// app/models/Part.php

class Part
{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function isPartAvailable($partId)
    {
        $stmt = $this->mysqli->prepare("SELECT 
            left_in_storage FROM part WHERE id = ?");

        if (!$stmt) {
            die("Prepare failed: " . $this->mysqli->error);
        }
        $stmt->bind_param("i", $partId);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();


        if (!$row || intval($row['left_in_storage']) <= 0) {
            return false;
        }

        return true;
    }

    public function checkPartsAvailability()
    {
        $partIds = json_decode(file_get_contents('php://input'), true);
        $unavailableParts = [];

        foreach ($partIds as $part) {
            $partId = intval($part['partValue']);
            if (!$this->isPartAvailable($partId)) {
                $unavailableParts[] = $part['partId'];
            }
        }

        echo json_encode([
            'unavailableParts' => $unavailableParts
        ]);
    }

    // Get base by ID
    public function getPartById($id)
    {
        $stmt = $this->mysqli->prepare("SELECT 
                p.id, p.name as name, pt.name as part_type, p.price, p.left_in_storage
                FROM part p
                    LEFT JOIN part_types pt ON p.part_type = pt.id_part_types
                WHERE p.id = ?");

        if (!$stmt) {
            die("Prepare failed: " . $this->mysqli->error);
        }

        $stmt->bind_param("i", $id); // 'i' for integer
        $stmt->execute();

        $result = $stmt->get_result();
        $partData = $result->fetch_assoc(); // Fetch as associative array

        $stmt->close();

        return $partData;
    }

    private function getPartId($partType)
    {
        $stmt = $this->mysqli->prepare("SELECT id_part_types FROM part_types WHERE name = ?");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->mysqli->error);
        }

        $stmt->bind_param("s", $partType);
        $stmt->execute();

        $result = $stmt->get_result();
        $partTypeData = $result->fetch_assoc();

        $stmt->close();

        if (!$partTypeData) {
            throw new Exception("Part type not found: " . $partType);
        }

        return $partTypeData['id_part_types'];
    }

    public function getPartsList($partType)
    {
        $partTypeId = $this->getPartId($partType);

        $stmt = $this->mysqli->prepare("SELECT * FROM part WHERE part_type = ?");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->mysqli->error);
        }

        $stmt->bind_param("i", $partTypeId);
        $stmt->execute();

        $result = $stmt->get_result();
        $parts = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        return $parts;
    }

    public function create($name, $price, $left_in_storage, $part_type)
    {
        // Prepare and execute the SQL query to insert the user into the database
        $stmt = $this->mysqli->prepare(
            "INSERT INTO `part`
            (`name`, `price`, `left_in_storage`, `part_type`) 
            VALUES (?,?,?,?)"
        );

        // Check if the statement was prepared successfully
        if (!$stmt) {
            throw new Exception("Failed to prepare the query: " . $this->mysqli->error);
        }

        // Bind the parameters to the prepared statement
        $stmt->bind_param(
            'siii',
            $name,
            $price,
            $left_in_storage,
            $part_type
        );


        if ($stmt->execute()) {
            return true; // Registration successful
        } else {
            throw new Exception("Error creating base.");
        }
    }

    public function getPartPriceById($partId)
    {
        $stmt = $this->mysqli->prepare("SELECT price FROM part WHERE id = ?");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->mysqli->error);
        }

        $stmt->bind_param("i", $partId);
        $stmt->execute();

        $result = $stmt->get_result();
        $partPrice = $result->fetch_assoc();

        $stmt->close();

        if (!$partPrice) {
            throw new Exception("Part not found ID: " . $partId);
        }

        return $partPrice;
    }

    private function getAllPartTypes()
    {
        $stmt = $this->mysqli->prepare(
            "SELECT * FROM `part_types`"
        );

        // Check if the statement was prepared successfully
        if (!$stmt) {
            throw new Exception("Failed to prepare the query: " . $this->mysqli->error);
        }

        $stmt->execute();

        $result = $stmt->get_result();
        $part_types = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        return $part_types;
    }

    public function getAllPartsList()
    {
        $partsByType = [];  // Array to store parts grouped by type
        $part_types = $this->getAllPartTypes();  // Get all part types

        foreach ($part_types as $part) {
            $partTypeId = $part["id_part_types"];
            $partName = $part["name"];

            // Prepare the query to fetch parts by type
            $stmt = $this->mysqli->prepare(
                "SELECT * FROM `part` WHERE part_type = ?"
            );

            $stmt->bind_param('i', $partTypeId);
            $stmt->execute();

            $result = $stmt->get_result();
            $parts = $result->fetch_all(MYSQLI_ASSOC);

            // Get the part type name for use as a key
            $partTypeName = $partName;

            // Group parts by part type name
            $partsByType[$partTypeName] = $parts;

            $stmt->close();
        }

        return $partsByType;
    }
}
