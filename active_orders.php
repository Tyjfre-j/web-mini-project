<?php
// Check if session is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection file
include_once('./includes/headerNav.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['cart_error'] = "Please login to view your orders";
    header('location:login.php?redirect=active_orders.php');
    exit();
}

// Initialize error/success messages
$error_message = '';
$success_message = '';

// Handle session messages
if(isset($_SESSION['order_error'])) {
    $error_message = $_SESSION['order_error'];
    unset($_SESSION['order_error']); // Clear after use
}

if(isset($_SESSION['order_success'])) {
    $success_message = $_SESSION['order_success'];
    unset($_SESSION['order_success']); // Clear after use
}

// Process order cancellation
if(isset($_POST['cancel_order'])) {
    $order_id = filter_input(INPUT_POST, 'order_id', FILTER_SANITIZE_NUMBER_INT);
    
    if($order_id) {
        // Update order status to cancelled
        $stmt = $conn->prepare("UPDATE orders SET order_status = 'cancelled', updated_at = NOW() WHERE order_id = ? AND customer_id = ?");
        $stmt->bind_param("ii", $order_id, $_SESSION['user_id']);
        
        if($stmt->execute()) {
            $success_message = "Order #" . $order_id . " has been cancelled successfully.";
        } else {
            $error_message = "Failed to cancel order. Please try again.";
        }
        $stmt->close();
    }
}

// Process order details view
$order_details = null;
if(isset($_GET['view_order'])) {
    $order_id = filter_input(INPUT_GET, 'view_order', FILTER_SANITIZE_NUMBER_INT);
    
    if($order_id) {
        // Use the stored procedure to get order details
        $stmt = $conn->prepare("CALL GetPaidOrderDetails(?, ?)");
        $stmt->bind_param("ii", $_SESSION['user_id'], $order_id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        if($result->num_rows > 0) {
            $order_details = $result->fetch_assoc();
            
            // Get order items
            $stmt->next_result(); // Move to next result set
            $items_result = $stmt->get_result();
            $order_items = [];
            while($item = $items_result->fetch_assoc()) {
                $order_items[] = $item;
            }
            $order_details['items'] = $order_items;
        } else {
            $error_message = "Order not found or you don't have permission to view it.";
        }
        $stmt->close();
    }
}

// Get active orders
$active_orders = [];
$stmt = $conn->prepare("SELECT 
                            o.order_id, 
                            o.order_date, 
                            o.total_amount, 
                            o.order_status,
                            o.shipping_address,
                            COUNT(oi.item_id) AS total_items
                        FROM orders o
                        LEFT JOIN order_items oi ON o.order_id = oi.order_id
                        WHERE o.customer_id = ? AND o.order_status IN ('pending', 'processing', 'shipped')
                        GROUP BY o.order_id
                        ORDER BY o.order_date DESC");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $active_orders[] = $row;
    }
}
$stmt->close();
?>

<div class="overlay" data-overlay></div>

<header>
    <?php require_once './includes/topheadactions.php'; ?>
    <?php require_once './includes/desktopnav.php' ?>
    <?php require_once './includes/mobilenav.php'; ?>
</header>

<style>
:root {
    --primary-color: #0d8a91; /* Main teal color from site */
    --primary-dark: #00656b; /* Deep teal from site */
    --secondary-color: #69585f;
    --accent-color: #00656b;
    --success-color: #38b000;
    --danger-color: #e63946;
    --warning-color: #ffb703;
    --text-dark: #333333;
    --text-light: #f8f9fa;
    --bg-light: #f8f9fa;
    --bg-primary: #e9f0f2;
    --bg-secondary: #f0f5f6;
    --bg-accent: #e3f2f3;
    --gray-light: #f8f9fa;
    --gray-medium: #e9ecef;
    --gray-dark: #6c757d;
    --border-light: #e0e0e0;
    --border-radius-md: 10px;
    --border-radius-sm: 5px;
    --shadow-sm: 0 2px 5px rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 8px rgba(0, 0, 0, 0.08);
    --shadow-lg: 0 8px 16px rgba(0, 0, 0, 0.1);
}

