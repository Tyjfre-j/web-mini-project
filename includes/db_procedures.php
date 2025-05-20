<?php
/**
 * Database Procedures Implementation
 * 
 * This file provides functions to interact with the database procedures
 * and make them easy to use throughout the application.
 */

/**
 * Get complete details for an order
 * 
 * @param int $orderId The order ID to retrieve
 * @return array|false Returns array with order header and items, or false on failure
 */
function getOrderDetails($orderId) {
    global $conn;
    
    try {
        // Create PDO connection if using mysqli
        if (!($conn instanceof PDO)) {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
            $pdoConn = new PDO($dsn, DB_USER, DB_PASS);
            $pdoConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } else {
            $pdoConn = $conn;
        }
        
        // Call the stored procedure
        $stmt = $pdoConn->prepare("CALL GetOrderDetails(?)");
        $stmt->bindParam(1, $orderId, PDO::PARAM_INT);
        $stmt->execute();
        
        // Get order header
        $orderHeader = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Move to the next result set for order items
        $stmt->nextRowset();
        
        // Get order items
        $orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'order' => $orderHeader,
            'items' => $orderItems
        ];
    } catch (PDOException $e) {
        error_log("Error in getOrderDetails: " . $e->getMessage());
        return false;
    }
}

/**
 * Finalize an order by calling the stored procedure
 * 
 * @param int $customerId Customer making the order
 * @param string $shippingAddress Shipping address
 * @param string $paymentMethod Payment method used
 * @return int|false New order ID or false on failure
 */
function finalizeOrder($customerId, $shippingAddress, $paymentMethod, $orderNotes = '') {
    global $conn;
    
    try {
        // Create PDO connection if using mysqli
        if (!($conn instanceof PDO)) {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
            $pdoConn = new PDO($dsn, DB_USER, DB_PASS);
            $pdoConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } else {
            $pdoConn = $conn;
        }
        
        // Call the stored procedure
        $stmt = $pdoConn->prepare("CALL FinalizeOrder(?, ?, ?, @new_order_id)");
        $stmt->bindParam(1, $customerId, PDO::PARAM_INT);
        $stmt->bindParam(2, $shippingAddress, PDO::PARAM_STR);
        $stmt->bindParam(3, $paymentMethod, PDO::PARAM_STR);
        $stmt->execute();
        
        // Get the new order ID
        $result = $pdoConn->query("SELECT @new_order_id as order_id");
        $row = $result->fetch(PDO::FETCH_ASSOC);
        
        return $row['order_id'];
    } catch (PDOException $e) {
        error_log("Error in finalizeOrder: " . $e->getMessage());
        return false;
    }
}

/**
 * Get a customer's order history
 * 
 * @param int $customerId The customer ID
 * @return array|false Array of orders or false on failure
 */
