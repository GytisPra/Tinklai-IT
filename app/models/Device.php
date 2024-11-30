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
        $query = "
        SELECT
            device.id AS device_id,
            device.name AS device_name,
            device.construction_cost AS device_cost,
            GROUP_CONCAT(CASE WHEN part_types.name= 'motherboard' THEN part.id END) AS motherboard_ids,
            GROUP_CONCAT(CASE WHEN part_types.name= 'motherboard' THEN part.name END) AS motherboard_names,
            GROUP_CONCAT(CASE WHEN part_types.name= 'memory' THEN part.id END) AS memory_ids,
            GROUP_CONCAT(CASE WHEN part_types.name= 'memory' THEN part.name END) AS memory_names,
            GROUP_CONCAT(CASE WHEN part_types.name= 'storage' THEN part.id END) AS storage_ids,
            GROUP_CONCAT(CASE WHEN part_types.name= 'storage' THEN part.name END) AS storage_names,
            GROUP_CONCAT(CASE WHEN part_types.name= 'processor' THEN part.id END) AS processor_ids,
            GROUP_CONCAT(CASE WHEN part_types.name= 'processor' THEN part.name END) AS processor_names,
            GROUP_CONCAT(CASE WHEN part_types.name= 'graphics_card' THEN part.id END) AS graphics_card_ids,
            GROUP_CONCAT(CASE WHEN part_types.name= 'graphics_card' THEN part.name END) AS graphics_card_names,
            GROUP_CONCAT(CASE WHEN part_types.name= 'cooling' THEN part.id END) AS cooling_ids,
            GROUP_CONCAT(CASE WHEN part_types.name= 'cooling' THEN part.name END) AS cooling_names,
            GROUP_CONCAT(CASE WHEN part_types.name= 'screen' THEN part.id END) AS screen_ids,
            GROUP_CONCAT(CASE WHEN part_types.name= 'screen' THEN part.name END) AS screen_names,
            GROUP_CONCAT(CASE WHEN part_types.name= 'os' THEN part.id END) AS os_ids,
            GROUP_CONCAT(CASE WHEN part_types.name= 'os' THEN part.name END) AS os_names
        FROM
            device
        JOIN
            device_parts ON device.id = device_parts.fk_device_id
        JOIN
            part ON device_parts.fk_part_id = part.id
        JOIN
            part_types ON part.part_type = part_types.id_part_types 
        WHERE
            device.id = ?
        GROUP BY
            device.id
        ";

        $stmt = $this->mysqli->prepare($query);
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
        // Start a transaction to ensure atomic deletion
        $this->mysqli->begin_transaction();

        try {
            // Delete parts associated with the device from the `device_parts` table
            $stmt = $this->mysqli->prepare("DELETE FROM `device_parts` WHERE `fk_device_id` = ?");

            if (!$stmt) {
                throw new Exception("Failed to prepare query for device parts deletion: " . $this->mysqli->error);
            }

            // Bind device ID and execute deletion of parts
            $stmt->bind_param('i', $deviceId);
            if (!$stmt->execute()) {
                throw new Exception("Error deleting parts from device_parts.");
            }

            // Delete the device itself from the `device` table
            $stmt = $this->mysqli->prepare("DELETE FROM `device` WHERE `id` = ?");

            if (!$stmt) {
                throw new Exception("Failed to prepare query for device deletion: " . $this->mysqli->error);
            }

            // Bind device ID and execute the deletion of the device
            $stmt->bind_param('i', $deviceId);
            if (!$stmt->execute()) {
                throw new Exception("Error deleting device.");
            }

            // Step 3: Commit the transaction if all operations are successful
            $this->mysqli->commit();

            return true;
        } catch (Exception $e) {
            // Rollback transaction if something went wrong
            $this->mysqli->rollback();

            // Optionally, log the error
            error_log($e->getMessage());

            return false;
        }
    }

    public function getAssemblyById($assemblyId)
    {
        $query = "
            SELECT
                *
            FROM
                user_assembly
            WHERE
                id = ?
            ";

        $stmt = $this->mysqli->prepare($query);
        if (!$stmt) {
            die("Prepare failed: " . $this->mysqli->error);
        }

        $stmt->bind_param("i", $assemblyId); // 'i' for integer
        $stmt->execute();
        $result = $stmt->get_result();
        $assemblyData = $result->fetch_assoc();

        $stmt->close();

        $totalPrice = $this->getTotalAssemblyPrice($assemblyId);
        $assemblyData['total_price'] = $totalPrice;

        return $assemblyData;
    }


    public function getAllDevices($sortOrder)
    {
        $query = "
        SELECT
            device.id AS device_id,
            device.name AS device_name,
            device.construction_cost AS device_cost,
            GROUP_CONCAT(CASE WHEN part_types.name= 'motherboard' THEN part.id END) AS motherboard_ids,
            GROUP_CONCAT(CASE WHEN part_types.name= 'memory' THEN part.id END) AS memory_ids,
            GROUP_CONCAT(CASE WHEN part_types.name= 'storage' THEN part.id END) AS storage_ids,
            GROUP_CONCAT(CASE WHEN part_types.name= 'processor' THEN part.id END) AS processor_ids,
            GROUP_CONCAT(CASE WHEN part_types.name= 'graphics_card' THEN part.id END) AS graphics_card_ids,
            GROUP_CONCAT(CASE WHEN part_types.name= 'cooling' THEN part.id END) AS cooling_ids,
            GROUP_CONCAT(CASE WHEN part_types.name= 'screen' THEN part.id END) AS screen_ids,
            GROUP_CONCAT(CASE WHEN part_types.name= 'os' THEN part.id END) AS os_ids
        FROM
            device
        JOIN
            device_parts ON device.id = device_parts.fk_device_id
        JOIN
            part ON device_parts.fk_part_id = part.id
        JOIN
            part_types ON part.part_type = part_types.id_part_types 
        GROUP BY
            device.id
        ";

        match ($sortOrder) {
            1 => $query .= " ORDER BY device.construction_cost DESC",
            2 => $query .= " ORDER BY device.construction_cost ASC",
        };

        $stmt = $this->mysqli->prepare($query);

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
        $query = "
        SELECT
            device.id AS device_id,
            device.name AS device_name,
            device.construction_cost AS device_cost,
            GROUP_CONCAT(CASE WHEN part_types.name= 'motherboard' THEN part.id END) AS motherboard_ids,
            GROUP_CONCAT(CASE WHEN part_types.name= 'memory' THEN part.id END) AS memory_ids,
            GROUP_CONCAT(CASE WHEN part_types.name= 'storage' THEN part.id END) AS storage_ids,
            GROUP_CONCAT(CASE WHEN part_types.name= 'processor' THEN part.id END) AS processor_ids,
            GROUP_CONCAT(CASE WHEN part_types.name= 'graphics_card' THEN part.id END) AS graphics_card_ids,
            GROUP_CONCAT(CASE WHEN part_types.name= 'cooling' THEN part.id END) AS cooling_ids,
            GROUP_CONCAT(CASE WHEN part_types.name= 'screen' THEN part.id END) AS screen_ids,
            GROUP_CONCAT(CASE WHEN part_types.name= 'os' THEN part.id END) AS os_ids
        FROM
            device
        JOIN
            device_parts ON device.id = device_parts.fk_device_id
        JOIN
            part ON device_parts.fk_part_id = part.id
        JOIN
            part_types ON part.part_type = part_types.id_part_types 
        WHERE
            device.fk_created_by = ?
        GROUP BY
            device.id
        ";

        $stmt = $this->mysqli->prepare($query);

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


    public function create($name, $price, $createdBy)
    {
        // Prepare and execute the SQL query to insert the device into the device table
        $stmt = $this->mysqli->prepare(
            "INSERT INTO `device`
        (`name`, `construction_cost`, `fk_created_by`) 
        VALUES (?, ?, ?)"
        );

        // Check if the statement was prepared successfully
        if (!$stmt) {
            throw new Exception("Failed to prepare the query: " . $this->mysqli->error);
        }

        // Bind the parameters to the prepared statement
        $stmt->bind_param(
            'sdi',
            $name,
            $price,
            $createdBy
        );

        // Execute the statement
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $deviceId = $this->mysqli->insert_id;

            $deviceData = [
                'id' => $deviceId,
                'name' => $name,
                'price' => $price,
                'fk_created_by' => $createdBy
            ];

            return $deviceData;
        } else {
            throw new Exception("Error creating device.");
        }
    }

    public function linkDevicePart($deviceId, $partId)
    {
        // Prepare the SQL query to insert the device-part link into the device_parts table
        $stmt = $this->mysqli->prepare(
            "INSERT INTO `device_parts` (`fk_device_id`, `fk_part_id`) VALUES (?, ?)"
        );

        // Check if the statement was prepared successfully
        if (!$stmt) {
            throw new Exception("Failed to prepare the query: " . $this->mysqli->error);
        }

        // Bind the parameters to the prepared statement
        $stmt->bind_param('ii', $deviceId, $partId);

        // Execute the statement
        $stmt->execute();

        // Check if the device-part link was created successfully
        if ($stmt->affected_rows > 0) {
            return true; // Successfully linked device and part
        } else {
            throw new Exception("Error linking device and part.");
        }
    }

    public function linkDeviceAssemblyParts($deviceId, $partId)
    {
        // Prepare the SQL query to insert the device-part link into the device_parts table
        $stmt = $this->mysqli->prepare(
            "INSERT INTO `device_assembly_parts` (`fk_device_id`, `fk_part_id`) VALUES (?, ?)"
        );

        // Check if the statement was prepared successfully
        if (!$stmt) {
            throw new Exception("Failed to prepare the query: " . $this->mysqli->error);
        }

        // Bind the parameters to the prepared statement
        $stmt->bind_param('ii', $deviceId, $partId);

        // Execute the statement
        $stmt->execute();

        // Check if the device-part link was created successfully
        if ($stmt->affected_rows > 0) {
            return true; // Successfully linked device and part
        } else {
            throw new Exception("Error linking assembly and part.");
        }
    }


    public function update($name, $price, $selectedParts, $deviceId)
    {
        // Start transaction to ensure atomicity
        $this->mysqli->begin_transaction();

        try {
            // Step 1: Update the device's name
            $stmt = $this->mysqli->prepare(
                "UPDATE `device` SET `name` = ?, `construction_cost` = ? WHERE `id` = ?"
            );

            if (!$stmt) {
                throw new Exception("Failed to prepare the query: " . $this->mysqli->error);
            }

            // Bind parameters
            $stmt->bind_param('sdi', $name, $price, $deviceId);

            // Execute the update query
            if (!$stmt->execute()) {
                throw new Exception("Error updating device name.");
            }

            // Step 2: Remove old parts associated with the device
            $stmt = $this->mysqli->prepare(
                "DELETE FROM `device_parts` WHERE `fk_device_id` = ?"
            );

            if (!$stmt) {
                throw new Exception("Failed to prepare the delete query: " . $this->mysqli->error);
            }

            // Bind the device ID
            $stmt->bind_param('i', $deviceId);

            // Execute the delete query to remove old parts
            if (!$stmt->execute()) {
                throw new Exception("Error removing old parts.");
            }

            // Step 3: Insert the new selected parts into the device_parts table
            $stmt = $this->mysqli->prepare(
                "INSERT INTO `device_parts` (`fk_device_id`, `fk_part_id`) VALUES (?, ?)"
            );

            if (!$stmt) {
                throw new Exception("Failed to prepare the insert query: " . $this->mysqli->error);
            }

            // Insert each selected part into the device_parts table
            foreach ($selectedParts as $partId) {
                // Bind the device ID and part ID
                $stmt->bind_param('ii', $deviceId, $partId);

                // Execute the insert query for each selected part
                if (!$stmt->execute()) {
                    throw new Exception("Error inserting part into device_parts.");
                }
            }

            // Commit the transaction to make all changes permanent
            $this->mysqli->commit();

            return true; // Update successful
        } catch (Exception $e) {
            // Rollback the transaction if something goes wrong
            $this->mysqli->rollback();

            throw new Exception("Error updating device: " . $e->getMessage());
        }
    }

    public function createUserAssembly(
        $belongsTo,
        $name,
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
    ) {
        // Prepare and execute the SQL query to insert the device into the device table
        $stmt = $this->mysqli->prepare(
            "INSERT INTO `user_assembly`
                (`fk_belongs_to`,
                `name`,
                `price`,
                `device_id`,
                `processor_id`, 
                `motherboard_id`,
                `screen_id`,
                `memory_id`,
                `graphics_card_id`,
                `storage_id`,
                `cooling_id`,
                `os_id`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        // Check if the statement was prepared successfully
        if (!$stmt) {
            throw new Exception("Failed to prepare the query: " . $this->mysqli->error);
        }

        // Bind the parameters to the prepared statement
        $stmt->bind_param(
            'isdiiiiiiiii',
            $belongsTo,
            $name,
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

        // Execute the statement
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $assemblyId = $this->mysqli->insert_id;

            $deviceData = [
                'id' => $assemblyId,
                'name' => $name,
                'price' => $price,
                'device_id' => $deviceId,
                'belongs_to' => $belongsTo,
                'processor_id' => $processor_id,
                'motherboard_id' => $motherboard_id,
                'screen_id' => $screen_id,
                'memory_id' => $memory_id,
                'graphics_card_id' => $graphics_card_id,
                'storage_id' => $storage_id,
                'cooling_id' => $cooling_id,
                'os_id' => $os_id
            ];

            return $deviceData;
        } else {
            throw new Exception("Error creating assembly.");
        }
    }

    public function updateAssembly(
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
    ) {
        // Prepare and execute the SQL query to update the assembly record
        $stmt = $this->mysqli->prepare(
            "UPDATE `user_assembly` SET
                `name` = ?,
                `processor_id` = ?, 
                `motherboard_id` = ?,
                `screen_id` = ?,
                `memory_id` = ?,
                `graphics_card_id` = ?,
                `storage_id` = ?,
                `cooling_id` = ?,
                `os_id` = ?
             WHERE `id` = ?"
        );

        // Check if the statement was prepared successfully
        if (!$stmt) {
            throw new Exception("Failed to prepare the query: " . $this->mysqli->error);
        }

        // Log the query and parameters for debugging
        error_log("SQL: UPDATE `user_assembly` SET processor_id = ?, motherboard_id = ?, screen_id = ?, memory_id = ?, graphics_card_id = ?, storage_id = ?, cooling_id = ?, os_id = ? WHERE id = ?");
        error_log("Params: $processor_id, $motherboard_id, $screen_id, $memory_id, $graphics_card_id, $storage_id, $cooling_id, $os_id, $assemblyId");

        // Bind the parameters to the prepared statement
        $stmt->bind_param(
            'siiiiiiiii',
            $name,
            $processor_id,
            $motherboard_id,
            $screen_id,
            $memory_id,
            $graphics_card_id,
            $storage_id,
            $cooling_id,
            $os_id,
            $assemblyId
        );

        // Execute the statement
        $stmt->execute();

        // Log affected rows for debugging
        error_log("Affected Rows: " . $stmt->affected_rows);

        // Check if any rows were affected by the update
        if ($stmt->affected_rows > 0) {
            // Return the updated device data
            $assemblyData = [
                'id' => $assemblyId,
                'processor_id' => $processor_id,
                'motherboard_id' => $motherboard_id,
                'screen_id' => $screen_id,
                'memory_id' => $memory_id,
                'graphics_card_id' => $graphics_card_id,
                'storage_id' => $storage_id,
                'cooling_id' => $cooling_id,
                'os_id' => $os_id
            ];

            return $assemblyData;
        } elseif ($this->mysqli->error) {
            $error_message = $this->mysqli->error; // Get the last error message
            throw new Exception("Error updating assembly: " . $error_message);
        }
    }



    public function getUserAssemblies($userId)
    {
        $query = "
    SELECT
        *
    FROM
        user_assembly
    WHERE
        fk_belongs_to = ?
    ";

        $stmt = $this->mysqli->prepare($query);
        if (!$stmt) {
            die("Prepare failed: " . $this->mysqli->error);
        }

        $stmt->bind_param("i", $userId); // 'i' for integer
        $stmt->execute();

        $result = $stmt->get_result();
        $assemblyData = $result->fetch_all(MYSQLI_ASSOC); // Fetch as associative array

        $stmt->close();

        // Now add the total price for each assembly
        foreach ($assemblyData as &$assembly) {
            $assembly['total_price'] = $this->getTotalAssemblyPrice($assembly['id']);
        }

        return $assemblyData; // Return the assembly data with the total price added
    }

    public function deleteAssembly($assemblyId)
    {
        // Start a transaction to ensure consistency
        $this->mysqli->begin_transaction();

        try {
            // Delete related orders
            $orderQuery = "DELETE FROM `order` WHERE assembly_id = ?";
            $orderStmt = $this->mysqli->prepare($orderQuery);
            if (!$orderStmt) {
                throw new Exception("Prepare failed for order deletion: " . $this->mysqli->error);
            }
            $orderStmt->bind_param("i", $assemblyId);
            $orderStmt->execute();
            $orderStmt->close();

            // Delete the assembly
            $assemblyQuery = "DELETE FROM user_assembly WHERE id = ?";
            $assemblyStmt = $this->mysqli->prepare($assemblyQuery);
            if (!$assemblyStmt) {
                throw new Exception("Prepare failed for assembly deletion: " . $this->mysqli->error);
            }
            $assemblyStmt->bind_param("i", $assemblyId);
            $assemblyStmt->execute();
            $assemblyStmt->close();

            // Commit the transaction
            $this->mysqli->commit();
            return true;
        } catch (Exception $e) {
            // Roll back the transaction in case of error
            $this->mysqli->rollback();
            throw $e;
        }
    }

    public function getTotalAssemblyPrice($assemblyId)
    {
        $query = "
            SELECT
                p1.price AS motherboard_price,
                p2.price AS processor_price,
                p3.price AS screen_price,
                p4.price AS memory_price,
                p5.price AS graphics_card_price,
                p6.price AS storage_price,
                p7.price AS cooling_price,
                p8.price AS os_price
            FROM
                user_assembly us
            LEFT JOIN
                part p1 ON us.motherboard_id = p1.id
            LEFT JOIN
                part p2 ON us.processor_id = p2.id
            LEFT JOIN
                part p3 ON us.screen_id = p3.id
            LEFT JOIN
                part p4 ON us.memory_id = p4.id
            LEFT JOIN
                part p5 ON us.graphics_card_id = p5.id
            LEFT JOIN
                part p6 ON us.storage_id = p6.id
            LEFT JOIN
                part p7 ON us.cooling_id = p7.id
            LEFT JOIN
                part p8 ON us.os_id = p8.id
            WHERE
                us.id = ?
            ";

        $stmt = $this->mysqli->prepare($query);
        if (!$stmt) {
            die("Prepare failed: " . $this->mysqli->error);
        }

        // Corrected variable binding
        $stmt->bind_param("i", $assemblyId); // 'i' for integer
        $stmt->execute();

        $result = $stmt->get_result();
        $assemblyData = $result->fetch_assoc(); // Fetch as associative array (only one row is expected)

        $stmt->close();

        // Calculate the total price
        $totalPrice = 0;
        foreach ($assemblyData as $price) {
            $totalPrice += $price; // Sum all part prices
        }

        return $totalPrice; // Return the total price of the assembly
    }
}
