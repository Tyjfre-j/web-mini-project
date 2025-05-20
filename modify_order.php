<?php include_once('./includes/headerNav.php'); ?>

<header>
  <?php require_once './includes/desktopnav.php' ?>
  <?php require_once './includes/mobilenav.php'; ?>
</header>

<?php
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Please login to modify your order";
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

try {
    include('./includes/dbconnection.php');
    
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
    
    $order = $verifyStmt->fetch(PDO::FETCH_ASSOC);
    
    // Get order items
    $itemStmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = :order_id");
    $itemStmt->bindParam(':order_id', $order_id);
    $itemStmt->execute();
    $items = $itemStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get customer info
    $customerStmt = $conn->prepare("SELECT * FROM customer WHERE customer_id = :customer_id");
    $customerStmt->bindParam(':customer_id', $user_id);
    $customerStmt->execute();
    $customer = $customerStmt->fetch(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Error retrieving order: " . $e->getMessage();
    header('Location: orders.php');
    exit();
}
?>

<style>
:root {
    --main-maroon: #CE5959;
    --deep-maroon: #89375F;
    --light-gray: #f5f5f5;
    --border-color: #e2e2e2;
}

.modify-container {
    max-width: 800px;
    margin: 2rem auto;
    padding: 1.5rem;
    background-color: #fff;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
    border-radius: 10px;
}

.modify-header {
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.modify-title {
    font-size: 1.5rem;
    color: var(--main-maroon);
    margin-bottom: 0.5rem;
    font-weight: 700;
}

.modify-subtitle {
    color: #666;
    font-size: 0.9rem;
}

.form-group {
    margin-bottom: 1.2rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    font-size: 0.9rem;
    color: #333;
}

.form-control {
    width: 100%;
    padding: 0.8rem;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    font-size: 0.9rem;
}

.form-control:focus {
    outline: none;
    border-color: var(--main-maroon);
    box-shadow: 0 0 0 2px rgba(206, 89, 89, 0.1);
}

.item-list {
    margin-bottom: 1.5rem;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    overflow: hidden;
}

.item-table {
    width: 100%;
    border-collapse: collapse;
}

.item-table th {
    background-color: var(--light-gray);
    padding: 0.8rem;
    text-align: left;
    font-weight: 600;
    font-size: 0.85rem;
}

.item-table td {
    padding: 0.8rem;
    border-top: 1px solid var(--border-color);
    font-size: 0.9rem;
}

.quantity-input {
    width: 60px;
    padding: 0.4rem;
    text-align: center;
    border: 1px solid var(--border-color);
    border-radius: 4px;
}

.btn-container {
    margin-top: 1.5rem;
    display: flex;
    justify-content: space-between;
}

.btn {
    padding: 0.8rem 1.5rem;
    border-radius: 6px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    text-align: center;
    transition: all 0.3s;
}

.btn-primary {
    background: linear-gradient(135deg, var(--main-maroon), var(--deep-maroon));
    color: white;
    border: none;
}

.btn-outline {
    background: transparent;
    color: #666;
    border: 1px solid var(--border-color);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(206, 89, 89, 0.2);
}

.btn-outline:hover {
    background-color: var(--light-gray);
}

@media (max-width: 768px) {
    .modify-container {
        padding: 1rem;
    }
    
    .btn-container {
        flex-direction: column;
        gap: 0.8rem;
    }
    
    .btn {
        width: 100%;
    }
}
</style>

<div class="modify-container">
    <div class="modify-header">
        <h1 class="modify-title">Modify Order #<?php echo $order_id; ?></h1>
        <p class="modify-subtitle">You can update your shipping address, payment method, or item quantities below.</p>
    </div>
    
    <form action="process_modify_order.php" method="post">
        <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
        
        <div class="form-group">
            <label class="form-label" for="shipping_address">Shipping Address</label>
            <textarea class="form-control" id="shipping_address" name="shipping_address" rows="3"><?php echo htmlspecialchars($order['shipping_address']); ?></textarea>
        </div>
        
        <div class="form-group">
            <label class="form-label" for="payment_method">Payment Method</label>
            <select class="form-control" id="payment_method" name="payment_method">
                <option value="Cash on Delivery" <?php echo ($order['payment_method'] === 'Cash on Delivery') ? 'selected' : ''; ?>>Cash on Delivery</option>
                <option value="Credit Card" <?php echo ($order['payment_method'] === 'Credit Card') ? 'selected' : ''; ?>>Credit Card</option>
                <option value="PayPal" <?php echo ($order['payment_method'] === 'PayPal') ? 'selected' : ''; ?>>PayPal</option>
                <option value="Bank Transfer" <?php echo ($order['payment_method'] === 'Bank Transfer') ? 'selected' : ''; ?>>Bank Transfer</option>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label" for="order_notes">Order Notes</label>
            <textarea class="form-control" id="order_notes" name="order_notes" rows="2"><?php echo htmlspecialchars($order['order_notes']); ?></textarea>
        </div>
        
        <h2 style="font-size: 1.1rem; margin: 1.5rem 0 0.8rem;">Order Items</h2>
        <div class="item-list">
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
                    <?php 
                    $total = 0;
                    foreach ($items as $index => $item): 
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td>
                            <input type="hidden" name="item_ids[]" value="<?php echo $item['item_id']; ?>">
                            <input type="number" class="quantity-input" name="quantities[]" value="<?php echo $item['quantity']; ?>" min="1" max="10">
                        </td>
                        <td>$<?php echo number_format($subtotal, 2); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right; font-weight: 600;">Total:</td>
                        <td style="font-weight: 700;">$<?php echo number_format($total, 2); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div class="btn-container">
            <a href="orders.php" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Order</button>
        </div>
    </form>
</div>

<?php require_once './includes/footer.php'; ?> 