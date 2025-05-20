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
    --light-red: #ffefef;
    --light-blue: #f0f7ff;
    --accent-blue: #3a86ff;
    --accent-green: #2ecc71;
    --accent-yellow: #ffd43b;
    --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    --card-radius: 12px;
    --transition-fast: 0.2s ease;
    --transition-medium: 0.3s ease;
}

.product-container {
    padding: 2rem 1rem;
    max-width: 1200px;
    margin: 0 auto;
}

/* Cart header */
.cart-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    animation: fadeSlideDown 0.5s ease-out;
}

.cart-title {
    font-size: 1.8rem;
    color: #333;
    font-weight: 700;
    margin: 0;
    position: relative;
}

.cart-title::after {
    content: '';
    position: absolute;
    height: 3px;
    width: 50px;
    background: linear-gradient(90deg, var(--main-maroon), var(--deep-maroon));
    left: 0;
    bottom: -8px;
    border-radius: 2px;
}

.continue-shopping {
    display: inline-flex;
    align-items: center;
    color: var(--deep-maroon);
    text-decoration: none;
    font-weight: 500;
    padding: 8px 16px;
    border-radius: 30px;
    background-color: rgba(137, 55, 95, 0.08);
    transition: all var(--transition-fast);
}

.continue-shopping ion-icon {
    margin-right: 6px;
    font-size: 18px;
}

.continue-shopping:hover {
    color: white;
    background-color: var(--deep-maroon);
    text-decoration: none;
    transform: translateX(-5px);
}

/* Cart table */
.cart-table-container {
    border-radius: var(--card-radius);
    box-shadow: var(--box-shadow);
    overflow: hidden;
    background: white;
    animation: fadeSlideUp 0.5s ease-out;
    animation-delay: 0.1s;
    opacity: 0;
    animation-fill-mode: forwards;
}

.cart-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin-bottom: 0;
}

.cart-table th {
    background: white;
    padding: 1.2rem 1rem;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    color: var(--text-gray);
    border-bottom: 2px solid var(--light-gray);
    letter-spacing: 0.5px;
}

.cart-table td {
    padding: 1.2rem 1rem;
    vertical-align: middle;
    border-bottom: 1px solid var(--border-color);
    transition: background-color var(--transition-fast);
}

.cart-table tr:hover td {
    background-color: rgba(245, 245, 245, 0.5);
}

.cart-table tr:last-child td {
    border-bottom: none;
}

.cart-product-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: var(--card-radius);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform var(--transition-medium), box-shadow var(--transition-medium);
}

.cart-product-image:hover {
    transform: scale(1.08);
    box-shadow: 0 8px 16px rgba(0,0,0,0.15);
}

.product-name {
    font-weight: 600;
    color: #333;
    font-size: 1rem;
    margin-bottom: 4px;
    transition: color var(--transition-fast);
}

.product-name:hover {
    color: var(--main-maroon);
}

.product-category {
    color: var(--text-gray);
    font-size: 0.85rem;
    display: block;
    margin-top: 3px;
    background-color: var(--light-gray);
    padding: 2px 8px;
    border-radius: 20px;
    display: inline-block;
}

.product-price {
    font-weight: 600;
    color: var(--deep-maroon);
    font-size: 1.1rem;
}

/* Quantity Input */
.quantity-controls {
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--border-color);
    border-radius: 30px;
    overflow: hidden;
    width: fit-content;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    transition: box-shadow var(--transition-fast);
}

.quantity-controls:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.quantity-btn {
    background: white;
    border: none;
    width: 35px;
    height: 35px;
    font-size: 1.2rem;
    color: var(--text-gray);
    cursor: pointer;
    transition: all var(--transition-fast);
    display: flex;
    align-items: center;
    justify-content: center;
}

.quantity-btn:hover {
    background-color: var(--light-gray);
    color: #333;
}

.quantity-btn.minus:hover {
    color: var(--main-maroon);
}

.quantity-btn.plus:hover {
    color: var(--accent-green);
}

.quantity-input {
    width: 50px;
    height: 35px;
    text-align: center;
    border: none;
    border-left: 1px solid var(--border-color);
    border-right: 1px solid var(--border-color);
    font-weight: 600;
    color: #333;
    -moz-appearance: textfield;
    background-color: white;
}

