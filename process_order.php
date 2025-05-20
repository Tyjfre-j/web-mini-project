<?php
// Check if session is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('./includes/dbconnection.php');
require_once('./includes/functions.php'); // This will include db_procedures.php

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Please login to complete your order";
    header('Location: login.php');
    exit();
}

// Check if cart is empty
if (!isset($_SESSION['mycart']) || empty($_SESSION['mycart'])) {
    $_SESSION['error_message'] = "Your cart is empty";
    header('Location: cart.php');
    exit();
}

// Get customer ID
$customer_id = $_SESSION['user_id'];

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $shipping_address = filter_var($_POST['shipping_address'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $payment_method = filter_var($_POST['payment_method'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Validate required inputs
    if (empty($shipping_address) || empty($payment_method)) {
        $_SESSION['error_message'] = "Missing required fields for checkout";
        header('Location: checkout.php');
        exit();
    }

    try {
        // Begin transaction
        $conn->beginTransaction();
        
        // Use the finalizeOrder function from db_procedures.php
        $order_id = finalizeOrder($customer_id, $shipping_address, $payment_method);
        
        if (!$order_id) {
            throw new Exception("Failed to create order");
        }
        
        // Insert order items and handle total amount
        $total_amount = 0;
        
        foreach ($_SESSION['mycart'] as $item) {
            $product_id = $item['product_id'];
            $product_type = $item['category'];
            $product_name = $item['name'];
            $quantity = $item['product_qty'];
            $price = $item['price'];
            $subtotal = $price * $quantity;
            $total_amount += $subtotal;
            
            try {
                // Use the addOrderItem function from db_procedures.php
                $success = addOrderItem($order_id, $product_id, $product_type, $product_name, $quantity, $price);
                
                if (!$success) {
                    throw new Exception("Failed to add item to order");
                }
            } catch (Exception $e) {
                // If error occurs (like insufficient stock from trigger)
                $conn->rollBack();
                $_SESSION['error_message'] = "Error processing your order: " . $e->getMessage();
                header('Location: checkout.php');
                exit();
            }
        }
        
        // Update order total
        $stmt = $conn->prepare("UPDATE orders SET total_amount = :total_amount WHERE order_id = :order_id");
        $stmt->bindParam(':total_amount', $total_amount);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
        
        // Commit the transaction
        $conn->commit();
        
        // Clear the cart
        unset($_SESSION['mycart']);
        
        // Set success message
        $_SESSION['success_message'] = "Order placed successfully! Your order ID is #" . $order_id;
        
        // Redirect to order confirmation page
        header('Location: order_confirmation.php?order_id=' . $order_id);
        exit();
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollBack();
        
        // Check if the error is from the stock trigger
        $error_message = $e->getMessage();
        if (strpos($error_message, 'STOCK_ERROR:') === 0) {
            // Parse the detailed error message: STOCK_ERROR:product_name:requested_qty:available_qty
            $error_parts = explode(':', $error_message);
            if (count($error_parts) >= 4) {
                $product_name = $error_parts[1];
                $requested_qty = $error_parts[2];
                $available_qty = $error_parts[3];
                
                $_SESSION['error_message'] = "Stock error: Cannot order $requested_qty units of '$product_name'. Only $available_qty in stock.";
            } else {
                $_SESSION['error_message'] = "There was an issue with product stock. Please check your cart.";
            }
        } else {
            $_SESSION['error_message'] = "Error processing your order: " . $e->getMessage();
        }
        
        header('Location: checkout.php');
        exit();
    }
}
else {
    // If not a POST request, redirect to checkout
    header('Location: checkout.php');
    exit();
}
?> 