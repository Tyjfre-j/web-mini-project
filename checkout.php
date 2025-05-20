<?php include_once('./includes/headerNav.php'); ?>
<div class="overlay" data-overlay></div>
<!--
    - HEADER
  -->
<header>
  <!-- desktop nav in php -->
  <!-- inc/desktopnav.php -->
  <?php require_once './includes/desktopnav.php' ?>
  <!-- mobile nav in php -->
  <!-- inc/mobilenav.php -->
  <?php require_once './includes/mobilenav.php'; ?>

    <style>
        * {
    font-family: Arial, Helvetica, sans-serif;
    box-sizing: border-box;

}
:root{
    --main-maroon: #CE5959;
    --deep-maroon: #89375F;
    --light-gray: #f5f5f5;
    --border-color: #e2e2e2;
}

.appointments-section {
    width: 80%;
    margin-left: auto;
    margin-right: auto;
    margin-top: 20px;
    margin-bottom: 50px;
}

input {
    border: none;
    outline: none;
}

.appointment-heading {
    text-align: center;
    margin-bottom: 30px;
}

.appointment-head {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 10px;
    color: var(--main-maroon);
}

.appointment-desc {
    color: #666;
    font-size: 16px;
    max-width: 600px;
    margin: 0 auto 15px;
    line-height: 1.6;
}

.appointment-line {
    width: 160px;
    height: 3px;
    border-radius: 10px;
    background-color: var(--main-maroon);
    display: inline-block;
}

.edit-detail-field .child-detail-inner {
    width: 100%;
    display: flex;
    margin-top: 10px;
    justify-content: space-between;
    margin-left: auto;
    margin-right: auto;
}

.checkout-content {
    display: flex;
    gap: 30px;
    align-items: flex-start;
}

.checkout-form {
    flex: 1;
}

.order-summary {
    width: 350px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    padding: 20px;
    position: sticky;
    top: 20px;
}

.summary-header {
    font-size: 18px;
    font-weight: 600;
    padding-bottom: 15px;
    margin-bottom: 15px;
    border-bottom: 1px solid var(--border-color);
    color: #333;
}

.summary-items {
    margin-bottom: 20px;
    max-height: 300px;
    overflow-y: auto;
}

.summary-item {
    display: flex;
    padding: 10px 0;
    border-bottom: 1px solid var(--border-color);
}

.summary-item-img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 5px;
    margin-right: 15px;
}

.summary-item-details {
    flex: 1;
}

.summary-item-name {
    font-weight: 500;
    margin-bottom: 5px;
    color: #333;
    font-size: 14px;
}

.summary-item-price {
    color: #666;
    font-size: 13px;
}

.summary-item-qty {
    background: var(--light-gray);
    border-radius: 3px;
    padding: 2px 8px;
    font-size: 12px;
    color: #555;
    display: inline-block;
    margin-left: 5px;
}

.summary-totals {
    padding-top: 15px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 5px 0;
    font-size: 14px;
    color: #555;
}

.summary-total {
    font-weight: 600;
    font-size: 16px;
    padding-top: 10px;
    margin-top: 10px;
    border-top: 1px solid var(--border-color);
    color: #333;
}

.Add-child-section {
    margin-top: 40px;
}

.Add-child-section .child-heading-t {
    font-size: 22px;
    font-weight: 700;
    color: var(--main-maroon);
    margin-bottom: 20px;
}

