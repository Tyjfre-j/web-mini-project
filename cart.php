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

.product-container {
    padding: 2rem 1rem;
    max-width: 1200px;
    margin: 0 auto;
}

.cart-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    margin-bottom: 2rem;
}

.cart-table th {
    background: var(--light-gray);
    padding: 1rem;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.9rem;
    color: var(--text-gray);
    border-bottom: 1px solid var(--border-color);
}

.cart-table td {
    padding: 1rem;
    vertical-align: middle;
    border-bottom: 1px solid var(--border-color);
}

.cart-product-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 4px;
}

.product-name {
    font-weight: 500;
    color: #333;
}

.quantity-input {
    width: 80px;
    padding: 0.5rem;
    border: 1px solid var(--border-color);
    border-radius: 4px;
    text-align: center;
}

.delete-btn {
    background: none;
    border: none;
    padding: 0.5rem;
    cursor: pointer;
    transition: all 0.2s;
}

.delete-icon {
    color: var(--main-maroon);
    font-size: 1.2rem;
}

.delete-btn:hover .delete-icon {
    color: var(--deep-maroon);
}

.checkout-btn {
    background-color: var(--main-maroon);
    color: white;
    padding: 1rem 2rem;
    border-radius: 8px;
    text-decoration: none;
    display: inline-block;
    font-weight: 600;
    transition: background-color 0.2s;
    border: none;
    cursor: pointer;
    width: 100%;
    max-width: 350px;
    text-align: center;
}

.checkout-btn:hover {
    background-color: var(--deep-maroon);
}

.cart-total-row {
    background-color: var(--light-gray);
    font-weight: 600;
}

.cart-total-row td {
    padding: 1.2rem 1rem;
}

.empty-cart {
    text-align: center;
    padding: 3rem 1rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.empty-cart h2 {
    color: #333;
    margin-bottom: 1rem;
}

.empty-cart p {
    color: var(--text-gray);
    margin-bottom: 2rem;
}

@media screen and (max-width: 768px) {
    .cart-table {
        display: block;
        overflow-x: auto;
    }
    
    .cart-table th,
    .cart-table td {
        white-space: nowrap;
        padding: 0.75rem;
    }
    
    .cart-product-image {
        width: 60px;
        height: 60px;
    }
    
    .quantity-input {
        width: 60px;
    }
    
    .checkout-btn {
        width: 100%;
    }
}
</style>

<main>
    <div class="product-container">
        <?php if(isset($_SESSION['mycart']) && !empty($_SESSION['mycart'])): ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = 0;
                    foreach($_SESSION['mycart'] as $key => $value): 
                        $itemTotal = $value['price'] * $value['product_qty'];
                        $total += $itemTotal;
                    ?>
                        <tr>
                            <td>
                                <img class="cart-product-image" 
                                     src="./admin/upload/<?php echo $value['product_img'] ?>" 
                                     alt="<?php echo htmlspecialchars($value['name']); ?>">
                            </td>
                            <td class="product-name"><?php echo htmlspecialchars($value['name']); ?></td>
                            <td>$<?php echo number_format($value['price'], 2); ?></td>
                            <td>
                                <form action="cart_actions.php" method="POST" class="quantity-form">
                                    <input type="hidden" name="action" value="update_quantity">
                                    <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?>">
                                    <input type="number" 
                                           name="quantity" 
                                           value="<?php echo $value['product_qty']; ?>" 
                                           min="1" 
                                           class="quantity-input"
                                           onchange="this.form.submit()">
                                </form>
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
                    <tr class="cart-total-row">
                        <td colspan="2" style="text-align: right;">Total:</td>
                        <td colspan="3">$<?php echo number_format($total, 2); ?></td>
                    </tr>
                </tbody>
            </table>
            
            <div style="text-align: center;">
                <a href="checkout.php" class="checkout-btn">
                    Proceed to Checkout
                </a>
            </div>
            
        <?php else: ?>
            <div class="empty-cart">
                <h2>Your Cart is Empty</h2>
                <p>Looks like you haven't added any items to your cart yet.</p>
                <a href="index.php" class="checkout-btn">
                    Continue Shopping
                </a>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once './includes/footer.php'; ?>