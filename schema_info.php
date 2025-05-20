<?php include_once('./includes/headerNav.php'); ?>

<div class="overlay" data-overlay></div>

<header>
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

.schema-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.section {
    background: white;
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
    margin-bottom: 2rem;
    padding: 1.5rem;
}

.section-title {
    color: var(--main-maroon);
    font-size: 1.5rem;
    margin-bottom: 1rem;
    font-weight: 700;
    border-bottom: 1px solid var(--border-color);
    padding-bottom: 0.5rem;
}

.schema-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 1rem;
}

.schema-table th {
    background-color: var(--light-gray);
    padding: 0.8rem;
    text-align: left;
    font-weight: 600;
    font-size: 0.9rem;
}

.schema-table td {
    padding: 0.8rem;
    border-top: 1px solid var(--border-color);
    font-size: 0.9rem;
}

.schema-table tr:nth-child(even) {
    background-color: #f9f9f9;
}

.table-name {
    font-weight: 700;
    color: var(--main-maroon);
}

.code-block {
    background-color: #f8f8f8;
    padding: 1rem;
    border-radius: 5px;
    border: 1px solid var(--border-color);
    font-family: monospace;
    white-space: pre-wrap;
    margin-bottom: 1rem;
    font-size: 0.9rem;
    color: #333;
}

.canceled-orders {
    margin-top: 2rem;
}

.canceled-orders-table {
    width: 100%;
    border-collapse: collapse;
}

.canceled-orders-table th {
    background-color: var(--light-gray);
    padding: 0.8rem;
    text-align: left;
    font-weight: 600;
    font-size: 0.9rem;
}

.canceled-orders-table td {
    padding: 0.8rem;
    border-top: 1px solid var(--border-color);
    font-size: 0.9rem;
}
</style>

<main>
    <div class="schema-container">
        <?php
        // Check if user is admin
        if (!isset($_SESSION['customer_role']) || $_SESSION['customer_role'] !== 'admin') {
            echo '<div class="alert alert-danger">You need to be logged in as an admin to view this page</div>';
        } else {
            include('./includes/dbconnection.php');
            
            // Display database schema
            ?>
            <div class="section">
                <h2 class="section-title">Database Schema</h2>
                <p>The database schema includes the following main tables with their relationships:</p>
                
                <ul>
                    <li><strong>customer</strong> - Stores user account information</li>
                    <li><strong>orders</strong> - Stores order header information</li>
                    <li><strong>order_items</strong> - Stores order line items linked to orders</li>
                    <li><strong>product tables</strong> - Various product tables like Laptops, Desktops, etc.</li>
                    <li><strong>canceled_orders_history</strong> - Records canceled orders</li>
                </ul>
                
                <h3 style="margin-top: 1.5rem;">Key Relationships:</h3>
                <ul>
                    <li>orders.customer_id → customer.customer_id</li>
                    <li>order_items.order_id → orders.order_id</li>
                    <li>order_items.product_id + order_items.product_type → dynamic product tables</li>
                    <li>canceled_orders_history.order_id → orders.order_id</li>
                </ul>
            </div>
            
            <div class="section">
                <h2 class="section-title">Implemented Database Procedures</h2>
                
                <h3>GetOrderDetails</h3>
                <p>This procedure fetches complete order details including customer information and all order items:</p>
                <div class="code-block">CALL GetOrderDetails(order_id);</div>
                
                <h3>FinalizeOrder</h3>
                <p>This procedure handles order creation:</p>
                <div class="code-block">CALL FinalizeOrder(customer_id, shipping_address, billing_address, payment_method, order_notes, @new_order_id);</div>
                
                <h3>GetCustomerOrderHistory</h3>
                <p>This procedure returns a customer's order history:</p>
                <div class="code-block">CALL GetCustomerOrderHistory(customer_id);</div>
            </div>
            
            <div class="section">
                <h2 class="section-title">Implemented Triggers</h2>
                
                <h3>after_order_items_insert</h3>
                <p>This trigger automatically updates product stock after order item insertion.</p>
                
                <h3>before_order_items_insert</h3>
                <p>This trigger prevents order item insertion if requested quantity exceeds available stock.</p>
                
                <h3>after_order_status_update</h3>
                <p>This trigger restores product stock when an order is canceled and logs to the canceled_orders_history table.</p>
            </div>
            
            <div class="section">
                <h2 class="section-title">Canceled Orders History</h2>
                
                <?php
                // Fetch canceled orders history
                try {
                    $stmt = $conn->query("SELECT h.*, o.order_date, c.customer_fname, c.customer_email 
                                         FROM canceled_orders_history h
                                         JOIN orders o ON h.order_id = o.order_id
                                         JOIN customer c ON h.customer_id = c.customer_id
                                         ORDER BY h.cancel_date DESC");
                    $canceled_orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    if (count($canceled_orders) > 0) {
                        ?>
                        <table class="canceled-orders-table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Order Date</th>
                                    <th>Cancel Date</th>
                                    <th>Amount</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($canceled_orders as $order): ?>
                                <tr>
                                    <td><?php echo $order['order_id']; ?></td>
                                    <td>
                                        <?php echo htmlspecialchars($order['customer_fname']); ?><br>
                                        <small><?php echo htmlspecialchars($order['customer_email']); ?></small>
                                    </td>
                                    <td><?php echo date('Y-m-d H:i', strtotime($order['order_date'])); ?></td>
                                    <td><?php echo date('Y-m-d H:i', strtotime($order['cancel_date'])); ?></td>
                                    <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($order['reason'] ?: 'Not specified'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php
                    } else {
                        echo '<p>No canceled orders found in the history.</p>';
                    }
                } catch (PDOException $e) {
                    echo '<div class="alert alert-danger">Error retrieving canceled orders: ' . $e->getMessage() . '</div>';
                }
                ?>
            </div>
            <?php
        }
        ?>
    </div>
</main>

<?php require_once './includes/footer.php'; ?> 