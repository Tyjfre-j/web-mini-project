<?php include_once('./includes/headerNav.php'); ?>

<!-- Add product detail CSS at the end of the head section for highest specificity -->
<style>
  body main {
    padding-top: 0 !important;
  }
  
  .product-detail-container {
    padding: 0 !important;
    margin: 0 !important;
  }
  
  main.no-padding {
    padding-top: 0 !important;
    margin-bottom: 0 !important;
  }
  
  /* Direct styling for header-main spacing */
  header + main {
    margin-top: 10px !important;
    margin-bottom: 0 !important;
  }
  
  /* Additional direct target for the container */
  header + main .product-detail-container {
    margin-top: 10px !important;
    margin-bottom: 0 !important;
  }
  
  /* Login message and button styles */
  .login-message {
    margin-top: 20px;
    width: 100%;
  }
  
  .login-message p {
    margin-bottom: 10px;
    color: #555;
  }
  
  .login-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    background: linear-gradient(135deg, var(--main-maroon), var(--deep-maroon));
    color: white;
    border: none;
    padding: 15px 30px;
    font-size: 1.1rem;
    font-weight: 600;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 100%;
    text-decoration: none;
  }
  
  .login-btn ion-icon {
    font-size: 1.3rem;
  }
  
  .login-btn:hover {
    background: linear-gradient(135deg, var(--deep-maroon), var(--main-maroon));
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(13, 138, 145, 0.2);
    color: white;
  }
</style>

<link rel="stylesheet" href="css/product-detail.css">

<!-- Include JavaScript files with defer attribute -->
<script src="js/index.js" defer></script>
<script src="js/script.js" defer></script>

<div class="overlay" data-overlay></div>

<!--
    - HEADER
  -->
<header>
  <!-- top head action, search etc in php -->
  <!-- inc/topheadactions.php -->
  <?php require_once './includes/topheadactions.php'; ?>
  <!-- desktop navigation -->
  <!-- inc/desktopnav.php -->
  <?php require_once './includes/desktopnav.php' ?>
  <!-- mobile nav in php -->
  <!-- inc/mobilenav.php -->
  <?php require_once './includes/mobilenav.php'; ?>
</header>

<?php
// Get product ID and type from URL using GET (appropriate for page navigation)
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product_type = isset($_GET['type']) ? $_GET['type'] : '';

if (empty($product_id) || empty($product_type)) {
    header("Location: index.php");
    exit();
}

// Function to get product by ID and type
function getProductById($id, $type) {
    global $conn;
    
    // Sanitize the type to avoid SQL injection
    $type = mysqli_real_escape_string($conn, $type);
    
    // In the database schema, field names include spaces from the table name
    // E.g., table "Custom Builds" has fields like "Custom Builds_id"
    $id_field = $type . '_id';
    $category_id_field = $type . '_category_id';
    $status_field = $type . '_status';
    
    // For the alias, we need to replace spaces with underscores
    $type_alias = str_replace(' ', '_', $type);
    
    $query = "SELECT `{$type}`.*, category.category_name AS `{$type_alias}_category_name` 
              FROM `{$type}` 
              JOIN category ON `{$type}`.`{$category_id_field}` = category.category_id 
              WHERE `{$type}`.`{$id_field}` = ? 
              AND `{$type}`.`{$status_field}` = true 
              AND category.category_status = true";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result;
}

// Get the product
$product_result = getProductById($product_id, $product_type);

if (!$product_result || $product_result->num_rows === 0) {
    header("Location: index.php");
    exit();
}

$product = $product_result->fetch_assoc();

// Define field names based on product type
// Field names in database include spaces from table name
// E.g., "Custom Builds_id" rather than "Custom_Builds_id"
$id_field = $product_type . '_id';
$name_field = $product_type . '_name';
$image_field = $product_type . '_image_path';
$small_desc_field = $product_type . '_small_description';
$long_desc_field = $product_type . '_long_description';
$price_field = $product_type . '_price';
// For category field, we need the alias version without spaces
$type_alias = str_replace(' ', '_', $product_type);
$category_field = $type_alias . '_category_name';
$quantity_field = $product_type . '_quantity';
?>

