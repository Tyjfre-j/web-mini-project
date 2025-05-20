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

.confirmation-container {
    max-width: 900px;
    margin: 2rem auto;
    padding: 2rem;
}

.confirmation-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    margin-bottom: 2rem;
    position: relative;
}

.card-header {
    background: linear-gradient(135deg, var(--main-maroon), var(--deep-maroon));
    color: white;
    padding: 1.5rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
}

.card-header h1 {
    font-size: 1.5rem;
    margin: 0;
    font-weight: 600;
}

.card-body {
    padding: 2rem;
}

.success-icon {
    display: flex;
    justify-content: center;
    margin-bottom: 1.5rem;
}

.success-icon svg {
    width: 80px;
    height: 80px;
    color: #4CAF50;
}

.order-message {
    text-align: center;
    margin-bottom: 2rem;
}

.order-message h2 {
    font-size: 1.8rem;
    color: #333;
    margin-bottom: 1rem;
}

.order-message p {
    color: var(--text-gray);
    font-size: 1.1rem;
    line-height: 1.6;
    max-width: 600px;
    margin: 0 auto;
}

.order-details {
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    background: var(--light-gray);
}

.order-details h3 {
    font-size: 1.3rem;
    margin-bottom: 1.2rem;
    color: #333;
    padding-bottom: 0.8rem;
    border-bottom: 1px solid var(--border-color);
}

.detail-row {
    display: flex;
    margin-bottom: 0.8rem;
}

.detail-label {
    font-weight: 600;
    width: 180px;
    color: var(--text-gray);
}

.detail-value {
    flex: 1;
    color: #333;
}

.order-items {
    margin-top: 2rem;
}

.order-items h3 {
    font-size: 1.3rem;
    margin-bottom: 1.2rem;
    color: #333;
}

.item-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.item-table th {
    background: var(--light-gray);
    padding: 1rem;
    font-weight: 600;
    text-align: left;
    color: var(--text-gray);
    border-bottom: 1px solid var(--border-color);
}

.item-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
    background: white;
}

.item-table tr:last-child td {
    border-bottom: none;
}

.actions {
    margin-top: 2rem;
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.btn {
    padding: 0.8rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s;
    text-decoration: none;
    display: inline-block;
    text-align: center;
    cursor: pointer;
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

@media screen and (max-width: 768px) {
    .confirmation-container {
        padding: 1rem;
    }
    
    .card-header {
        padding: 1.2rem;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .detail-row {
        flex-direction: column;
        margin-bottom: 1.2rem;
    }
    
    .detail-label {
        width: 100%;
        margin-bottom: 0.3rem;
    }
    
    .actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
    }
}
</style>

<main>
    <div class="confirmation-container">
        <?php
        if (isset($_SESSION['success_message'])) {
            echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
            unset($_SESSION['success_message']);
        }
        
        // Check if order_id is provided
        if (!isset($_GET['order_id'])) {
            header('Location: orders.php');
            exit();
        }

        $order_id = $_GET['order_id'];
        $user_id = $_SESSION['user_id'] ?? 0;

        try {
            include('./includes/dbconnection.php');
            
            // Use the GetOrderDetails stored procedure to fetch order information
            $stmt = $conn->prepare("CALL GetOrderDetails(:order_id)");
            $stmt->bindParam(':order_id', $order_id);
            $stmt->execute();
            
            // Fetch order header information (first result set)
            $order = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // If no order found or order doesn't belong to logged in user
            if (!$order || ($user_id && $order['customer_id'] != $user_id)) {
                $_SESSION['error_message'] = "Order not found or you don't have permission to view it";
                header('Location: orders.php');
                exit();
            }
            
            // Move to the next result set (order items)
            $stmt->nextRowset();
            
            // Fetch all items
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            ?>
            
            <div class="confirmation-card">
                <div class="card-header">
                    <h1>Order Confirmation</h1>
                    <span>Order #<?php echo $order_id; ?></span>
                </div>
                
                <div class="card-body">
                    <div class="success-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    
                    <div class="order-message">
                        <h2>Thank You for Your Order!</h2>
                        <p>Your order has been placed successfully. We are processing it and will ship it as soon as possible.</p>
                    </div>
                    
                    <div class="order-details">
                        <h3>Order Details</h3>
                        
                        <div class="detail-row">
                            <div class="detail-label">Order Number:</div>
                            <div class="detail-value">#<?php echo $order_id; ?></div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">Order Date:</div>
                            <div class="detail-value"><?php echo date('F j, Y, g:i a', strtotime($order['order_date'])); ?></div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">Order Status:</div>
                            <div class="detail-value">
                                <span class="status-badge"><?php echo ucfirst($order['order_status']); ?></span>
                            </div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">Customer Name:</div>
                            <div class="detail-value"><?php echo htmlspecialchars($order['customer_fname']); ?></div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">Email:</div>
                            <div class="detail-value"><?php echo htmlspecialchars($order['customer_email']); ?></div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">Phone:</div>
                            <div class="detail-value"><?php echo htmlspecialchars($order['customer_phone']); ?></div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">Payment Method:</div>
                            <div class="detail-value"><?php echo htmlspecialchars($order['payment_method']); ?></div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">Shipping Address:</div>
                            <div class="detail-value"><?php echo nl2br(htmlspecialchars($order['shipping_address'])); ?></div>
                        </div>
                    </div>
                    
                    <div class="order-items">
                        <h3>Order Items</h3>
                        
                        <table class="item-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td>$<?php echo number_format($item['subtotal'], 2); ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td colspan="3" style="text-align: right; font-weight: 600;">Total:</td>
                                    <td style="font-weight: 600;">$<?php echo number_format($order['total_amount'], 2); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="actions">
                        <a href="profile.php" class="btn btn-primary">View My Orders</a>
                        <a href="index.php" class="btn btn-outline">Continue Shopping</a>
                    </div>
                </div>
            </div>
            
        <?php
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Error retrieving order details: " . $e->getMessage();
            header('Location: orders.php');
            exit();
        }
        ?>
    </div>
</main>

<?php require_once './includes/footer.php'; ?> 