.orders-container {
    padding: 1.5rem;
    max-width: 1200px;
    margin: 0 auto;
}

.page-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.page-title {
    font-size: 1.8rem;
    color: var(--text-dark);
    font-weight: 700;
    margin: 0;
    position: relative;
}

.page-title::after {
    content: '';
    position: absolute;
    height: 3px;
    width: 50px;
    background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
    left: 0;
    bottom: -8px;
    border-radius: 2px;
}

.nav-tabs {
    display: flex;
    gap: 0.5rem;
    border-bottom: 1px solid var(--border-light);
    margin-bottom: 1.5rem;
}

.nav-tab {
    padding: 0.75rem 1.5rem;
    background: var(--bg-light);
    color: var(--gray-dark);
    border-radius: 5px 5px 0 0;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    font-weight: 500;
}

.nav-tab:hover {
    background: var(--bg-primary);
    color: var(--primary-dark);
    text-decoration: none;
}

.nav-tab.active {
    background: var(--primary-color);
    color: white;
    text-decoration: none;
}

.orders-grid {
    display: grid;
    gap: 1.5rem;
}

.order-card {
    background: white;
    border-radius: var(--border-radius-md);
    box-shadow: var(--shadow-md);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.order-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.order-header {
    padding: 1.25rem;
    border-bottom: 1px solid var(--border-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--bg-secondary);
}

.order-id {
    font-weight: 700;
    color: var(--text-dark);
    font-size: 1.1rem;
}

.order-date {
    color: var(--gray-dark);
    font-size: 0.9rem;
}

.order-body {
    padding: 1.25rem;
}

.order-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.25rem;
    margin-bottom: 1.25rem;
}

.info-item {
    display: flex;
    flex-direction: column;
}

.info-label {
    font-size: 0.9rem;
    color: var(--gray-dark);
    margin-bottom: 0.25rem;
}

.info-value {
    font-weight: 600;
    color: var(--text-dark);
}

.status-badge {
    display: inline-block;
    padding: 0.35rem 0.75rem;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: capitalize;
}

.status-pending {
    background-color: rgba(255, 183, 3, 0.2);
    color: #d6a100;
}

.status-processing {
    background-color: rgba(13, 138, 145, 0.2);
    color: var(--primary-dark);
}

.status-shipped {
    background-color: rgba(56, 176, 0, 0.2);
    color: #38b000;
}

.status-delivered {
    background-color: rgba(56, 176, 0, 0.2);
    color: #38b000;
}

.status-cancelled {
    background-color: rgba(230, 57, 70, 0.2);
    color: #e63946;
}

.order-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.25rem;
    background: var(--bg-light);
    border-top: 1px solid var(--border-light);
}

.order-total {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--text-dark);
}

.order-actions {
    display: flex;
    gap: 0.75rem;
}

.btn {
    padding: 0.5rem 1rem;
    border-radius: 5px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
}

.btn ion-icon {
    margin-right: 0.25rem;
    font-size: 1.1rem;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
    transform: translateY(-2px);
    text-decoration: none;
    color: white;
}

.btn-outline {
    background: transparent;
    border: 1px solid var(--primary-color);
    color: var(--primary-color);
}

.btn-outline:hover {
    background: var(--primary-color);
    color: white;
    text-decoration: none;
}

.btn-danger {
    background: transparent;
    border: 1px solid var(--danger-color);
    color: var(--danger-color);
}

.btn-danger:hover {
    background: var(--danger-color);
    color: white;
    text-decoration: none;
}

/* Order Details Modal */
.order-details-modal {
    max-width: 800px;
    margin: 2rem auto;
    background: white;
    border-radius: var(--border-radius-md);
    box-shadow: var(--shadow-lg);
    overflow: hidden;
}

.modal-header {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0;
}

.modal-close {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    color: white;
}

.modal-close:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
}

.modal-body {
    padding: 1.5rem;
}

.modal-section {
    margin-bottom: 1.5rem;
}

.modal-section:last-child {
    margin-bottom: 0;
}

.section-title {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: var(--text-dark);
    border-bottom: 1px solid var(--border-light);
    padding-bottom: 0.5rem;
}