.quantity-input::-webkit-outer-spin-button,
.quantity-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.quantity-input:focus {
    outline: none;
    background-color: var(--light-blue);
}

/* Delete Button */
.delete-btn {
    background: none;
    border: none;
    padding: 8px;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    cursor: pointer;
    transition: all var(--transition-fast);
    display: flex;
    align-items: center;
    justify-content: center;
}

.delete-icon {
    color: var(--text-gray);
    font-size: 1.3rem;
    transition: color var(--transition-fast);
}

.delete-btn:hover {
    background-color: var(--light-red);
    transform: rotate(5deg);
}

.delete-btn:hover .delete-icon {
    color: var(--main-maroon);
}

/* Cart Summary */
.cart-summary {
    background: white;
    border-radius: var(--card-radius);
    box-shadow: var(--box-shadow);
    padding: 1.5rem;
    margin-bottom: 2rem;
    animation: fadeSlideUp 0.5s ease-out;
    animation-delay: 0.2s;
    opacity: 0;
    animation-fill-mode: forwards;
    position: relative;
    overflow: hidden;
}

.cart-summary::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, var(--main-maroon), var(--deep-maroon));
}

.summary-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #333;
    margin-top: 0;
    margin-bottom: 1.2rem;
    padding-bottom: 0.8rem;
    border-bottom: 1px solid var(--border-color);
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 0.8rem 0;
    color: var(--text-gray);
}

.summary-row.total {
    font-weight: 700;
    color: #333;
    font-size: 1.2rem;
    border-top: 1px solid var(--border-color);
    margin-top: 0.5rem;
    padding-top: 1rem;
}

.summary-row.savings {
    color: var(--accent-green);
    font-weight: 500;
}

.summary-badge {
    background-color: var(--light-blue);
    color: var(--accent-blue);
    font-size: 0.8rem;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 20px;
    display: inline-block;
}

/* Progress Bar */
.checkout-progress {
    margin: 1rem 0;
    background-color: var(--light-gray);
    height: 6px;
    border-radius: 3px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(90deg, var(--accent-blue), var(--accent-green));
    border-radius: 3px;
    transition: width 0.6s ease;
}

.progress-text {
    font-size: 0.8rem;
    color: var(--text-gray);
    margin-bottom: 0.5rem;
    text-align: right;
}

.free-shipping-message {
    display: flex;
    align-items: center;
    margin: 1rem 0;
    padding: 0.8rem;
    background-color: rgba(46, 204, 113, 0.1);
    border-radius: 8px;
    color: var(--accent-green);
    font-size: 0.9rem;
    font-weight: 500;
}

.free-shipping-message ion-icon {
    margin-right: 8px;
    font-size: 1.2rem;
}

/* Buttons */
.checkout-btn {
    background: linear-gradient(135deg, var(--main-maroon), var(--deep-maroon));
    color: white;
    padding: 1rem 2rem;
    border-radius: 50px;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    transition: all var(--transition-medium);
    border: none;
    cursor: pointer;
    width: 100%;
    max-width: 350px;
    margin: 1rem auto 0;
    text-align: center;
    box-shadow: 0 4px 15px rgba(206, 89, 89, 0.25);
    position: relative;
    overflow: hidden;
}

.checkout-btn::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: -100%;
    background: linear-gradient(90deg, 
        rgba(255,255,255,0) 0%, 
        rgba(255,255,255,0.2) 50%, 
        rgba(255,255,255,0) 100%);
    transition: all 0.8s;
}

.checkout-btn:hover {
    background: linear-gradient(135deg, var(--deep-maroon), var(--main-maroon));
    transform: translateY(-3px);
    box-shadow: 0 6px 18px rgba(137, 55, 95, 0.35);
    color: white;
    text-decoration: none;
}

.checkout-btn:hover::after {
    left: 100%;
}

.checkout-btn ion-icon {
    margin-right: 8px;
    font-size: 1.2rem;
    vertical-align: middle;
}

