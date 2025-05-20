<?php
session_start();
include('./includes/dbconnection.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Please login to modify your order";
    header('Location: login.php');
    exit();
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error_message'] = "Invalid request";
    header('Location: orders.php');
    exit();
}

// Get form data
$order_id = $_POST['order_id'];
$shipping_address = $_POST['shipping_address'];
$payment_method = $_POST['payment_method'];
$order_notes = $_POST['order_notes'];
$item_ids = $_POST['item_ids'] ?? [];
$quantities = $_POST['quantities'] ?? [];
$user_id = $_SESSION['user_id'];

try {
    // First verify that the order belongs to the logged-in user and is in 'pending' status
    $verifyStmt = $conn->prepare("SELECT * FROM orders WHERE order_id = :order_id AND customer_id = :customer_id AND order_status = 'pending'");
    $verifyStmt->bindParam(':order_id', $order_id);
    $verifyStmt->bindParam(':customer_id', $user_id);
    $verifyStmt->execute();
    
    if ($verifyStmt->rowCount() === 0) {
        $_SESSION['error_message'] = "This order cannot be modified";
        header('Location: orders.php');
        exit();
    }
    
    $conn->beginTransaction();
    
    // Update order details
    $updateOrderStmt = $conn->prepare("UPDATE orders SET 
        shipping_address = :shipping_address,
        billing_address = :billing_address,
        payment_method = :payment_method,
        order_notes = :order_notes
        WHERE order_id = :order_id");
        
    $updateOrderStmt->bindParam(':shipping_address', $shipping_address);
    $updateOrderStmt->bindParam(':billing_address', $shipping_address); // Use same address for billing
    $updateOrderStmt->bindParam(':payment_method', $payment_method);
    $updateOrderStmt->bindParam(':order_notes', $order_notes);
    $updateOrderStmt->bindParam(':order_id', $order_id);
    $updateOrderStmt->execute();
    
    // Update order items quantities and recalculate subtotals
    $total_amount = 0;
    
    for ($i = 0; $i < count($item_ids); $i++) {
        // First get the current item price
        $getItemStmt = $conn->prepare("SELECT price FROM order_items WHERE item_id = :item_id");
        $getItemStmt->bindParam(':item_id', $item_ids[$i]);
        $getItemStmt->execute();
        $item = $getItemStmt->fetch(PDO::FETCH_ASSOC);
        
        // Calculate new subtotal
        $quantity = max(1, intval($quantities[$i])); // Ensure quantity is at least 1
        $subtotal = $item['price'] * $quantity;
        $total_amount += $subtotal;
        
        // Update item
        $updateItemStmt = $conn->prepare("UPDATE order_items SET 
            quantity = :quantity,
            subtotal = :subtotal
            WHERE item_id = :item_id");
            
        $updateItemStmt->bindParam(':quantity', $quantity);
        $updateItemStmt->bindParam(':subtotal', $subtotal);
        $updateItemStmt->bindParam(':item_id', $item_ids[$i]);
        $updateItemStmt->execute();
    }
    
    // Update order total amount
    $updateTotalStmt = $conn->prepare("UPDATE orders SET total_amount = :total_amount WHERE order_id = :order_id");
    $updateTotalStmt->bindParam(':total_amount', $total_amount);
    $updateTotalStmt->bindParam(':order_id', $order_id);
    $updateTotalStmt->execute();
    
    $conn->commit();
    
    $_SESSION['success_message'] = "Order #" . $order_id . " has been updated successfully";
    header('Location: orders.php');
    exit();
    
} catch (PDOException $e) {
    $conn->rollBack();
    $_SESSION['error_message'] = "Error updating order: " . $e->getMessage();
    header('Location: modify_order.php?order_id=' . $order_id);
    exit();
}
?> 