<!--
    - MAIN
  -->
<main class="no-padding">
  <div class="product-detail-container">
    <div class="container no-padding">
      <div class="product-detail-main">
        <div class="product-detail-left">
          <div class="product-image-container">
            <img src="<?php echo htmlspecialchars($product[$image_field]); ?>" alt="<?php echo htmlspecialchars($product[$name_field]); ?>" class="product-detail-img">
          </div>
        </div>
        
        <div class="product-detail-right">
          <h1 class="product-detail-title"><?php echo htmlspecialchars($product[$name_field]); ?></h1>
          
          <div class="product-detail-category">
            <span class="label">Category:</span>
            <span class="value"><?php echo htmlspecialchars($product[$category_field]); ?></span>
          </div>
          
          <div class="product-detail-price">
            <span class="price-value">$<?php echo htmlspecialchars($product[$price_field]); ?></span>
          </div>
          
          <div class="product-detail-rating">
            <div class="stars">
              <ion-icon name="star"></ion-icon>
              <ion-icon name="star"></ion-icon>
              <ion-icon name="star"></ion-icon>
              <ion-icon name="star"></ion-icon>
              <ion-icon name="star"></ion-icon>
            </div>
            <span class="rating-count">(5.0)</span>
          </div>
          
          <div class="product-detail-description">
            <h3>Description</h3>
            <p><?php echo htmlspecialchars($product[$long_desc_field]); ?></p>
          </div>
          
          <div class="product-detail-stock">
            <span class="label">Availability:</span>
            <?php if ($product[$quantity_field] > 0): ?>
              <span class="in-stock">In Stock (<?php echo $product[$quantity_field]; ?> units)</span>
            <?php else: ?>
              <span class="out-of-stock">Out of Stock</span>
            <?php endif; ?>
          </div>
          
          <?php if(isset($_SESSION['user_id'])): ?>
          <!-- User is logged in, show standard add to cart form -->
          <form action="manage_cart.php" method="post" class="cart-form">
            <input type="hidden" name="product_id" value="<?php echo $product[$id_field]; ?>">
            <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product[$name_field]); ?>">
            <input type="hidden" name="product_price" value="<?php echo $product[$price_field]; ?>">
            <input type="hidden" name="product_category" value="<?php echo $product_type; ?>">
            <input type="hidden" name="product_img" value="<?php echo htmlspecialchars($product[$image_field]); ?>">
            
            <div class="quantity-selector">
              <label for="product_qty">Quantity:</label>
              <div class="quantity-controls">
                <button type="button" class="quantity-btn minus-btn" onclick="decrementQuantity()">-</button>
                <input type="number" id="product_qty" name="product_qty" value="1" min="1" max="<?php echo $product[$quantity_field]; ?>">
                <button type="button" class="quantity-btn plus-btn" onclick="incrementQuantity()">+</button>
              </div>
            </div>
            
            <button type="submit" name="add_to_cart" class="add-to-cart-btn" <?php echo ($product[$quantity_field] <= 0) ? 'disabled' : ''; ?>>
              <ion-icon name="cart-outline"></ion-icon>
              Add to Cart
            </button>
          </form>
          <?php else: ?>
          <!-- User is not logged in, show login button -->
          <div class="login-message">
            <p>Please log in to add items to your cart</p>
            <?php
            // Build current URL for redirect
            $current_url = "product.php?id={$product_id}&type=" . urlencode($product_type);
            ?>
            <a href="login.php?redirect=<?php echo urlencode($current_url); ?>" class="login-btn">
              <ion-icon name="log-in-outline"></ion-icon>
              Login to Purchase
            </a>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</main>

<?php require_once './includes/footer.php'; ?>

<script>
  function incrementQuantity() {
    const input = document.getElementById('product_qty');
    const max = parseInt(input.getAttribute('max'));
    const currentValue = parseInt(input.value);
    
    if (currentValue < max) {
      input.value = currentValue + 1;
    }
  }
  
  function decrementQuantity() {
    const input = document.getElementById('product_qty');
    const currentValue = parseInt(input.value);
    
    if (currentValue > 1) {
      input.value = currentValue - 1;
    }
  }
</script>