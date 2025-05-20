<?php
session_start();
include('./includes/dbconnection.php');

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
    // Get form data
    $shipping_address = $_POST['shipping_address'];
    $billing_address = $_POST['billing_address'] ?? $shipping_address; // Use shipping address if billing not provided
    $payment_method = $_POST['payment_method'];
    $order_notes = $_POST['order_notes'] ?? '';
    
    try {
        // Begin transaction
        $conn->beginTransaction();
        
        // Call the FinalizeOrder stored procedure
        $stmt = $conn->prepare("CALL FinalizeOrder(:customer_id, :shipping_address, :billing_address, :payment_method, :order_notes, @new_order_id)");
        $stmt->bindParam(':customer_id', $customer_id);
        $stmt->bindParam(':shipping_address', $shipping_address);
        $stmt->bindParam(':billing_address', $billing_address);
        $stmt->bindParam(':payment_method', $payment_method);
        $stmt->bindParam(':order_notes', $order_notes);
        $stmt->execute();
        
        // Get the new order ID
        $stmt = $conn->query("SELECT @new_order_id as order_id");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $order_id = $result['order_id'];
        
        // Insert order items (will trigger stock update automatically through trigger)
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
                $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_type, product_name, quantity, price, subtotal) 
                                      VALUES (:order_id, :product_id, :product_type, :product_name, :quantity, :price, :subtotal)");
                
                $stmt->bindParam(':order_id', $order_id);
                $stmt->bindParam(':product_id', $product_id);
                $stmt->bindParam(':product_type', $product_type);
                $stmt->bindParam(':product_name', $product_name);
                $stmt->bindParam(':quantity', $quantity);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':subtotal', $subtotal);
                
                $stmt->execute();
            } catch (PDOException $e) {
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
        
    } catch (PDOException $e) {
        // Rollback transaction on error
        $conn->rollBack();
        $_SESSION['error_message'] = "Error processing your order: " . $e->getMessage();
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