<?php
// Check if session is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection file
include_once('./includes/headerNav.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['cart_error'] = "Please login to view your order history";
    header('location:login.php?redirect=order_history.php');
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

// Get order history using stored procedure
$order_history = [];
$stmt = $conn->prepare("CALL GetCustomerOrderHistory(?)");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Only include completed or cancelled orders
        if(in_array($row['order_status'], ['delivered', 'cancelled'])) {
            $order_history[] = $row;
        }
    }
}
$stmt->close();

// Get annual order history statistics
$annual_stats = [];
$stmt = $conn->prepare("SELECT 
                            year, 
                            month, 
                            COUNT(*) as order_count, 
                            SUM(total_amount) as total_spent,
                            SUM(items_count) as items_count
                        FROM order_history 
                        WHERE customer_id = ? AND order_status = 'delivered'
                        GROUP BY year, month
                        ORDER BY year DESC, month DESC");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Create year array if not exists
        if(!isset($annual_stats[$row['year']])) {
            $annual_stats[$row['year']] = [];
        }
        
        // Add month data
        $annual_stats[$row['year']][$row['month']] = [
            'order_count' => $row['order_count'],
            'total_spent' => $row['total_spent'],
            'items_count' => $row['items_count']
        ];
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

/* Annual Statistics */
.stats-section {
    background: white;
    border-radius: var(--border-radius-md);
    box-shadow: var(--shadow-md);
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.stats-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 1.5rem;
    position: relative;
}

.stats-title::after {
    content: '';
    position: absolute;
    height: 3px;
    width: 40px;
    background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
    left: 0;
    bottom: -8px;
    border-radius: 2px;
}

.year-section {
    margin-bottom: 2rem;
}

.year-header {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 1rem;
    border-bottom: 1px solid var(--border-light);
    padding-bottom: 0.5rem;
}

.months-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1rem;
}

.month-card {
    background: var(--bg-light);
    border-radius: var(--border-radius-sm);
    padding: 1rem;
    transition: transform 0.3s ease;
}

.month-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-sm);
}

.month-name {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.75rem;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.stat-label {
    color: var(--gray-dark);
}

.stat-value {
    font-weight: 600;
    color: var(--text-dark);
}

.stat-value.highlight {
    color: var(--primary-color);
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
    
    .months-grid {
        grid-template-columns: 1fr;
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
            <h1 class="page-title">Order History</h1>
            <a href="index.php" class="btn btn-outline">
                <ion-icon name="arrow-back-outline"></ion-icon> Continue Shopping
            </a>
        </div>
        
        <div class="nav-tabs">
            <a href="active_orders.php" class="nav-tab">Active Orders</a>
            <a href="order_history.php" class="nav-tab active">Order History</a>
        </div>
        
        <?php if($order_details): ?>
            <!-- Order Details View -->
            <div class="order-details-modal">
                <div class="modal-header">
                    <h2 class="modal-title">Order #<?php echo $order_details['order_id']; ?> Details</h2>
                    <a href="order_history.php" class="modal-close">
                        <ion-icon name="close-outline"></ion-icon>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="modal-section">
                        <h3 class="section-title">Order Information</h3>
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
                    <a href="order_history.php" class="btn btn-outline">Back to History</a>
                    <a href="#" class="btn btn-primary" onclick="window.print()">
                        <ion-icon name="print-outline"></ion-icon> Print Receipt
                    </a>
                </div>
            </div>
        <?php else: ?>
            <!-- Annual Statistics -->
            <?php if(!empty($annual_stats)): ?>
                <div class="stats-section">
                    <h2 class="stats-title">Your Shopping History</h2>
                    
                    <?php foreach($annual_stats as $year => $months): ?>
                        <div class="year-section">
                            <h3 class="year-header"><?php echo $year; ?></h3>
                            <div class="months-grid">
                                <?php 
                                $monthNames = [
                                    1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                                    5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                                    9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                                ];
                                
                                foreach($months as $month => $stats): 
                                ?>
                                    <div class="month-card">
                                        <div class="month-name"><?php echo $monthNames[$month]; ?></div>
                                        <div class="stat-item">
                                            <div class="stat-label">Orders</div>
                                            <div class="stat-value"><?php echo $stats['order_count']; ?></div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-label">Items Purchased</div>
                                            <div class="stat-value"><?php echo $stats['items_count']; ?></div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-label">Total Spent</div>
                                            <div class="stat-value highlight">$<?php echo number_format($stats['total_spent'], 2); ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <!-- Orders List -->
            <?php if(!empty($order_history)): ?>
                <div class="orders-grid">
                    <?php foreach($order_history as $order): ?>
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
                                </div>
                            </div>
                            <div class="order-footer">
                                <div class="order-total">$<?php echo number_format($order['total_amount'], 2); ?></div>
                                <div class="order-actions">
                                    <a href="order_history.php?view_order=<?php echo $order['order_id']; ?>" class="btn btn-primary">
                                        <ion-icon name="eye-outline"></ion-icon> View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <ion-icon name="calendar-outline"></ion-icon>
                    </div>
                    <h2 class="empty-state-title">No Order History</h2>
                    <p class="empty-state-text">You don't have any completed orders yet. Once your orders are delivered or cancelled, they will appear here.</p>
                    <a href="active_orders.php" class="btn btn-outline">
                        <ion-icon name="arrow-forward-outline"></ion-icon> Check Active Orders
                    </a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <div class="divider-text">
            <span>Need Help?</span>
        </div>
        
        <div style="text-align: center; margin-bottom: 2rem;">
            <p>If you have any questions about your order history, please contact our customer support.</p>
            <a href="contact.php" class="btn btn-outline">
                <ion-icon name="mail-outline"></ion-icon> Contact Support
            </a>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Print receipt
    window.addEventListener('beforeprint', function() {
        // Add any specific print styling or preparations here
        document.body.classList.add('printing');
    });
    
    window.addEventListener('afterprint', function() {
        document.body.classList.remove('printing');
    });
});
</script>

<?php require_once './includes/footer.php'; ?> 