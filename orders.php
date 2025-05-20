<?php include_once('./includes/headerNav.php'); ?>

<div class="overlay" data-overlay></div>

<header>
    <?php require_once './includes/topheadactions.php'; ?>
    <?php require_once './includes/desktopnav.php' ?>
    <?php require_once './includes/mobilenav.php'; ?>
</header>

<style>
:root {
    --main-maroon: #CE5959;
    --deep-maroon: #89375F;
    --light-gray: #f5f5f5;
    --border-color: #e2e2e2;
    --text-gray: #666;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 8px;
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}

.orders-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.page-header {
    margin-bottom: 2rem;
    text-align: center;
}

.page-title {
    font-size: 2.2rem;
    font-weight: 700;
    color: var(--main-maroon);
    margin-bottom: 0.5rem;
}

.page-subtitle {
    color: var(--text-gray);
    font-size: 1.1rem;
    max-width: 600px;
    margin: 0 auto 1.5rem;
    line-height: 1.6;
}

.orders-list {
    display: flex;
    flex-direction: column;
}

.order-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    margin-bottom: 15px;
}

.order-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 20px rgba(0, 0, 0, 0.1);
}

.order-header {
    background: linear-gradient(135deg, var(--main-maroon), var(--deep-maroon));
    color: white;
    padding: 0.8rem 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.order-number {
    font-weight: 600;
    font-size: 0.95rem;
}

.order-date {
    font-size: 0.8rem;
    opacity: 0.9;
}

.order-body {
    padding: 0.8rem 1rem;
}

.order-summary {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.8rem;
}

.order-amount {
    font-size: 1.2rem;
    font-weight: 700;
    color: #333;
}

.order-status {
    display: inline-block;
    padding: 0.3rem 0.8rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
}

.status-pending {
    background-color: #FFF3CD;
    color: #856404;
}

.status-processing {
    background-color: #CCE5FF;
    color: #004085;
}

.status-shipped {
    background-color: #D4EDDA;
    color: #155724;
}

.status-delivered {
    background-color: #D1E7DD;
    color: #0F5132;
}

.status-cancelled {
    background-color: #F8D7DA;
    color: #721C24;
}

.order-details {
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 0.8rem;
    font-size: 0.9rem;
}

.detail-group {
    flex: 1;
    min-width: 180px;
    margin-bottom: 0.5rem;
}

.detail-label {
    font-size: 0.8rem;
    color: var(--text-gray);
    margin-bottom: 0.2rem;
}

.detail-value {
    font-weight: 500;
    color: #333;
    font-size: 0.9rem;
}

.order-items {
    border-top: 1px solid var(--border-color);
    padding-top: 0.8rem;
}

.order-items-header {
    font-size: 0.95rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #333;
}

.item-list {
    max-height: 150px;
    overflow-y: auto;
}

.order-item {
    display: flex;
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--border-color);
}

.order-item:last-child {
    border-bottom: none;
}

.item-img {
    width: 70px;
    height: 70px;
    object-fit: cover;
    border-radius: 8px;
    margin-right: 1rem;
}

.item-details {
    flex: 1;
}

.item-name {
    font-weight: 500;
    color: #333;
    margin-bottom: 0.2rem;
    font-size: 0.9rem;
}

.item-price {
    color: var(--text-gray);
    font-size: 0.8rem;
}

.item-quantity {
    background: var(--light-gray);
    border-radius: 4px;
    padding: 0.1rem 0.4rem;
    font-size: 0.75rem;
    margin-left: 0.3rem;
}

.order-actions {
    margin-top: 0.8rem;
    display: flex;
    gap: 0.5rem;
}

.btn {
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-weight: 600;
    transition: all 0.3s;
    text-decoration: none;
    display: inline-block;
    text-align: center;
    cursor: pointer;
    font-size: 0.85rem;
}

