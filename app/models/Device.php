<?php
// models/Device.php

class Device
{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

    // Get device by ID
    public function getDeviceById($id)
    {
        $stmt = $this->mysqli->prepare("SELECT 
            b.id AS device_id, b.name AS device_name,
            ct.name  AS computer_type,
            p1.id AS motherboard,
            p2.id AS memory,
            p3.id AS storage,
            p4.id AS processor,
            p5.id AS graphics_card,
            p6.id AS cooling_option,
            p7.id AS screen,
            p8.id AS operating_system
            FROM device d
                LEFT JOIN computer_types ct ON d.type = ct.id_computer_types
                LEFT JOIN part p1 ON d.fk_motherboard_id = p1.id
                LEFT JOIN part p2 ON d.fk_memory_id = p2.id
                LEFT JOIN part p3 ON d.fk_storage_id = p3.id
                LEFT JOIN part p4 ON d.fk_processor_id = p4.id
                LEFT JOIN part p5 ON d.fk_graphics_card_id = p5.id
                LEFT JOIN part p6 ON d.fk_cooling_id = p6.id
                LEFT JOIN part p7 ON d.fk_screen_id = p7.id
                LEFT JOIN part p8 ON d.fk_os_id = p8.id
            WHERE d.id = ?");
        if (!$stmt) {
            die("Prepare failed: " . $this->mysqli->error);
        }

        $stmt->bind_param("i", $id); // 'i' for integer
        $stmt->execute();

        $result = $stmt->get_result();
        $deviceData = $result->fetch_assoc(); // Fetch as associative array

        $stmt->close();

        return $deviceData; // Return raw data (not JSON encoded)
    }

    // Delete device by ID
    public function deleteDeviceById($deviceId)
    {
        $query = "DELETE FROM device WHERE id = ?";
        $stmt = $this->mysqli->prepare($query);
        if (!$stmt) {
            die("Prepare failed: " . $this->mysqli->error);
        }
        $stmt->bind_param('i', $deviceId);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getAllDevices()
    {
        $stmt = $this->mysqli->prepare("SELECT 
            d.id AS device_id, d.name AS device_name, 
            u.id AS created_by_id,
            ct.name AS computer_type,
            p1.name AS motherboard,
            p2.name AS memory,
            p3.name AS storage,
            p4.name AS processor,
            p5.name AS graphics_card,
            p6.name AS cooling_option,
            p7.name AS screen,
            p8.name AS operating_system
            FROM device d
                LEFT JOIN computer_types ct ON d.type = ct.id_computer_types
                LEFT JOIN part p1 ON d.fk_motherboard_id = p1.id
                LEFT JOIN part p2 ON d.fk_memory_id = p2.id
                LEFT JOIN part p3 ON d.fk_storage_id = p3.id
                LEFT JOIN part p4 ON d.fk_processor_id = p4.id
                LEFT JOIN part p5 ON d.fk_graphics_card_id = p5.id
                LEFT JOIN part p6 ON d.fk_cooling_id = p6.id
                LEFT JOIN part p7 ON d.fk_screen_id = p7.id
                LEFT JOIN part p8 ON d.fk_os_id = p8.id
                LEFT JOIN user u ON d.fk_created_by = u.id
                ");

        if (!$stmt) {
            die("Prepare failed: " . $this->mysqli->error);
        }
        $stmt->execute();

        $result = $stmt->get_result();
        $deviceData = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        return $deviceData;
    }

    public function getAllUserDevices($userId)
    {
        $stmt = $this->mysqli->prepare("SELECT 
            d.id AS device_id, d.name AS device_name, 
            ct.name AS computer_type,
            p1.name AS motherboard,
            p2.name AS memory,
            p3.name AS storage,
            p4.name AS processor,
            p5.name AS graphics_card,
            p6.name AS cooling_option,
            p7.name AS screen,
            p8.name AS operating_system
            FROM device d
                LEFT JOIN computer_types ct ON d.type = ct.id_computer_types
                LEFT JOIN part p1 ON d.fk_motherboard_id = p1.id
                LEFT JOIN part p2 ON d.fk_memory_id = p2.id
                LEFT JOIN part p3 ON d.fk_storage_id = p3.id
                LEFT JOIN part p4 ON d.fk_processor_id = p4.id
                LEFT JOIN part p5 ON d.fk_graphics_card_id = p5.id
                LEFT JOIN part p6 ON d.fk_cooling_id = p6.id
                LEFT JOIN part p7 ON d.fk_screen_id = p7.id
                LEFT JOIN part p8 ON d.fk_os_id = p8.id
                LEFT JOIN user u ON d.fk_created_by = u.id
            WHERE u.id = ?");

        if (!$stmt) {
            die("Prepare failed: " . $this->mysqli->error);
        }
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $result = $stmt->get_result();
        $deviceData = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        return $deviceData;
    }


    public function create($name, $type, $motherboard_id, $memory_id, $storage_id, $processor_id, $graphics_card_id, $cooling_id, $screen_id, $os_id, $createdBy)
    {
        // Prepare and execute the SQL query to insert the user into the datadevice
        $stmt = $this->mysqli->prepare(
            "INSERT INTO `device`
            (`name`, `type`, `fk_created_by`, `fk_motherboard_id`, `fk_memory_id`, 
            `fk_storage_id`, `fk_processor_id`, `fk_graphics_card_id`, 
            `fk_cooling_id`, `fk_screen_id`, `fk_os_id`) 
            VALUES (?,?,?,?,?,?,?,?,?,?,?)"
        );

        // Check if the statement was prepared successfully
        if (!$stmt) {
            throw new Exception("Failed to prepare the query: " . $this->mysqli->error);
        }

        // Bind the parameters to the prepared statement
        $stmt->bind_param(
            'siiiiiiiiii',
            $name,
            $type,
            $createdBy,
            $motherboard_id,
            $memory_id,
            $storage_id,
            $processor_id,
            $graphics_card_id,
            $cooling_id,
            $screen_id,
            $os_id
        );


        if ($stmt->execute()) {
            return true; // Registration successful
        } else {
            throw new Exception("Error creating device.");
        }
    }
}
