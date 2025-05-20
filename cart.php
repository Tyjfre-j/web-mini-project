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
    --light-red: #ffefef;
    --light-blue: #f0f7ff;
    --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
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
}

.cart-title {
    font-size: 1.8rem;
    color: #333;
    font-weight: 600;
    margin: 0;
}

.continue-shopping {
    display: inline-flex;
    align-items: center;
    color: var(--deep-maroon);
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s;
}

.continue-shopping ion-icon {
    margin-right: 6px;
}

.continue-shopping:hover {
    color: var(--main-maroon);
    text-decoration: none;
}

/* Cart table */
.cart-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: white;
    border-radius: 12px;
    box-shadow: var(--box-shadow);
    margin-bottom: 2rem;
    overflow: hidden;
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
}

.cart-product-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    transition: transform 0.3s ease;
}

.cart-product-image:hover {
    transform: scale(1.05);
}

.product-name {
    font-weight: 600;
    color: #333;
    font-size: 1rem;
    margin-bottom: 4px;
}

.product-category {
    color: var(--text-gray);
    font-size: 0.85rem;
    display: block;
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
    border-radius: 8px;
    overflow: hidden;
    width: fit-content;
}

.quantity-btn {
    background: white;
    border: none;
    width: 32px;
    height: 32px;
    font-size: 1.2rem;
    color: var(--text-gray);
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.quantity-btn:hover {
    background-color: var(--light-gray);
    color: #333;
}

.quantity-input {
    width: 50px;
    height: 32px;
    text-align: center;
    border: none;
    border-left: 1px solid var(--border-color);
    border-right: 1px solid var(--border-color);
    font-weight: 500;
    color: #333;
    -moz-appearance: textfield;
}

.quantity-input::-webkit-outer-spin-button,
.quantity-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Delete Button */
.delete-btn {
    background: none;
    border: none;
    padding: 8px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.delete-icon {
    color: var(--text-gray);
    font-size: 1.3rem;
    transition: color 0.2s;
}

.delete-btn:hover {
    background-color: var(--light-red);
}

.delete-btn:hover .delete-icon {
    color: var(--main-maroon);
}

/* Cart Summary */
.cart-summary {
    background: white;
    border-radius: 12px;
    box-shadow: var(--box-shadow);
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.summary-title {
    font-size: 1.2rem;
    font-weight: 600;
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
    font-size: 1.1rem;
    border-top: 1px solid var(--border-color);
    margin-top: 0.5rem;
    padding-top: 1rem;
}

/* Buttons */
.checkout-btn {
    background-color: var(--main-maroon);
    color: white;
    padding: 1rem 2rem;
    border-radius: 8px;
    text-decoration: none;
    display: inline-block;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    width: 100%;
    max-width: 350px;
    text-align: center;
    box-shadow: 0 4px 6px rgba(206, 89, 89, 0.2);
}

.checkout-btn:hover {
    background-color: var(--deep-maroon);
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(137, 55, 95, 0.25);
    color: white;
    text-decoration: none;
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
    border-radius: 12px;
    box-shadow: var(--box-shadow);
}

.empty-cart-icon {
    font-size: 4rem;
    color: var(--text-gray);
    margin-bottom: 1rem;
}

.empty-cart h2 {
    color: #333;
    margin-bottom: 1rem;
}

.empty-cart p {
    color: var(--text-gray);
    margin-bottom: 2rem;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

.cart-grid {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 2rem;
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

    .cart-table {
        display: block;
        overflow-x: auto;
    }
    
    .cart-table th,
    .cart-table td {
        white-space: nowrap;
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
        margin: 0 auto;
    }
}
</style>

<main>
    <div class="product-container">
        <?php if(isset($_SESSION['mycart']) && !empty($_SESSION['mycart'])): ?>
            <div class="cart-header">
                <h1 class="cart-title">Shopping Cart</h1>
                <a href="index.php" class="continue-shopping">
                    <ion-icon name="arrow-back-outline"></ion-icon> Continue Shopping
                </a>
            </div>
            
            <div class="cart-grid">
                <div class="cart-items">
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
                                <tr>
                                    <td style="min-width: 200px;">
                                        <div style="display: flex; align-items: center; gap: 15px;">
                                            <img class="cart-product-image" 
                                                 src="<?php echo $value['product_img']; ?>" 
                                                 alt="<?php echo htmlspecialchars($value['product_name']); ?>">
                                            <div>
                                                <span class="product-name"><?php echo htmlspecialchars($value['product_name']); ?></span>
                                                <span class="product-category"><?php echo htmlspecialchars($value['product_category']); ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="product-price">$<?php echo number_format($value['product_price'], 2); ?></span>
                                    </td>
                                    <td>
                                        <form action="manage_cart.php" method="POST" class="quantity-form">
                                            <input type="hidden" name="action" value="update_quantity">
                                            <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?>">
                                            <div class="quantity-controls">
                                                <button type="button" class="quantity-btn" onclick="decrementQuantity(this)">-</button>
                                                <input type="number" 
                                                       name="quantity" 
                                                       value="<?php echo $value['product_qty']; ?>" 
                                                       min="1" 
                                                       class="quantity-input"
                                                       onchange="this.form.submit()">
                                                <button type="button" class="quantity-btn" onclick="incrementQuantity(this)">+</button>
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                        <span class="product-price">$<?php echo number_format($itemTotal, 2); ?></span>
                                    </td>
                                    <td>
                                        <form action="manage_cart.php" method="POST">
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
                
                <div class="cart-summary">
                    <h2 class="summary-title">Order Summary</h2>
                    <div class="summary-row">
                        <span>Items (<?php echo $items_count; ?>)</span>
                        <span>$<?php echo number_format($total, 2); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span>Free</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span>$<?php echo number_format($total, 2); ?></span>
                    </div>
                    
                    <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="checkout.php" class="checkout-btn">
                        <ion-icon name="bag-check-outline"></ion-icon> Proceed to Checkout
                    </a>
                    <?php else: ?>
                    <div style="margin-bottom: 15px; color: var(--text-gray); text-align: center;">
                        Please login to complete your purchase
                    </div>
                    <a href="login.php?redirect=cart.php" class="checkout-btn">
                        <ion-icon name="log-in-outline"></ion-icon> Login to Checkout
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="empty-cart">
                <ion-icon name="cart-outline" class="empty-cart-icon"></ion-icon>
                <h2>Your Cart is Empty</h2>
                <p>Looks like you haven't added any items to your cart yet. Browse our collections and find something you'll love!</p>
                <a href="index.php" class="checkout-btn">
                    <ion-icon name="bag-outline"></ion-icon> Continue Shopping
                </a>
            </div>
        <?php endif; ?>
    </div>
</main>

<script>
    function decrementQuantity(button) {
        const input = button.nextElementSibling;
        const currentValue = parseInt(input.value);
        if (currentValue > 1) {
            input.value = currentValue - 1;
            input.form.submit();
        }
    }
    
    function incrementQuantity(button) {
        const input = button.previousElementSibling;
        const currentValue = parseInt(input.value);
        input.value = currentValue + 1;
        input.form.submit();
    }
</script>

<?php require_once './includes/footer.php'; ?>