.address-box {
    background: var(--bg-light);
    padding: 1rem;
    border-radius: var(--border-radius-sm);
    margin-bottom: 1rem;
}

.items-table {
    width: 100%;
    border-collapse: collapse;
}

.items-table th,
.items-table td {
    padding: 0.75rem;
    text-align: left;
    border-bottom: 1px solid var(--border-light);
}

.items-table th {
    background: var(--bg-light);
    font-weight: 600;
    color: var(--gray-dark);
    font-size: 0.9rem;
}

.items-table tr:last-child td {
    border-bottom: none;
}

.item-name {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.item-img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: var(--border-radius-sm);
}

.item-title {
    font-weight: 600;
    color: var(--text-dark);
}

.item-category {
    font-size: 0.85rem;
    color: var(--gray-dark);
}

.subtotal-row {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-top: 1px solid var(--border-light);
}

.subtotal-label {
    color: var(--gray-dark);
}

.subtotal-value {
    font-weight: 600;
    color: var(--text-dark);
}

.total-row {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-top: 1px dashed var(--border-light);
    font-size: 1.1rem;
}

.total-label {
    font-weight: 600;
    color: var(--text-dark);
}

.total-value {
    font-weight: 700;
    color: var(--primary-dark);
}

.modal-footer {
    padding: 1rem 1.5rem;
    background: var(--bg-light);
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    border-top: 1px solid var(--border-light);
}

.tracking-info {
    background: var(--bg-accent);
    padding: 1rem;
    border-radius: var(--border-radius-sm);
    margin-top: 1rem;
}

.tracking-heading {
    font-weight: 600;
    color: var(--primary-dark);
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.tracking-heading ion-icon {
    font-size: 1.2rem;
}

.tracking-number {
    font-family: monospace;
    font-size: 1.1rem;
    background: rgba(255, 255, 255, 0.5);
    padding: 0.5rem;
    border-radius: 4px;
    border: 1px dashed var(--primary-color);
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    background: white;
    border-radius: var(--border-radius-md);
    box-shadow: var(--shadow-md);
}

.empty-state-icon {
    font-size: 3rem;
    color: var(--gray-dark);
    margin-bottom: 1rem;
}

.empty-state-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 1rem;
}

.empty-state-text {
    color: var(--gray-dark);
    max-width: 500px;
    margin: 0 auto 1.5rem;
}