.Add-child-section .child-fields1 {
    width: 49%;
    height: 55px;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    margin-bottom: 25px;
    padding: 15px;
    background-color: #FFFFFF;
    position: relative;
    box-shadow: 1px 1px 3px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.Add-child-section .child-fields1:focus-within {
    border-color: var(--main-maroon);
    box-shadow: 0 0 0 2px rgba(206,89,89,0.1);
}

.Add-child-section .child-fields1 input {
    color: #333;
    font-weight: 500;
    width: 100%;
    background-color: transparent;
    font-size: 15px;
}

.Add-child-section .child-fields1::before {
    position: absolute;
    content: "First Name";
    top: -10px;
    left: 10px;
    background-color: white;
    padding: 0 8px;
    color: var(--main-maroon);
    font-weight: 600;
    font-size: 13px;
}

.Add-child-section .child-fields3 {
    width: 49%;
    height: 55px;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    margin-bottom: 25px;
    padding: 15px;
    background-color: #FFFFFF;
    position: relative;
    box-shadow: 1px 1px 3px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.Add-child-section .child-fields3:focus-within {
    border-color: var(--main-maroon);
    box-shadow: 0 0 0 2px rgba(206,89,89,0.1);
}

.Add-child-section .child-fields3 input {
    color: #333;
    font-weight: 500;
    width: 100%;
    background-color: transparent;
    font-size: 15px;
}

.Add-child-section .child-fields3::before {
    position: absolute;
    content: "Last Name";
    top: -10px;
    left: 10px;
    background-color: white;
    padding: 0 8px;
    color: var(--main-maroon);
    font-weight: 600;
    font-size: 13px;
}

.Add-child-section .child-fields4 {
    width: 49%;
    height: 55px;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    margin-bottom: 25px;
    padding: 15px;
    background-color: #FFFFFF;
    position: relative;
    box-shadow: 1px 1px 3px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.Add-child-section .child-fields4:focus-within {
    border-color: var(--main-maroon);
    box-shadow: 0 0 0 2px rgba(206,89,89,0.1);
}

.Add-child-section .child-fields4::before {
    position: absolute;
    content: "Address Line 1";
    top: -10px;
    left: 10px;
    background-color: white;
    padding: 0 8px;
    color: var(--main-maroon);
    font-weight: 600;
    font-size: 13px;
}

.Add-child-section .child-fields4 input {
    color: #333;
    font-weight: 500;
    width: 100%;
    background-color: transparent;
    font-size: 15px;
}

.Add-child-section .child-fields5 {
    width: 49%;
    height: 55px;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    margin-bottom: 25px;
    padding: 15px;
    background-color: #FFFFFF;
    position: relative;
    box-shadow: 1px 1px 3px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.Add-child-section .child-fields5:focus-within {
    border-color: var(--main-maroon);
    box-shadow: 0 0 0 2px rgba(206,89,89,0.1);
}

.Add-child-section .child-fields5::before {
    position: absolute;
    content: "Address Line 2";
    top: -10px;
    left: 10px;
    background-color: white;
    padding: 0 8px;
    color: var(--main-maroon);
    font-weight: 600;
    font-size: 13px;
}

.Add-child-section .child-fields5 input {
    color: #333;
    font-weight: 500;
    width: 100%;
    background-color: transparent;
    font-size: 15px;
}

.Add-child-section .child-fields6 {
    width: 32%;
    height: 55px;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    margin-bottom: 25px;
    padding: 15px;
    background-color: #FFFFFF;
    position: relative;
    box-shadow: 1px 1px 3px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.Add-child-section .child-fields6:focus-within {
    border-color: var(--main-maroon);
    box-shadow: 0 0 0 2px rgba(206,89,89,0.1);
}

.Add-child-section .child-fields6::before {
    position: absolute;
    content: "City";
    top: -10px;
    left: 10px;
    background-color: white;
    padding: 0 8px;
    color: var(--main-maroon);
    font-weight: 600;
    font-size: 13px;
}

.Add-child-section .child-fields6 input {
    color: #333;
    font-weight: 500;
    width: 100%;
    background-color: transparent;
    font-size: 15px;
}

.Add-child-section .child-fields7 {
    width: 32%;
    height: 55px;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    margin-bottom: 25px;
    padding: 15px;
    background-color: #FFFFFF;
    position: relative;
    box-shadow: 1px 1px 3px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.Add-child-section .child-fields7:focus-within {
    border-color: var(--main-maroon);
    box-shadow: 0 0 0 2px rgba(206,89,89,0.1);
}

.Add-child-section .child-fields7::before {
    position: absolute;
    content: "State/Region";
    top: -10px;
    left: 10px;
    background-color: white;
    padding: 0 8px;
    color: var(--main-maroon);
    font-weight: 600;
    font-size: 13px;
}

.Add-child-section .child-fields7 input {
    color: #333;
    font-weight: 500;
    width: 100%;
    background-color: transparent;
    font-size: 15px;
}

.Add-child-section .child-fields8 {
    width: 32%;
    height: 55px;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    margin-bottom: 25px;
    padding: 15px;
    background-color: #FFFFFF;
    position: relative;
    box-shadow: 1px 1px 3px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}

.Add-child-section .child-fields8:focus-within {
    border-color: var(--main-maroon);
    box-shadow: 0 0 0 2px rgba(206,89,89,0.1);
}

.Add-child-section .child-fields8::before {
    position: absolute;
    content: "Postal/Zip Code";
    top: -10px;
    left: 10px;
    background-color: white;
    padding: 0 8px;
    color: var(--main-maroon);
    font-weight: 600;
    font-size: 13px;
}

.Add-child-section .child-fields8 input {
    color: #333;
    font-weight: 500;
    width: 100%;
    background-color: transparent;
    font-size: 15px;
}

.payment-section {
    margin-top: 30px;
}

.payment-methods {
    display: flex;
    gap: 15px;
    margin-bottom: 25px;
}

.payment-method {
    flex: 1;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    padding: 15px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    position: relative;
}

.payment-method.active {
    border-color: var(--main-maroon);
    background-color: rgba(206,89,89,0.05);
}

.payment-method input {
    position: absolute;
    opacity: 0;
}

.payment-method label {
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.payment-method-icon {
    font-size: 24px;
    color: #555;
}

.payment-method.active .payment-method-icon {
    color: var(--main-maroon);
}

.payment-method-title {
    font-weight: 600;
    color: #555;
}

.payment-method.active .payment-method-title {
    color: var(--main-maroon);
}

.text-area {
    width: 100%;
    min-height: 100px;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 30px;
    font-family: Arial, sans-serif;
    position: relative;
    background: white;
    transition: all 0.3s;
}

.text-area:focus-within {
    border-color: var(--main-maroon);
    box-shadow: 0 0 0 2px rgba(206,89,89,0.1);
}

.text-area::before {
    position: absolute;
    content: "Order Notes (Optional)";
    top: -10px;
    left: 10px;
    background-color: white;
    padding: 0 8px;
    color: var(--main-maroon);
    font-weight: 600;
    font-size: 13px;
}

.text-area textarea {
    width: 100%;
    height: 100%;
    border: none;
    outline: none;
    background: transparent;
    resize: vertical;
    color: #333;
}

.place-order-btn {
    width: 100%;
    background: linear-gradient(135deg, var(--main-maroon), var(--deep-maroon));
    color: white;
    border: none;
    padding: 16px 30px;
    font-size: 16px;
    font-weight: 600;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    box-shadow: 0 4px 15px rgba(206,89,89,0.3);
}

.place-order-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(206,89,89,0.4);
}

.place-order-btn:active {
    transform: translateY(1px);
}

.alert {
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-weight: 500;
}

.alert-danger {
    background-color: #FFEBEE;
    color: #D32F2F;
    border: 1px solid #FFCDD2;
}

.alert-success {
    background-color: #E8F5E9;
    color: #388E3C;
    border: 1px solid #C8E6C9;
}

@media (max-width: 992px) {
    .appointments-section {
        width: 90%;
    }

    .checkout-content {
        flex-direction: column;
    }

    .order-summary {
        width: 100%;
        margin-top: 30px;
        position: static;
    }
}

@media (max-width: 768px) {
    .appointments-section {
        width: 95%;
    }
    
    .Add-child-section .child-detail-inner {
        flex-direction: column;
    }
    
    .Add-child-section .child-fields1,
    .Add-child-section .child-fields3,
    .Add-child-section .child-fields4,
    .Add-child-section .child-fields5,
    .Add-child-section .child-fields6,
    .Add-child-section .child-fields7,
    .Add-child-section .child-fields8 {
        width: 100%;
}

    .payment-methods {
        flex-direction: column;
    }
}
    </style>

<?php
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Please login to complete your checkout";
    header('Location: login.php');
    exit();
}

// Check if cart is empty
if (!isset($_SESSION['mycart']) || empty($_SESSION['mycart'])) {
    $_SESSION['error_message'] = "Your cart is empty";
    header('Location: cart.php');
    exit();
}

// Fetch user information if logged in
$customer_id = $_SESSION['user_id'];
try {
    include('./includes/dbconnection.php');
    $stmt = $conn->prepare("SELECT * FROM customer WHERE customer_id = :customer_id");
    $stmt->bindParam(':customer_id', $customer_id);
    $stmt->execute();
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Error fetching customer data: " . $e->getMessage();
}
?>
    
                <div class="appointments-section">
    <?php if(isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

                    <div class="appointment-heading">
        <h2 class="appointment-head">Checkout</h2>
        <p class="appointment-desc">Complete your purchase by providing your shipping information and choosing a payment method.</p>
                            <span class="appointment-line"></span>
                        </div>
                   
    <div class="checkout-content">
        <form class="checkout-form" action="process_order.php" method="POST">
            <div class="Add-child-section">
                <div class="child-heading-t">Customer Information</div>
                
                            <div class="child-detail-inner">
                                <div class="child-fields1">
                        <input type="text" name="first_name" value="<?php echo htmlspecialchars($customer['customer_fname'] ?? ''); ?>" required>
                                </div>
                                <div class="child-fields3">
                        <input type="text" name="last_name" value="" placeholder="Your last name">
                            </div>
                                </div>
                                </div>

            <div class="Add-child-section">
                <div class="child-heading-t">Shipping Address</div>
                
                            <div class="child-detail-inner">
                    <div class="child-fields4">
                        <input type="text" name="address_line1" required placeholder="Street address or P.O. Box">
                                </div>
                    <div class="child-fields5">
                        <input type="text" name="address_line2" placeholder="Apartment, suite, unit, building, floor, etc.">
                                </div>
                            </div>
                            
                            <div class="child-detail-inner">
                    <div class="child-fields6">
                        <input type="text" name="city" required placeholder="City">
                                </div>
                    <div class="child-fields7">
                        <input type="text" name="state" required placeholder="State/Province/Region">
                            </div>
                    <div class="child-fields8">
                        <input type="text" name="postal_code" required placeholder="Postal/Zip code">
                                </div>
                                </div>
                            </div>
                         
            <div class="payment-section">
                <div class="child-heading-t">Payment Method</div>
                
                <div class="payment-methods">
                    <div class="payment-method active">
                        <input type="radio" id="payment-cash" name="payment_method" value="Cash on Delivery" checked>
                        <label for="payment-cash">
                            <ion-icon name="cash-outline" class="payment-method-icon"></ion-icon>
                            <span class="payment-method-title">Cash on Delivery</span>
                        </label>
                        </div>

                    <div class="payment-method">
                        <input type="radio" id="payment-card" name="payment_method" value="Credit Card">
                        <label for="payment-card">
                            <ion-icon name="card-outline" class="payment-method-icon"></ion-icon>
                            <span class="payment-method-title">Credit Card</span>
                        </label>
                    </div>

                    <div class="payment-method">
                        <input type="radio" id="payment-bank" name="payment_method" value="Bank Transfer">
                        <label for="payment-bank">
                            <ion-icon name="wallet-outline" class="payment-method-icon"></ion-icon>
                            <span class="payment-method-title">Bank Transfer</span>
                        </label>
                    </div>
                </div>
                
                <div class="text-area">
                    <textarea name="order_notes" placeholder="Add notes about your order (optional)"></textarea>
                </div>
            </div>

            <input type="hidden" name="shipping_address" id="shipping_address">
            <button type="submit" class="place-order-btn">
                <ion-icon name="bag-check-outline"></ion-icon>
                Place Order
            </button>
        </form>
        
        <div class="order-summary">
            <div class="summary-header">Order Summary</div>
            
            <div class="summary-items">
                <?php 
                $subtotal = 0;
                foreach($_SESSION['mycart'] as $item): 
                    $itemTotal = $item['price'] * $item['product_qty'];
                    $subtotal += $itemTotal;
                ?>
                <div class="summary-item">
                    <img src="./admin/upload/<?php echo $item['product_img'] ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="summary-item-img">
                    <div class="summary-item-details">
                        <div class="summary-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                        <div class="summary-item-price">
                            $<?php echo number_format($item['price'], 2); ?>
                            <span class="summary-item-qty">x<?php echo $item['product_qty']; ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="summary-totals">
                <div class="summary-row">
                    <div>Subtotal</div>
                    <div>$<?php echo number_format($subtotal, 2); ?></div>
                </div>
                <div class="summary-row">
                    <div>Shipping</div>
                    <div>Free</div>
                </div>
                <div class="summary-row summary-total">
                    <div>Total</div>
                    <div>$<?php echo number_format($subtotal, 2); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Combine shipping address fields into single hidden field before form submission
document.querySelector('.checkout-form').addEventListener('submit', function(e) {
    const addressLine1 = document.querySelector('input[name="address_line1"]').value;
    const addressLine2 = document.querySelector('input[name="address_line2"]').value;
    const city = document.querySelector('input[name="city"]').value;
    const state = document.querySelector('input[name="state"]').value;
    const postalCode = document.querySelector('input[name="postal_code"]').value;
    
    const fullAddress = [
        addressLine1,
        addressLine2,
        `${city}, ${state} ${postalCode}`
    ].filter(Boolean).join("\n");
    
    document.getElementById('shipping_address').value = fullAddress;
});

// Payment method selection
document.querySelectorAll('.payment-method').forEach(method => {
    method.addEventListener('click', function() {
        document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('active'));
        this.classList.add('active');
        this.querySelector('input').checked = true;
    });
});
</script>

<?php require_once './includes/footer.php'; ?>
