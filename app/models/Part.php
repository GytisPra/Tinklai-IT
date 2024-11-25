<?php
// app/models/Part.php

class Part
{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

    // Get base by ID
    public function getPartById($id)
    {
        $stmt = $this->mysqli->prepare("SELECT * WHERE id = ?");
        if (!$stmt) {
            die("Prepare failed: " . $this->mysqli->error);
        }

        $stmt->bind_param("i", $id); // 'i' for integer
        $stmt->execute();

        $result = $stmt->get_result();
        $partData = $result->fetch_assoc(); // Fetch as associative array

        $stmt->close();

        return $partData; // Return raw data (not JSON encoded)
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
}