/* Empty Cart */
.empty-cart {
    text-align: center;
    padding: 3rem 1rem;
    background: white;
    border-radius: var(--card-radius);
    box-shadow: var(--box-shadow);
    animation: fadeIn 0.5s ease-out;
}

.empty-cart-icon {
    font-size: 5rem;
    color: var(--light-gray);
    margin-bottom: 1.5rem;
    animation: cartBounce 2s infinite ease-in-out;
}

.empty-cart h2 {
    color: #333;
    margin-bottom: 1rem;
    font-weight: 700;
}

.empty-cart p {
    color: var(--text-gray);
    margin-bottom: 2rem;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
    line-height: 1.6;
}

/* Cart Layout */
.cart-grid {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 2rem;
}

/* Animation Keyframes */
@keyframes cartBounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-15px); }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes fadeSlideDown {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fadeSlideUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Responsive */
@media screen and (max-width: 992px) {
    .cart-grid {
        grid-template-columns: 1fr;
    }
    
    .cart-summary {
        order: -1;
    }
}

@media screen and (max-width: 768px) {
    .cart-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .continue-shopping {
        margin-top: 1rem;
    }

    .cart-table-container {
        overflow-x: auto;
    }
    
    .cart-table {
        min-width: 650px;
    }
    
    .cart-table th,
    .cart-table td {
        padding: 1rem 0.75rem;
    }
    
    .cart-product-image {
        width: 60px;
        height: 60px;
    }
    
    .checkout-btn {
        width: 100%;
    }
    
    .quantity-controls {
        margin: 0;
    }
}

@media screen and (max-width: 576px) {
    .product-container {
        padding: 1rem 0.5rem;
    }
    
    .cart-title {
        font-size: 1.5rem;
    }
    
    .empty-cart {
        padding: 2rem 1rem;
    }
    
    .empty-cart-icon {
        font-size: 4rem;
    }
}

.cart-item {
    transition: all 0.3s ease-out;
}

.cart-item.removing {
    transform: translateX(30px);
    opacity: 0;
}

.quantity-change {
    animation: quantityPulse 0.3s ease;
}

@keyframes quantityPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.03); }
    100% { transform: scale(1); }
}

.stock-indicator {
    display: inline-flex;
    align-items: center;
    font-size: 0.8rem;
    margin-top: 6px;
}

.stock-indicator.in-stock {
    color: var(--accent-green);
}

.stock-indicator.low-stock {
    color: var(--accent-yellow);
}

.stock-indicator ion-icon {
    margin-right: 4px;
    font-size: 1rem;
}

/* Save for Later */
.action-buttons {
    display: flex;
    gap: 10px;
    margin-top: 8px;
}

.action-btn {
    background: none;
    border: none;
    font-size: 0.85rem;
    color: var(--text-gray);
    padding: 0;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    transition: color var(--transition-fast);
}

.action-btn ion-icon {
    margin-right: 4px;
    font-size: 0.95rem;
}

.action-btn:hover {
    color: var(--deep-maroon);
}

/* Related Products */
.related-products {
    margin-top: 3rem;
    padding-top: 1rem;
    border-top: 1px solid var(--border-color);
    animation: fadeSlideUp 0.5s ease-out;
    animation-delay: 0.3s;
    opacity: 0;
    animation-fill-mode: forwards;
}

.related-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 1.5rem;
    text-align: center;
    position: relative;
}

.related-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 3px;
    background: linear-gradient(90deg, var(--main-maroon), var(--deep-maroon));
    border-radius: 2px;
}

.related-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 1.5rem;
}

.related-product {
    background: white;
    border-radius: var(--card-radius);
    overflow: hidden;
    box-shadow: var(--box-shadow);
    transition: transform var(--transition-medium), box-shadow var(--transition-medium);
}

.related-product:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
}

.related-img {
    height: 180px;
    width: 100%;
    object-fit: cover;
}

.related-info {
    padding: 1rem;
}

.related-name {
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
}