function getCustomerOrderHistory($customerId) {
    global $conn;
    
    try {
        // Check if the stored procedure exists
        $checkProcedure = false;
        
        if ($conn instanceof PDO) {
            // Using PDO
            try {
                // First check if the procedure exists to avoid cryptic errors
                $checkStmt = $conn->prepare("SHOW PROCEDURE STATUS WHERE Db = ?");
                $db = 'site_database'; // Same as in config.php
                $checkStmt->execute([$db]);
                $procedures = $checkStmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($procedures as $proc) {
                    if ($proc['Name'] === 'GetCustomerOrderHistory') {
                        $checkProcedure = true;
                        break;
                    }
                }
                
                if (!$checkProcedure) {
                    // Procedure doesn't exist, use a fallback query
                    $fallbackStmt = $conn->prepare("SELECT * FROM orders WHERE customer_id = ? ORDER BY order_date DESC");
                    $fallbackStmt->bindParam(1, $customerId, PDO::PARAM_INT);
                    $fallbackStmt->execute();
                    return $fallbackStmt->fetchAll(PDO::FETCH_ASSOC);
                }
                
                // Procedure exists, call it
                $stmt = $conn->prepare("CALL GetCustomerOrderHistory(?)");
                $stmt->bindParam(1, $customerId, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                // If there's an error calling the procedure, use a fallback query
                error_log("Error calling procedure, using fallback: " . $e->getMessage());
                $fallbackStmt = $conn->prepare("SELECT * FROM orders WHERE customer_id = ? ORDER BY order_date DESC");
                $fallbackStmt->bindParam(1, $customerId, PDO::PARAM_INT);
                $fallbackStmt->execute();
                return $fallbackStmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } else {
            // Using mysqli
            // Check if procedure exists
            $checkResult = $conn->query("SHOW PROCEDURE STATUS WHERE Db = 'site_database'");
            if ($checkResult) {
                while ($row = $checkResult->fetch_assoc()) {
                    if ($row['Name'] === 'GetCustomerOrderHistory') {
                        $checkProcedure = true;
                        break;
                    }
                }
            }
            
            if (!$checkProcedure) {
                // Procedure doesn't exist, use a fallback query
                $fallbackStmt = $conn->prepare("SELECT * FROM orders WHERE customer_id = ? ORDER BY order_date DESC");
                $fallbackStmt->bind_param("i", $customerId);
                $fallbackStmt->execute();
                $result = $fallbackStmt->get_result();
                return $result->fetch_all(MYSQLI_ASSOC);
            }
            
            // Procedure exists, use it
            $stmt = $conn->prepare("CALL GetCustomerOrderHistory(?)");
            $stmt->bind_param("i", $customerId);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }
    } catch (Exception $e) {
        error_log("Error in getCustomerOrderHistory: " . $e->getMessage());
        
        // Try one last fallback - direct query if all else fails
        try {
            if ($conn instanceof PDO) {
                $stmt = $conn->prepare("SELECT * FROM orders WHERE customer_id = ?");
                $stmt->bindParam(1, $customerId, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $stmt = $conn->prepare("SELECT * FROM orders WHERE customer_id = ?");
                $stmt->bind_param("i", $customerId);
                $stmt->execute();
                $result = $stmt->get_result();
                return $result->fetch_all(MYSQLI_ASSOC);
            }
        } catch (Exception $fallbackE) {
            error_log("Final fallback query failed: " . $fallbackE->getMessage());
            return [];  // Return empty array instead of false to avoid exceptions
        }
    }
}

/**
 * Add an item to an order with stock validation
 * 
 * This function will utilize the before_order_items_insert trigger for stock validation
 * and the after_order_items_insert trigger for stock updates
 * 
 * @param int $orderId The order ID
 * @param int $productId The product ID
 * @param string $productType The product type (matches table name)
 * @param string $productName Product name 
 * @param int $quantity Quantity to order
 * @param float $price Unit price
 * @return bool Success or failure
 */
function addOrderItem($orderId, $productId, $productType, $productName, $quantity, $price) {
    global $conn;
    
    try {
        // Calculate subtotal
        $subtotal = $quantity * $price;
        
        // Insert the order item (triggers will handle stock validation and updates)
        if ($conn instanceof PDO) {
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_type, product_name, quantity, price, subtotal) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bindParam(1, $orderId, PDO::PARAM_INT);
            $stmt->bindParam(2, $productId, PDO::PARAM_INT);
            $stmt->bindParam(3, $productType, PDO::PARAM_STR);
            $stmt->bindParam(4, $productName, PDO::PARAM_STR);
            $stmt->bindParam(5, $quantity, PDO::PARAM_INT);
            $stmt->bindParam(6, $price, PDO::PARAM_STR);
            $stmt->bindParam(7, $subtotal, PDO::PARAM_STR);
            return $stmt->execute();
        } else {
            // Using mysqli
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_type, product_name, quantity, price, subtotal) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iissids", $orderId, $productId, $productType, $productName, $quantity, $price, $subtotal);
            return $stmt->execute();
        }
    } catch (Exception $e) {
        error_log("Error in addOrderItem: " . $e->getMessage());
        return false;
    }
}

/**
 * Cancel an order and restore product stock
 * 
 * This utilizes the after_order_status_update trigger to restore stock
 * and log to the canceled_orders_history table
 * 
 * @param int $orderId The order ID to cancel
 * @param string $reason Optional reason for cancellation
 * @return bool Success or failure
 */
function cancelOrder($orderId, $reason = '') {
    global $conn;
    
    try {
        // Update order status to cancelled (trigger will handle stock restoration)
        if ($conn instanceof PDO) {
            $stmt = $conn->prepare("UPDATE orders SET order_status = 'cancelled', order_notes = ? WHERE order_id = ?");
            $stmt->bindParam(1, $reason, PDO::PARAM_STR);
            $stmt->bindParam(2, $orderId, PDO::PARAM_INT);
            return $stmt->execute();
        } else {
            // Using mysqli
            $stmt = $conn->prepare("UPDATE orders SET order_status = 'cancelled', order_notes = ? WHERE order_id = ?");
            $stmt->bind_param("si", $reason, $orderId);
            return $stmt->execute();
        }
    } catch (Exception $e) {
        error_log("Error in cancelOrder: " . $e->getMessage());
        return false;
    }
}

/**
 * Check if all items in the cart have sufficient stock
 * Returns an array with status and detailed error information if any
 * 
 * @param array $cartItems Array of cart items from $_SESSION['mycart']
 * @return array Array with 'status' (boolean) and 'error_info' (if status is false)
 */
function checkCartStockAvailability($cartItems) {
    global $conn;
    
    if (empty($cartItems)) {
        return ['status' => false, 'error_info' => 'Cart is empty'];
    }
    
    try {
        foreach ($cartItems as $item) {
            $product_id = $item['product_id'];
            $product_category = $item['product_category'];
            $product_name = $item['product_name'];
            $product_qty = $item['product_qty'];
            
            // Check stock of this item
            $sql = '';
            $fieldName = '';
            
            switch($product_category) {
                case 'Laptops':
                    $sql = "SELECT Laptops_quantity FROM Laptops WHERE Laptops_id = ?";
                    $fieldName = 'Laptops_quantity';
                    break;
                case 'Desktops':
                    $sql = "SELECT Desktops_quantity FROM Desktops WHERE Desktops_id = ?";
                    $fieldName = 'Desktops_quantity';
                    break;
                case 'Custom Builds':
                    $sql = "SELECT `Custom Builds_quantity` FROM `Custom Builds` WHERE `Custom Builds_id` = ?";
                    $fieldName = 'Custom Builds_quantity';
                    break;
                case 'Processors':
                    $sql = "SELECT Processors_quantity FROM Processors WHERE Processors_id = ?";
                    $fieldName = 'Processors_quantity';
                    break;
                case 'Graphics Cards':
                    $sql = "SELECT `Graphics Cards_quantity` FROM `Graphics Cards` WHERE `Graphics Cards_id` = ?";
                    $fieldName = 'Graphics Cards_quantity';
                    break;
                case 'Keyboards':
                    $sql = "SELECT Keyboards_quantity FROM Keyboards WHERE Keyboards_id = ?";
                    $fieldName = 'Keyboards_quantity';
                    break;
                case 'Display Screens':
                    $sql = "SELECT `Display Screens_quantity` FROM `Display Screens` WHERE `Display Screens_id` = ?";
                    $fieldName = 'Display Screens_quantity';
                    break;
                default:
                    return [
                        'status' => false, 
                        'error_info' => "Invalid product category: $product_category"
                    ];
            }
            
            if ($conn instanceof PDO) {
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(1, $product_id, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $available_qty = $result[$fieldName] ?? 0;
            } else {
                // Using mysqli
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $product_id);
                $stmt->execute();
                $stmt->bind_result($available_qty);
                $stmt->fetch();
                $stmt->close();
            }
            
            // Compare with requested quantity
            if ($product_qty > $available_qty) {
                return [
                    'status' => false,
                    'error_info' => [
                        'product_name' => $product_name,
                        'requested_qty' => $product_qty,
                        'available_qty' => $available_qty
                    ]
                ];
            }
        }
        
        // All items have sufficient stock
        return ['status' => true];
        
    } catch (Exception $e) {
        return [
            'status' => false,
            'error_info' => "Error checking stock: " . $e->getMessage()
        ];
    }
} 