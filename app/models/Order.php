<?php

class Order
{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function updateOrderStatus($orderId, $newOrderStatus)
    {
        // Prepare the update query
        $query = "UPDATE `order` SET `order_status` = ? WHERE `id` = ?";

        $stmt = $this->mysqli->prepare($query);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->mysqli->error);
        }


        // Bind parameters (new status and order id)
        $stmt->bind_param("ii", $newOrderStatus, $orderId);

        // Execute the update query
        $stmt->execute();

        $result = false;
        if ($stmt->affected_rows > 0) {
            $result = true;
        }

        $stmt->close();

        return $result;
    }

    function getOrderById($orderId)
    {
        $query = "
        SELECT 
            o.id as order_id,
            o.total_price as price,
            asmbl.name as name,
            os.name as order_status,
            u.id as ordered_by
        FROM 
            `order` o 
        LEFT JOIN
            user_assembly asmbl ON o.assembly_id = asmbl.id
        LEFT JOIN
            order_states os ON o.order_status = os.id
        LEFT JOIN
            user u ON o.ordered_by = u.id
        WHERE 
            o.id = ?";

        $stmt = $this->mysqli->prepare($query);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->mysqli->error);
        }

        $stmt->bind_param("i", $orderId);
        $stmt->execute();

        $result = $stmt->get_result();
        $userOrders = $result->fetch_assoc();

        $stmt->close();

        return $userOrders;
    }


    public function getUserOrders($userId)
    {
        $query = "
            SELECT 
                o.id as order_id,
                o.total_price as price,
                asmbl.name as name,
                os.name as order_status,
                u.id as ordered_by
            FROM 
                `order` o 
            LEFT JOIN
                user_assembly asmbl ON o.assembly_id = asmbl.id
            LEFT JOIN
                order_states os ON o.order_status = os.id
            LEFT JOIN
                user u ON o.ordered_by = u.id
            WHERE 
                o.ordered_by = ? 
                AND os.name != 'Atšauktas'
            ORDER BY
                o.order_status ASC";

        $stmt = $this->mysqli->prepare($query);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->mysqli->error);
        }

        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $result = $stmt->get_result();
        $userOrders = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        return $userOrders;
    }

    public function getAllOrders()
    {
        $query = "
            SELECT 
                o.id as order_id,
                o.total_price as price,
                asmbl.name as name,
                asmbl.id as assembly_id,
                os.name as order_status,
                u.id as ordered_by,
                CONCAT(u.name, ' ', u.lastname) as user_name
            FROM 
                `order` o 
            LEFT JOIN
                user_assembly asmbl ON o.assembly_id = asmbl.id
            LEFT JOIN
                order_states os ON o.order_status = os.id
            LEFT JOIN
                user u ON o.ordered_by = u.id
            ORDER BY
                o.order_status ASC";

        $stmt = $this->mysqli->prepare($query);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->mysqli->error);
        }

        $stmt->execute();

        $result = $stmt->get_result();
        $userOrders = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        return $userOrders;
    }

    public function orderAssembly($assemblyData)
    {
        $stmt = $this->mysqli->prepare(
            "INSERT INTO `order`
            (`ordered_by`, `total_price`, `order_status`, `assembly_id`) 
            VALUES (?, ?, ?, ?)"
        );

        // Check if the statement was prepared successfully
        if (!$stmt) {
            throw new Exception("Failed to prepare the query: " . $this->mysqli->error);
        }

        // Bind the parameters to the prepared statement

        $userId = getUserId();
        $totalPrice = $assemblyData['total_price'];
        $orderStatus = 2;

        $stmt->bind_param(
            'idii',
            $userId,
            $totalPrice,
            $orderStatus,
            $assemblyData['id'],
        );

        // Execute the statement
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $orderId = $this->mysqli->insert_id;

            $orderData = [
                'id' => $orderId,
                'price' => $assemblyData['total_price'] + $assemblyData['price'],
                'ordered_by' => getUserId(),
                'order_status' => $orderStatus
            ];

            return $orderData;
        } else {
            throw new Exception("Error creating order.");
        }
    }
}