.related-price {
    color: var(--deep-maroon);
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.related-add {
    display: block;
    text-align: center;
    background-color: var(--accent-blue);
    color: white;
    border: none;
    border-radius: 30px;
    padding: 0.5rem;
    cursor: pointer;
    width: 100%;
    transition: all var(--transition-fast);
    font-weight: 600;
    font-size: 0.85rem;
}

.related-add:hover {
    background-color: var(--deep-maroon);
}

.promo-banner {
    background: linear-gradient(135deg, #3a86ff, #8338ec);
    border-radius: var(--card-radius);
    padding: 1.5rem;
    margin-bottom: 2rem;
    color: white;
    text-align: center;
    box-shadow: 0 4px 15px rgba(58, 134, 255, 0.3);
    animation: fadeSlideDown 0.5s ease-out;
    animation-delay: 0.1s;
    opacity: 0;
    animation-fill-mode: forwards;
}

.promo-title {
    font-size: 1.4rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.promo-text {
    font-size: 0.95rem;
    margin-bottom: 1rem;
    opacity: 0.9;
}

.promo-code {
    background: rgba(255, 255, 255, 0.2);
    padding: 0.5rem 1rem;
    border-radius: 50px;
    display: inline-block;
    font-weight: 700;
    letter-spacing: 1px;
    backdrop-filter: blur(5px);
    border: 1px dashed rgba(255, 255, 255, 0.5);
    margin-bottom: 1rem;
}

/* Toast Notification */
.toast-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 100;
}

.toast {
    background: white;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 300px;
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.3s ease;
}

.toast.show {
    opacity: 1;
    transform: translateY(0);
}

.toast-icon {
    font-size: 1.5rem;
}

.toast-content {
    flex: 1;
}

.toast-title {
    font-weight: 700;
    color: #333;
    margin-bottom: 0.2rem;
}

.toast-message {
    font-size: 0.85rem;
    color: var(--text-gray);
}

.toast-success .toast-icon {
    color: var(--accent-green);
}

.toast-info .toast-icon {
    color: var(--accent-blue);
}

/* Loading Spinner */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(255, 255, 255, 0.7);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s, visibility 0.3s;
}

.loading-overlay.active {
    opacity: 1;
    visibility: visible;
}

.spinner {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 3px solid rgba(137, 55, 95, 0.1);
    border-top-color: var(--deep-maroon);
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Item Removal Animation */
@keyframes itemRemove {
    0% { 
        transform: translateX(0);
        opacity: 1;
    }
    20% { 
        transform: translateX(30px); 
        opacity: 0.8;
    }
    100% { 
        transform: translateX(-100%);
        opacity: 0;
        height: 0;
        padding: 0;
        margin: 0;
        border: none;
    }
}

.removing-item {
    animation: itemRemove 0.5s ease forwards;
    overflow: hidden;
}

/* Quantity Badge */
.cart-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background-color: var(--main-maroon);
    color: white;
    border-radius: 50%;
    width: 18px;
    height: 18px;
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s ease;
}

.cart-badge.pulse {
    animation: badgePulse 0.5s ease;
}

@keyframes badgePulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.5); }
    100% { transform: scale(1); }
}

/* Cart Button Styles */
.cart-icon-container {
    position: relative;
    display: inline-block;
}

.floating-cart {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--main-maroon), var(--deep-maroon));
    color: white;
    position: fixed;
    bottom: 30px;
    right: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(206, 89, 89, 0.3);
    cursor: pointer;
    z-index: 99;
    transition: transform var(--transition-medium);
}

.floating-cart:hover {
    transform: translateY(-5px);
}

.floating-cart ion-icon {
    font-size: 1.8rem;
}
</style>

