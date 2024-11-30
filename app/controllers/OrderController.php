<?php

class OrderController
{
    private $orderModel;
    private $deviceModel;

    public function __construct()
    {
        global $mysqli;
        $this->orderModel = new Order($mysqli);
        $this->deviceModel = new Device($mysqli);
    }

    public function showUserOrders()
    {
        $userId = intval(getUserId());

        if (isUserInRole([1])) {
            $ordersData = $this->orderModel->getAllOrders();
        } elseif (isUserInRole([3])) {
            $ordersData = $this->orderModel->getUserOrders($userId);
        }

        if ($ordersData) {
            $this->renderView('orders', ['ordersData' => $ordersData]);
        } else {
            $this->renderView('orders', []);
        }
    }

    public function renderView($viewName, $data = [])
    {
        // Extract data array to individual variables
        extract($data);

        // Include the view file
        include "app/views/$viewName.php";
    }

    public function orderAssembly()
    {
        header('Content-Type: application/json');
        // Check if assembly_id is present in POST data
        if (isset($_POST['assembly_id'])) {
            $assemblyId = intval($_POST['assembly_id']);
            $assemblyData = $this->deviceModel->getAssemblyById($assemblyId);

            if ($assemblyData) {
                $order = $this->orderModel->orderAssembly($assemblyData);

                if (!$order) {
                    http_response_code(404);
                    echo json_encode([
                        'error' => 'Order could not be placed. Assembly data not found',
                        'assemblyId' => $assemblyId,
                        'assemblyData' => $assemblyData,
                    ]);
                } else {
                    http_response_code(200);
                    echo json_encode([
                        'success' => true,
                        'order' => $order,
                    ]);
                }
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Assembly not found']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid or missing assembly ID']);
        }
    }

    public function updateOrderStatus()
    {
        header('Content-Type: application/json');

        // Check if order_id is present in POST data
        if (isset($_POST['order_id']) && isset($_POST['new_status'])) {
            $orderId = intval($_POST['order_id']);
            $newOrderStatus = intval($_POST['new_status']);

            // Call the updateOrderStatus method to update the order status to canceled
            $result = $this->orderModel->updateOrderStatus($orderId, $newOrderStatus);

            if ($result) {
                http_response_code(200);
                echo json_encode([
                    'success' => true,
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    'error' => 'Order status could not be updated. Order data not found',
                    'orderId' => $orderId,
                    'result' => $result
                ]);
            }
        } else {
            if (!isset($_POST['order_id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Order ID not provided']);
            } elseif (!isset($_POST['new_status'])) {
                http_response_code(400);
                echo json_encode(['error' => 'New order status not provided']);
            }
        }
    }
}
