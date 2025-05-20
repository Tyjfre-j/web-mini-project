<?php
// Check if session is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection file
require_once('./includes/db_connection.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['cart_error'] = "Please login to complete your purchase";
    header('location:login.php?redirect=checkout.php');
    exit();
}

// Check if cart is empty
if(!isset($_SESSION['mycart']) || empty($_SESSION['mycart'])) {
    $_SESSION['cart_error'] = "Your cart is empty";
    header('location:cart.php');
    exit();
}

// Function to sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Process checkout form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate required fields
    $required_fields = ['shipping_address', 'payment_method'];
    $missing_fields = [];
    
    foreach($required_fields as $field) {
        if(!isset($_POST[$field]) || empty($_POST[$field])) {
            $missing_fields[] = $field;
        }
    }
    
    if(!empty($missing_fields)) {
        $_SESSION['checkout_error'] = "Missing required fields: " . implode(', ', $missing_fields);
        header('Location: checkout.php');
        exit();
    }
    
    // Sanitize inputs
    $shipping_address = sanitize_input($_POST['shipping_address']);
    $payment_method = sanitize_input($_POST['payment_method']);
    $order_notes = isset($_POST['order_notes']) ? sanitize_input($_POST['order_notes']) : '';
    
    // Calculate total amount
    $total_amount = 0;
    foreach($_SESSION['mycart'] as $item) {
        $total_amount += $item['product_price'] * $item['product_qty'];
    }
    
    // Begin transaction
    $conn->begin_transaction();
    
    try {
        // Create order
        $stmt = $conn->prepare("INSERT INTO orders (customer_id, total_amount, shipping_address, payment_method) 
                                VALUES (?, ?, ?, ?)");
        $stmt->bind_param("idss", $_SESSION['user_id'], $total_amount, $shipping_address, $payment_method);
        
        if(!$stmt->execute()) {
            throw new Exception("Failed to create order: " . $stmt->error);
        }
        
        $order_id = $conn->insert_id;
        $stmt->close();
        
        // Check stock and add order items
        foreach($_SESSION['mycart'] as $item) {
            $product_id = $item['product_id'];
            $product_category = $item['product_category'];
            $product_name = $item['product_name'];
            $product_price = $item['product_price'];
            $product_qty = $item['product_qty'];
            $subtotal = $product_price * $product_qty;
            
            // Check stock before adding item
            $stock_available = false;
            $available_quantity = 0;
            
            // Get current stock
            $stock_query = "SELECT ";
            
            switch($product_category) {
                case 'Laptops':
                    $stock_query .= "Laptops_quantity FROM Laptops WHERE Laptops_id = ?";
                    break;
                case 'Desktops':
                    $stock_query .= "Desktops_quantity FROM Desktops WHERE Desktops_id = ?";
                    break;
                case 'Custom Builds':
                    $stock_query .= "`Custom Builds_quantity` FROM `Custom Builds` WHERE `Custom Builds_id` = ?";
                    break;
                case 'Processors':
                    $stock_query .= "Processors_quantity FROM Processors WHERE Processors_id = ?";
                    break;
                case 'Graphics Cards':
                    $stock_query .= "`Graphics Cards_quantity` FROM `Graphics Cards` WHERE `Graphics Cards_id` = ?";
                    break;
                case 'Keyboards':
                    $stock_query .= "Keyboards_quantity FROM Keyboards WHERE Keyboards_id = ?";
                    break;
                case 'Display Screens':
                    $stock_query .= "`Display Screens_quantity` FROM `Display Screens` WHERE `Display Screens_id` = ?";
                    break;
                default:
                    throw new Exception("Invalid product category: " . $product_category);
            }
            
            $stock_stmt = $conn->prepare($stock_query);
            $stock_stmt->bind_param("i", $product_id);
            $stock_stmt->execute();
            $stock_stmt->bind_result($available_quantity);
            $stock_stmt->fetch();
            $stock_stmt->close();
            
            // Check if enough stock is available
            if($product_qty <= $available_quantity) {
                // Add order item
                $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_type, product_name, quantity, price, subtotal) 
                                      VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iissids", $order_id, $product_id, $product_category, $product_name, $product_qty, $product_price, $subtotal);
                
                if(!$stmt->execute()) {
                    throw new Exception("Failed to add order item: " . $stmt->error);
                }
                
                $stmt->close();
            } else {
                throw new Exception("Insufficient stock for " . $product_name . ". Available: " . $available_quantity . ", Requested: " . $product_qty);
            }
        }
        
        // Finalize the order using stored procedure
        $success = false;
        $stmt = $conn->prepare("CALL FinalizeOrder(?, @success)");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $stmt->close();
        
        // Get the output parameter
        $result = $conn->query("SELECT @success as success");
        $row = $result->fetch_assoc();
        $success = $row['success'];
        
        if(!$success) {
            throw new Exception("Failed to finalize order");
        }
        
        // Commit transaction
        $conn->commit();
        
        // Clear cart
        unset($_SESSION['mycart']);
        
        // Set success message
        $_SESSION['checkout_success'] = "Your order has been placed successfully! Order #" . $order_id;
        
        // Redirect to order confirmation
        header("Location: active_orders.php?view_order=" . $order_id);
        exit();
        
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();
        
        // Check if the error is from the stock trigger
        $error_message = $e->getMessage();
        if (strpos($error_message, 'STOCK_ERROR:') === 0) {
            // Parse the detailed error message: STOCK_ERROR:product_name:requested_qty:available_qty
            $error_parts = explode(':', $error_message);
            if (count($error_parts) >= 4) {
                $product_name = $error_parts[1];
                $requested_qty = $error_parts[2];
                $available_qty = $error_parts[3];
                
                $_SESSION['checkout_error'] = "Stock error: Cannot order $requested_qty units of '$product_name'. Only $available_qty in stock.";
            } else {
                $_SESSION['checkout_error'] = "There was an issue with product stock. Please check your cart.";
            }
        } else {
            // Set generic error message
            $_SESSION['checkout_error'] = $e->getMessage();
        }
        
        // Redirect back to checkout
        header('Location: checkout.php');
        exit();
    }
    
} else {
    // Not a POST request
    header('Location: checkout.php');
    exit();
}
?> 