.btn-primary {
    background: linear-gradient(135deg, var(--main-maroon), var(--deep-maroon));
    color: white;
    border: none;
    box-shadow: 0 4px 10px rgba(206, 89, 89, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(206, 89, 89, 0.35);
}

.btn-outline {
    background: transparent;
    color: var(--main-maroon);
    border: 1px solid var(--main-maroon);
}

.btn-outline:hover {
    background: rgba(206, 89, 89, 0.05);
}

.empty-orders {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.empty-orders h3 {
    font-size: 1.5rem;
    color: #333;
    margin-bottom: 1rem;
}

.empty-orders p {
    color: var(--text-gray);
    margin-bottom: 2rem;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

.pagination {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 2rem;
}

.page-item {
    display: inline-block;
}

.page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 8px;
    border: 1px solid var(--border-color);
    background: white;
    color: #333;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s;
}

.page-link:hover {
    background: var(--light-gray);
}

.page-item.active .page-link {
    background: var(--main-maroon);
    color: white;
    border-color: var(--main-maroon);
}

@media screen and (max-width: 768px) {
    .order-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .order-date {
        margin-top: 0.5rem;
    }
    
    .order-summary {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .order-actions {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .btn {
        width: 100%;
    }
}
</style>

<main>
    <div class="orders-container">
        <?php
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error_message'] = "Please login to view your orders";
            header('Location: login.php');
            exit();
        }
        
        // Display success message if set
        if (isset($_SESSION['success_message'])) {
            echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
            unset($_SESSION['success_message']);
        }
        
        // Display error message if set
        if (isset($_SESSION['error_message'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']);
        }
        
        $customer_id = $_SESSION['user_id'];
        
        try {
            include('./includes/dbconnection.php');
            include_once('./includes/functions.php'); // This will include db_procedures.php
            
            // Use the getCustomerOrderHistory function
            $orders = getCustomerOrderHistory($customer_id);
            
            // Even if there's an issue, getCustomerOrderHistory now returns an empty array instead of false
            
            // For pagination we'll handle the results client-side
            // since stored procedures can't be easily combined with LIMIT/OFFSET
            $total_orders = count($orders);
            $orders_per_page = 5;
            $total_pages = ceil($total_orders / $orders_per_page);
            
            // Get current page
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $page = max(1, min($page, $total_pages));
            
            // Calculate slice for current page
            $offset = ($page - 1) * $orders_per_page;
            $current_page_orders = array_slice($orders, $offset, $orders_per_page);
            
            if (count($orders) > 0) {
                ?>
                <div class="page-header">
                    <h1 class="page-title">My Orders</h1>
                    <p class="page-subtitle">View and manage all your orders in one place. Track your order status and review your purchase history.</p>
                </div>
                
                <div class="orders-list">
                    <?php foreach ($current_page_orders as $order) { 
                        // Get order items
                        $itemStmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = :order_id");
                        $itemStmt->bindParam(':order_id', $order['order_id']);
                        $itemStmt->execute();
                        $items = $itemStmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-number">Order #<?php echo $order['order_id']; ?></div>
                            <div class="order-date"><?php echo date('F j, Y, g:i a', strtotime($order['order_date'])); ?></div>
                        </div>
                        
                        <div class="order-body">
                            <div class="order-summary">
                                <div class="order-amount">$<?php echo number_format($order['total_amount'], 2); ?></div>
                                <div class="order-status status-<?php echo $order['order_status']; ?>"><?php echo ucfirst($order['order_status']); ?></div>
                            </div>
                            
                            <div class="order-details">
                                <div class="detail-group">
                                    <div class="detail-label">Shipping Address</div>
                                    <div class="detail-value"><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></div>
                                </div>
                                
                                <div class="detail-group">
                                    <div class="detail-label">Payment Method</div>
                                    <div class="detail-value"><?php echo htmlspecialchars($order['payment_method']); ?></div>
                                </div>
                                
                                <?php if (!empty($order['tracking_number'])): ?>
                                <div class="detail-group">
                                    <div class="detail-label">Tracking Number</div>
                                    <div class="detail-value"><?php echo htmlspecialchars($order['tracking_number']); ?></div>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="order-items">
                                <div class="order-items-header">Order Items (<?php echo count($items); ?>)</div>
                                <div class="item-list">
                                    <?php foreach ($items as $item): ?>
                                    <div class="order-item">
                                        <div class="item-details">
                                            <div class="item-name"><?php echo htmlspecialchars($item['product_name']); ?></div>
                                            <div class="item-price">
                                                $<?php echo number_format($item['price'], 2); ?>
                                                <span class="item-quantity">x<?php echo $item['quantity']; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <div class="order-actions">
                                <?php if ($order['order_status'] === 'pending'): ?>
                                    <a href="order_confirmation.php?order_id=<?php echo $order['order_id']; ?>" class="btn btn-primary">View Details</a>
                                    <a href="modify_order.php?order_id=<?php echo $order['order_id']; ?>" class="btn btn-outline">Modify Order</a>
                                    <a href="process_cancel_order.php?order_id=<?php echo $order['order_id']; ?>" class="btn btn-outline" style="color: #dc3545; border-color: #dc3545;">Cancel Order</a>
                                <?php elseif ($order['order_status'] === 'processing'): ?>
                                    <a href="order_confirmation.php?order_id=<?php echo $order['order_id']; ?>" class="btn btn-primary">View Details</a>
                                    <a href="process_cancel_order.php?order_id=<?php echo $order['order_id']; ?>" class="btn btn-outline" style="color: #dc3545; border-color: #dc3545;">Cancel Order</a>
                                <?php else: ?>
                                    <a href="order_confirmation.php?order_id=<?php echo $order['order_id']; ?>" class="btn btn-primary">View Details</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                
                <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                    <div class="page-item">
                        <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </div>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <div class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </div>
                    <?php endfor; ?>
                    
                    <?php if ($page < $total_pages): ?>
                    <div class="page-item">
                        <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
            <?php } else { ?>
                <div class="empty-orders">
                    <h3>No Orders Found</h3>
                    <p>You haven't placed any orders yet. Start shopping and your orders will appear here.</p>
                    <a href="index.php" class="btn btn-primary">Start Shopping</a>
                </div>
            <?php } 
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Error retrieving orders: ' . $e->getMessage() . '</div>';
        }
        ?>
    </div>
</main>

<?php require_once './includes/footer.php'; ?> 