/* Responsive */
@media screen and (max-width: 768px) {
    .orders-container {
        padding: 1rem;
    }
    
    .order-info {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .order-footer {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .order-actions {
        width: 100%;
        justify-content: flex-end;
    }
    
    .modal-header {
        padding: 1rem;
    }
    
    .modal-title {
        font-size: 1.2rem;
    }
    
    .modal-body {
        padding: 1rem;
    }
}

.alert {
    padding: 1rem;
    border-radius: var(--border-radius-sm);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.alert-danger {
    background-color: rgba(230, 57, 70, 0.1);
    color: #e63946;
    border-left: 4px solid #e63946;
}

.alert-success {
    background-color: rgba(56, 176, 0, 0.1);
    color: #38b000;
    border-left: 4px solid #38b000;
}

.order-status-steps {
    display: flex;
    justify-content: space-between;
    margin: 1.5rem 0;
    position: relative;
}

.order-status-steps::before {
    content: '';
    position: absolute;
    top: 24px;
    left: 15px;
    right: 15px;
    height: 2px;
    background: var(--border-light);
    z-index: 1;
}

.status-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
    flex: 1;
}

.step-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: var(--bg-light);
    border: 2px solid var(--border-light);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.5rem;
    font-size: 1.5rem;
    color: var(--gray-dark);
    transition: all 0.3s ease;
}

.step-label {
    font-size: 0.85rem;
    color: var(--gray-dark);
    text-align: center;
    font-weight: 500;
}

.status-step.active .step-icon,
.status-step.completed .step-icon {
    background: var(--primary-color);
    border-color: var(--primary-color);
    color: white;
}

.status-step.active .step-label,
.status-step.completed .step-label {
    color: var(--primary-dark);
    font-weight: 600;
}

.divider-text {
    position: relative;
    text-align: center;
    margin: 1.5rem 0;
}

.divider-text::before,
.divider-text::after {
    content: '';
    position: absolute;
    top: 50%;
    width: calc(50% - 70px);
    height: 1px;
    background: var(--border-light);
}

.divider-text::before {
    left: 0;
}

.divider-text::after {
    right: 0;
}

.divider-text span {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: white;
    font-weight: 500;
    color: var(--gray-dark);
    position: relative;
    z-index: 1;
}
</style>

<main>
    <div class="orders-container">
        <!-- Display error and success messages -->
        <?php if(!empty($error_message)): ?>
        <div class="alert alert-danger" role="alert">
            <ion-icon name="alert-circle-outline"></ion-icon> <?php echo $error_message; ?>
        </div>
        <?php endif; ?>
        
        <?php if(!empty($success_message)): ?>
        <div class="alert alert-success" role="alert">
            <ion-icon name="checkmark-circle-outline"></ion-icon> <?php echo $success_message; ?>
        </div>
        <?php endif; ?>
        
        <div class="page-header">
            <h1 class="page-title">Your Orders</h1>
            <a href="index.php" class="btn btn-outline">
                <ion-icon name="arrow-back-outline"></ion-icon> Continue Shopping
            </a>
        </div>
        
        <div class="nav-tabs">
            <a href="active_orders.php" class="nav-tab active">Active Orders</a>
            <a href="order_history.php" class="nav-tab">Order History</a>
        </div>
        
        <?php if($order_details): ?>
            <!-- Order Details View -->
            <div class="order-details-modal">
                <div class="modal-header">
                    <h2 class="modal-title">Order #<?php echo $order_details['order_id']; ?> Details</h2>
                    <a href="active_orders.php" class="modal-close">
                        <ion-icon name="close-outline"></ion-icon>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="modal-section">
                        <h3 class="section-title">Order Information</h3>
                        <div class="order-status-steps">
                            <?php
                            $status = $order_details['order_status'];
                            $steps = ['pending', 'processing', 'shipped', 'delivered'];
                            $icons = ['time-outline', 'construct-outline', 'car-outline', 'checkmark-circle-outline'];
                            $labels = ['Order Placed', 'Processing', 'Shipped', 'Delivered'];
                            
                            $current_step_index = array_search($status, $steps);
                            if($current_step_index === false) $current_step_index = -1;
                            
                            foreach($steps as $index => $step):
                                $step_class = '';
                                if($index == $current_step_index) {
                                    $step_class = 'active';
                                } elseif($index < $current_step_index) {
                                    $step_class = 'completed';
                                }
                            ?>
                                <div class="status-step <?php echo $step_class; ?>">
                                    <div class="step-icon">
                                        <ion-icon name="<?php echo $icons[$index]; ?>"></ion-icon>
                                    </div>
                                    <div class="step-label"><?php echo $labels[$index]; ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="order-info">
                            <div class="info-item">
                                <div class="info-label">Order Date</div>
                                <div class="info-value"><?php echo date("F j, Y, g:i a", strtotime($order_details['order_date'])); ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Status</div>
                                <div class="info-value">
                                    <span class="status-badge status-<?php echo $order_details['order_status']; ?>">
                                        <?php echo ucfirst($order_details['order_status']); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Payment Method</div>
                                <div class="info-value"><?php echo $order_details['payment_method']; ?></div>
                            </div>
                        </div>
                        
                        <?php if($order_details['order_status'] == 'shipped' && !empty($order_details['tracking_number'])): ?>
                        <div class="tracking-info">
                            <div class="tracking-heading">
                                <ion-icon name="navigate-outline"></ion-icon> Tracking Information
                            </div>
                            <div class="tracking-number"><?php echo $order_details['tracking_number']; ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="modal-section">
                        <h3 class="section-title">Shipping Address</h3>
                        <div class="address-box">
                            <strong><?php echo $order_details['customer_fname']; ?></strong><br>
                            <?php echo nl2br($order_details['shipping_address']); ?><br>
                            <?php echo $order_details['customer_phone']; ?>
                        </div>
                    </div>
                    
                    <div class="modal-section">
                        <h3 class="section-title">Order Items</h3>
                        <table class="items-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($order_details['items'] as $item): ?>
                                <tr>
                                    <td>
                                        <div class="item-name">
                                            <div>
                                                <div class="item-title"><?php echo $item['product_name']; ?></div>
                                                <div class="item-category"><?php echo $item['product_type']; ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td>$<?php echo number_format($item['subtotal'], 2); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        
                        <div class="subtotal-row">
                            <div class="subtotal-label">Subtotal</div>
                            <div class="subtotal-value">$<?php echo number_format($order_details['total_amount'], 2); ?></div>
                        </div>
                        <div class="subtotal-row">
                            <div class="subtotal-label">Shipping</div>
                            <div class="subtotal-value">Free</div>
                        </div>
                        <div class="total-row">
                            <div class="total-label">Total</div>
                            <div class="total-value">$<?php echo number_format($order_details['total_amount'], 2); ?></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="active_orders.php" class="btn btn-outline">Back to Orders</a>
                    
                    <?php if($order_details['order_status'] == 'pending'): ?>
                    <form action="active_orders.php" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                        <input type="hidden" name="order_id" value="<?php echo $order_details['order_id']; ?>">
                        <button type="submit" name="cancel_order" class="btn btn-danger">
                            <ion-icon name="close-circle-outline"></ion-icon> Cancel Order
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <!-- Orders List -->
            <?php if(!empty($active_orders)): ?>
                <div class="orders-grid">
                    <?php foreach($active_orders as $order): ?>
                        <div class="order-card">
                            <div class="order-header">
                                <div class="order-id">Order #<?php echo $order['order_id']; ?></div>
                                <div class="order-date"><?php echo date("F j, Y", strtotime($order['order_date'])); ?></div>
                            </div>
                            <div class="order-body">
                                <div class="order-info">
                                    <div class="info-item">
                                        <div class="info-label">Status</div>
                                        <div class="info-value">
                                            <span class="status-badge status-<?php echo $order['order_status']; ?>">
                                                <?php echo ucfirst($order['order_status']); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Items</div>
                                        <div class="info-value"><?php echo $order['total_items']; ?> items</div>
                                    </div>
                                    <?php if($order['order_status'] == 'shipped' && !empty($order['tracking_number'])): ?>
                                    <div class="info-item">
                                        <div class="info-label">Tracking</div>
                                        <div class="info-value"><?php echo $order['tracking_number']; ?></div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="order-footer">
                                <div class="order-total">$<?php echo number_format($order['total_amount'], 2); ?></div>
                                <div class="order-actions">
                                    <a href="active_orders.php?view_order=<?php echo $order['order_id']; ?>" class="btn btn-primary">
                                        <ion-icon name="eye-outline"></ion-icon> View Details
                                    </a>
                                    
                                    <?php if($order['order_status'] == 'pending'): ?>
                                    <form action="active_orders.php" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                        <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                        <button type="submit" name="cancel_order" class="btn btn-danger">
                                            <ion-icon name="close-circle-outline"></ion-icon> Cancel
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <ion-icon name="bag-outline"></ion-icon>
                    </div>
                    <h2 class="empty-state-title">No Active Orders</h2>
                    <p class="empty-state-text">You don't have any active orders at the moment. Browse our products and place an order to see it here.</p>
                    <a href="index.php" class="btn btn-primary">
                        <ion-icon name="bag-outline"></ion-icon> Start Shopping
                    </a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <div class="divider-text">
            <span>Need Help?</span>
        </div>
        
        <div style="text-align: center; margin-bottom: 2rem;">
            <p>If you have any questions about your orders, please contact our customer support.</p>
            <a href="contact.php" class="btn btn-outline">
                <ion-icon name="mail-outline"></ion-icon> Contact Support
            </a>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Generic confirmation for delete/cancel actions
    document.querySelectorAll('form[onsubmit]').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (form.dataset.submitting === 'true') {
                e.preventDefault();
                return false;
            }
            form.dataset.submitting = 'true';
            
            setTimeout(() => {
                form.dataset.submitting = 'false';
            }, 1000);
        });
    });
});
</script>

<?php require_once './includes/footer.php'; ?> 