<main>
    <?php 
    // Get cart total items count for badge
    $cartTotalItems = 0;
    if(isset($_SESSION['mycart'])) {
        foreach($_SESSION['mycart'] as $item) {
            $cartTotalItems += $item['product_qty'];
        }
    }
    ?>

    <!-- Floating Cart Button -->
    <div class="floating-cart" onclick="window.location.href='cart.php'">
        <div class="cart-icon-container">
            <ion-icon name="cart-outline"></ion-icon>
            <?php if($cartTotalItems > 0): ?>
            <span class="cart-badge"><?php echo $cartTotalItems; ?></span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay">
        <div class="spinner"></div>
    </div>

    <div class="product-container">
        <?php if(isset($_SESSION['mycart']) && !empty($_SESSION['mycart'])): ?>
            <?php if(rand(0, 1)): /* Show promo randomly for demo */ ?>
            <div class="promo-banner">
                <div class="promo-title">Special Discount for You!</div>
                <div class="promo-text">Use the code below to get 10% OFF your entire order</div>
                <div class="promo-code">WELCOME10</div>
                <button class="btn btn-outline-light" onclick="copyPromoCode('WELCOME10')">
                    <ion-icon name="copy-outline"></ion-icon> Copy Code
                </button>
            </div>
            <?php endif; ?>
            
            <div class="cart-header">
                <h1 class="cart-title">Your Shopping Cart</h1>
                <a href="index.php" class="continue-shopping">
                    <ion-icon name="arrow-back-outline"></ion-icon> Continue Shopping
                </a>
            </div>
            
            <div class="cart-grid">
                <div class="cart-items">
                    <div class="cart-table-container">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $total = 0;
                                $items_count = 0;
                                foreach($_SESSION['mycart'] as $key => $value): 
                                    $itemTotal = $value['product_price'] * $value['product_qty'];
                                    $total += $itemTotal;
                                    $items_count += $value['product_qty'];
                                ?>
                                    <tr class="cart-item" data-product-id="<?php echo $value['product_id']; ?>">
                                        <td style="min-width: 200px;">
                                            <div style="display: flex; align-items: center; gap: 15px;">
                                                <img class="cart-product-image" 
                                                     src="<?php echo $value['product_img']; ?>" 
                                                     alt="<?php echo htmlspecialchars($value['product_name']); ?>">
                                                <div>
                                                    <span class="product-name"><?php echo htmlspecialchars($value['product_name']); ?></span>
                                                    <span class="product-category"><?php echo htmlspecialchars($value['product_category']); ?></span>
                                                    
                                                    <?php 
                                                    // Randomly assign stock status for demo purposes
                                                    // In real app, fetch from database
                                                    $stock = rand(3, 15);
                                                    $stockClass = $stock > 5 ? 'in-stock' : 'low-stock';
                                                    $stockIcon = $stock > 5 ? 'checkmark-circle' : 'alert-circle';
                                                    $stockText = $stock > 5 ? 'In Stock' : 'Low Stock - Only ' . $stock . ' left';
                                                    ?>
                                                    
                                                    <div class="stock-indicator <?php echo $stockClass; ?>">
                                                        <ion-icon name="<?php echo $stockIcon; ?>"></ion-icon>
                                                        <?php echo $stockText; ?>
                                                    </div>
                                                    
                                                    <div class="action-buttons">
                                                        <button type="button" class="action-btn" onclick="saveForLater(<?php echo $value['product_id']; ?>)">
                                                            <ion-icon name="heart-outline"></ion-icon> Save for later
                                                        </button>
                                                        <button type="button" class="action-btn" onclick="showToast('Added to Compare', 'Product added to compare list')">
                                                            <ion-icon name="git-compare-outline"></ion-icon> Compare
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="product-price">$<?php echo number_format($value['product_price'], 2); ?></span>
                                        </td>
                                        <td>
                                            <form action="manage_cart.php" method="POST" class="quantity-form" onsubmit="showLoading()">
                                                <input type="hidden" name="action" value="update_quantity">
                                                <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?>">
                                                <div class="quantity-controls">
                                                    <button type="button" class="quantity-btn minus" onclick="decrementQuantity(this)">
                                                        <ion-icon name="remove-outline"></ion-icon>
                                                    </button>
                                                    <input type="number" 
                                                           name="quantity" 
                                                           value="<?php echo $value['product_qty']; ?>" 
                                                           min="1" 
                                                           class="quantity-input"
                                                           onchange="this.form.submit()">
                                                    <button type="button" class="quantity-btn plus" onclick="incrementQuantity(this)">
                                                        <ion-icon name="add-outline"></ion-icon>
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                        <td>
                                            <span class="product-price">$<?php echo number_format($itemTotal, 2); ?></span>
                                        </td>
                                        <td>
                                            <form action="manage_cart.php" method="POST" onsubmit="return confirmDelete(this)">
                                                <input type="hidden" name="action" value="remove_item">
                                                <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?>">
                                                <button type="submit" class="delete-btn" 
                                                        aria-label="Remove item">
                                                    <ion-icon name="trash-outline" class="delete-icon"></ion-icon>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="cart-summary">
                    <h2 class="summary-title">Order Summary</h2>
                    
                    <?php 
                    // Calculate free shipping progress
                    $free_shipping_threshold = 100;
                    $progress_percentage = min(($total / $free_shipping_threshold) * 100, 100);
                    $amount_needed = max($free_shipping_threshold - $total, 0);
                    ?>
                    
                    <?php if ($total < $free_shipping_threshold): ?>
                        <div class="progress-text">
                            Add $<?php echo number_format($amount_needed, 2); ?> more for free shipping
                        </div>
                        <div class="checkout-progress">
                            <div class="progress-bar" style="width: <?php echo $progress_percentage; ?>%"></div>
                        </div>
                    <?php else: ?>
                        <div class="free-shipping-message">
                            <ion-icon name="checkmark-circle"></ion-icon>
                            You've qualified for free shipping!
                        </div>
                    <?php endif; ?>
                    
                    <div class="summary-row">
                        <span>Items (<?php echo $items_count; ?>)</span>
                        <span>$<?php echo number_format($total, 2); ?></span>
                    </div>
                    
                    <!-- Optional: Add a discount feature -->
                    <?php 
                    $has_discount = false;
                    $discount_amount = 0;
                    if ($total > 150) {
                        $has_discount = true;
                        $discount_amount = $total * 0.05; // 5% discount for orders over $150
                    }
                    ?>
                    
                    <?php if($has_discount): ?>
                    <div class="summary-row savings">
                        <span>Discount <span class="summary-badge">5% OFF</span></span>
                        <span>-$<?php echo number_format($discount_amount, 2); ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span>Free</span>
                    </div>
                    
                    <?php 
                    // Apply discount if applicable
                    $final_total = $has_discount ? ($total - $discount_amount) : $total;
                    ?>
                    
                    <div class="summary-row total">
                        <span>Total</span>
                        <span>$<?php echo number_format($final_total, 2); ?></span>
                    </div>
                    
                    <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="checkout.php" class="checkout-btn" onclick="showLoading()">
                        <ion-icon name="bag-check-outline"></ion-icon> Proceed to Checkout
                    </a>
                    <?php else: ?>
                    <div style="margin: 1rem 0; color: var(--text-gray); text-align: center; padding: 0.5rem; background-color: var(--light-gray); border-radius: 8px;">
                        <ion-icon name="information-circle-outline" style="vertical-align: middle; margin-right: 5px;"></ion-icon>
                        Please login to complete your purchase
                    </div>
                    <a href="login.php?redirect=cart.php" class="checkout-btn" onclick="showLoading()">
                        <ion-icon name="log-in-outline"></ion-icon> Login to Checkout
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if(count($_SESSION['mycart']) > 0): ?>
            <div class="related-products">
                <h2 class="related-title">You Might Also Like</h2>
                <div class="related-grid">
                    <?php
                    // In a real app, you would fetch related products based on cart items
                    // For demo, just show some random products
                    $demoProducts = [
                        ['id' => 101, 'name' => 'Hiking Boots', 'price' => 89.99, 'img' => './assets/images/products/product01.jpg'],
                        ['id' => 102, 'name' => 'Camping Tent', 'price' => 129.99, 'img' => './assets/images/products/product02.jpg'],
                        ['id' => 103, 'name' => 'Hiking Backpack', 'price' => 59.99, 'img' => './assets/images/products/product03.jpg'],
                        ['id' => 104, 'name' => 'Sleeping Bag', 'price' => 49.99, 'img' => './assets/images/products/product04.jpg']
                    ];
                    
                    foreach($demoProducts as $product):
                    ?>
                    <div class="related-product">
                        <img src="<?php echo $product['img']; ?>" alt="<?php echo $product['name']; ?>" class="related-img">
                        <div class="related-info">
                            <div class="related-name"><?php echo $product['name']; ?></div>
                            <div class="related-price">$<?php echo $product['price']; ?></div>
                            <button class="related-add" onclick="quickAddToCart(<?php echo $product['id']; ?>, '<?php echo $product['name']; ?>', <?php echo $product['price']; ?>, '<?php echo $product['img']; ?>')">
                                <ion-icon name="add-circle-outline"></ion-icon> Add to Cart
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
        <?php else: ?>
            <div class="empty-cart">
                <ion-icon name="cart-outline" class="empty-cart-icon"></ion-icon>
                <h2>Your Cart is Empty</h2>
                <p>Looks like you haven't added any items to your cart yet. Browse our collections and find something you'll love!</p>
                <a href="index.php" class="checkout-btn">
                    <ion-icon name="bag-outline"></ion-icon> Start Shopping Now
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="toast-container"></div>
</main>

