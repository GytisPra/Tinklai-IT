<?php
// models/Base.php

class Base
{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

    // Get base by ID
    public function getBaseById($id)
    {

        $stmt = $this->mysqli->prepare("SELECT 
            b.id AS base_id, b.name AS base_name,
            ct.name  AS computer_type,
            p1.name AS motherboard,
            p2.name AS memory,
            p3.name AS storage,
            p4.name AS processor,
            p5.name AS graphics_card,
            p6.name AS cooling_option,
            p7.name AS screen,
            p8.name AS operating_system
            FROM base b
                LEFT JOIN computer_types ct ON b.type = ct.id_computer_types
                LEFT JOIN part p1 ON b.fk_motherboard_id = p1.id
                LEFT JOIN part p2 ON b.fk_memory_id = p2.id
                LEFT JOIN part p3 ON b.fk_storage_id = p3.id
                LEFT JOIN part p4 ON b.fk_processor_id = p4.id
                LEFT JOIN part p5 ON b.fk_graphics_card_id = p5.id
                LEFT JOIN part p6 ON b.fk_cooling_id = p6.id
                LEFT JOIN part p7 ON b.fk_screen_id = p7.id
                LEFT JOIN part p8 ON b.fk_os_id = p8.id
            WHERE b.id = ?");
        if (!$stmt) {
            die("Prepare failed: " . $this->mysqli->error);
        }

        $stmt->bind_param("i", $id); // 'i' for integer
        $stmt->execute();

        $result = $stmt->get_result();
        $baseData = $result->fetch_assoc(); // Fetch as associative array

        $stmt->close();

        return $baseData; // Return raw data (not JSON encoded)
    }

    public function getAllBases()
    {
        $stmt = $this->mysqli->prepare("SELECT 
            b.id AS base_id, b.name AS base_name,
            ct.name  AS computer_type,
            p1.name AS motherboard,
            p2.name AS memory,
            p3.name AS storage,
            p4.name AS processor,
            p5.name AS graphics_card,
            p6.name AS cooling_option,
            p7.name AS screen,
            p8.name AS operating_system
            FROM base b
                LEFT JOIN computer_types ct ON b.type = ct.id_computer_types
                LEFT JOIN part p1 ON b.fk_motherboard_id = p1.id
                LEFT JOIN part p2 ON b.fk_memory_id = p2.id
                LEFT JOIN part p3 ON b.fk_storage_id = p3.id
                LEFT JOIN part p4 ON b.fk_processor_id = p4.id
                LEFT JOIN part p5 ON b.fk_graphics_card_id = p5.id
                LEFT JOIN part p6 ON b.fk_cooling_id = p6.id
                LEFT JOIN part p7 ON b.fk_screen_id = p7.id
                LEFT JOIN part p8 ON b.fk_os_id = p8.id
                ");

        if (!$stmt) {
            die("Prepare failed: " . $this->mysqli->error);
        }
        $stmt->execute();

        $result = $stmt->get_result();
        $baseData = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        return $baseData;
    }

    public function create($name, $type, $motherboard_id, $memory_id, $storage_id, $processor_id, $graphics_card_id, $cooling_id, $screen_id, $os_id)
    {
        if (empty($name)) {
            throw new Exception("Username cannot be empty.");
        }
        if (empty($type)) {
            throw new Exception("type cannot be empty.");
        }
        if (empty($motherboard_id)) {
            throw new Exception("motherboard_id cannot be empty.");
        }
        if (empty($memory_id)) {
            throw new Exception("memory_id cannot be empty.");
        }
        if (empty($storage_id)) {
            throw new Exception("storage_id cannot be empty.");
        }
        if (empty($processor_id)) {
            throw new Exception("processor_id cannot be empty.");
        }
        if (empty($graphics_card_id)) {
            throw new Exception("graphics_card_id cannot be empty.");
        }
        if (empty($cooling_id)) {
            throw new Exception("cooling_id cannot be empty.");
        }
        if (empty($screen_id)) {
            throw new Exception("screen_id cannot be empty.");
        }
        if (empty($os_id)) {
            throw new Exception("os_id cannot be empty.");
        }


        // Prepare and execute the SQL query to insert the user into the database
        $stmt = $this->mysqli->prepare(
            "INSERT INTO `base`
            (`name`, `type`, `fk_motherboard_id`, `fk_memory_id`, 
            `fk_storage_id`, `fk_processor_id`, `fk_graphics_card_id`, 
            `fk_cooling_id`, `fk_screen_id`, `fk_os_id`) 
            VALUES (?,?,?,?,?,?,?,?,?,?)"
        );

        // Check if the statement was prepared successfully
        if (!$stmt) {
            throw new Exception("Failed to prepare the query: " . $this->mysqli->error);
        }

        // Bind the parameters to the prepared statement
        $stmt->bind_param(
            'siiiiiiiii',
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


        if ($stmt->execute()) {
            return true; // Registration successful
        } else {
            throw new Exception("Error creating base.");
        }
    }
}
