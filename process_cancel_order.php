<?php
session_start();
include('./includes/dbconnection.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Please login to cancel your order";
    header('Location: login.php');
    exit();
}

// Check if order_id is provided
if (!isset($_GET['order_id']) || empty($_GET['order_id'])) {
    $_SESSION['error_message'] = "Invalid order ID";
    header('Location: orders.php');
    exit();
}

$order_id = $_GET['order_id'];
$user_id = $_SESSION['user_id'];
$reason = isset($_GET['reason']) ? $_GET['reason'] : 'Customer requested cancellation';

try {
    // First verify that the order belongs to the logged-in user
    $verifyStmt = $conn->prepare("SELECT * FROM orders WHERE order_id = :order_id AND customer_id = :customer_id");
    $verifyStmt->bindParam(':order_id', $order_id);
    $verifyStmt->bindParam(':customer_id', $user_id);
    $verifyStmt->execute();
    
    if ($verifyStmt->rowCount() === 0) {
        $_SESSION['error_message'] = "You don't have permission to cancel this order";
        header('Location: orders.php');
        exit();
    }
    
    $order = $verifyStmt->fetch(PDO::FETCH_ASSOC);
    
    // Check if order status allows cancellation
    if ($order['order_status'] !== 'pending' && $order['order_status'] !== 'processing') {
        $_SESSION['error_message'] = "This order cannot be cancelled in its current status";
        header('Location: orders.php');
        exit();
    }
    
    // Start a transaction
    $conn->beginTransaction();
    
    try {
        // Update order status to cancelled - this will trigger the after_order_status_update trigger
        // which will restore stock and log to canceled_orders_history automatically
        $updateStmt = $conn->prepare("UPDATE orders SET order_status = 'cancelled' WHERE order_id = :order_id");
        $updateStmt->bindParam(':order_id', $order_id);
        $updateStmt->execute();
        
        // Add reason to canceled_orders_history
        $updateReasonStmt = $conn->prepare(
            "UPDATE canceled_orders_history SET reason = :reason WHERE order_id = :order_id ORDER BY history_id DESC LIMIT 1"
        );
        $updateReasonStmt->bindParam(':reason', $reason);
        $updateReasonStmt->bindParam(':order_id', $order_id);
        $updateReasonStmt->execute();
        
        $conn->commit();
        
        $_SESSION['success_message'] = "Order #" . $order_id . " has been cancelled successfully";
        header('Location: orders.php');
        exit();
    } catch (PDOException $e) {
        $conn->rollBack();
        throw $e;
    }
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Error cancelling order: " . $e->getMessage();
    header('Location: orders.php');
    exit();
}
?> 