<script>
    function decrementQuantity(button) {
        const input = button.nextElementSibling;
        const currentValue = parseInt(input.value);
        if (currentValue > 1) {
            input.value = currentValue - 1;
            
            // Add animation
            button.closest('.cart-item').classList.add('quantity-change');
            setTimeout(() => {
                showLoading();
                button.closest('.cart-item').classList.remove('quantity-change');
                input.form.submit();
            }, 300);
        }
    }
    
    function incrementQuantity(button) {
        const input = button.previousElementSibling;
        const currentValue = parseInt(input.value);
        input.value = currentValue + 1;
        
        // Add animation
        button.closest('.cart-item').classList.add('quantity-change');
        setTimeout(() => {
            showLoading();
            button.closest('.cart-item').classList.remove('quantity-change');
            input.form.submit();
        }, 300);
    }
    
    // Add delete animation
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const row = this.closest('tr');
            
            row.classList.add('removing');
            
            setTimeout(() => {
                this.closest('form').submit();
            }, 300);
        });
    });

    function confirmDelete(form) {
        const row = form.closest('tr');
        row.classList.add('removing-item');
        
        setTimeout(() => {
            showLoading();
            return true;
        }, 500);
        
        return false;
    }

    function showLoading() {
        const loadingOverlay = document.querySelector('.loading-overlay');
        loadingOverlay.classList.add('active');
        
        // Safety timeout - hide after 5 seconds in case of issues
        setTimeout(() => {
            loadingOverlay.classList.remove('active');
        }, 5000);
    }

    // Animate badge when adding to cart
    function animateCartBadge() {
        const badge = document.querySelector('.cart-badge');
        if (badge) {
            badge.classList.add('pulse');
            setTimeout(() => {
                badge.classList.remove('pulse');
            }, 500);
        }
    }

    function quickAddToCart(productId, name, price, img) {
        // In a real app, this would be an AJAX call to add the item
        // For demo, just show a toast notification
        showToast('Added to Cart', `${name} has been added to your cart`, 'success');
        
        // Animate the cart badge
        animateCartBadge();
    }

    // Disable loading overlay when page is fully loaded
    window.addEventListener('load', function() {
        const loadingOverlay = document.querySelector('.loading-overlay');
        loadingOverlay.classList.remove('active');
    });

    function saveForLater(productId) {
        // In a real app, this would call an AJAX endpoint to save the item
        showToast('Saved', 'Item saved to your wishlist', 'success');
    }

    function showToast(title, message, type = 'info') {
        const toastContainer = document.querySelector('.toast-container');
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        
        toast.innerHTML = `
            <div class="toast-icon">
                <ion-icon name="${type === 'success' ? 'checkmark-circle' : 'information-circle'}"></ion-icon>
            </div>
            <div class="toast-content">
                <div class="toast-title">${title}</div>
                <div class="toast-message">${message}</div>
            </div>
        `;
        
        toastContainer.appendChild(toast);
        
        // Trigger animation
        setTimeout(() => {
            toast.classList.add('show');
        }, 10);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }

    function copyPromoCode(code) {
        navigator.clipboard.writeText(code).then(() => {
            showToast('Copied!', 'Promo code copied to clipboard', 'success');
        }).catch(err => {
            console.error('Failed to copy: ', err);
        });
    }
</script>

<?php require_once './includes/footer.php'; ?>