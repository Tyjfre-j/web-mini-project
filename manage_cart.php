<?php
// Check if session is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security: Verify CSRF token if implemented
// Add this later with form token implementation

// Set default messages
$_SESSION['cart_error'] = '';
$_SESSION['cart_success'] = '';

// Function to sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Add to cart functionality
if(isset($_POST['add_to_cart'])) {    
    // Check if user is logged in
    if(!isset($_SESSION['user_id'])) {
        $_SESSION['cart_error'] = "Please login to add items to your cart";
        
        // Support both product.php and viewdetail.php (for backward compatibility)
        if(isset($_POST['product_category']) && isset($_POST['product_id'])) {
            $product_id = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT);
            
            if(isset($_POST['product_type'])) {
                $product_type = filter_input(INPUT_POST, 'product_type', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $redirect = "viewdetail.php?id=" . $product_id . "&category=" . urlencode($product_type);
            } else {
                $product_category = filter_input(INPUT_POST, 'product_category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $redirect = "product.php?id=" . $product_id . "&type=" . urlencode($product_category);
            }
            
            header('location:login.php?redirect=' . urlencode($redirect));
        } else {
            header('location:login.php');
        }
        exit();
    }
    
    // Validate required fields
    $required_fields = ['product_id', 'product_name', 'product_price', 'product_qty', 'product_category'];
    $missing_fields = [];
    
    foreach($required_fields as $field) {
        if(!isset($_POST[$field]) || empty($_POST[$field])) {
            $missing_fields[] = $field;
        }
    }
    
    if(!empty($missing_fields)) {
        $_SESSION['cart_error'] = "Missing required fields: " . implode(', ', $missing_fields);
        header('Location: ' . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php'));
        exit();
    }
    
    // Initialize cart if not exists
    if(!isset($_SESSION['mycart']) || !is_array($_SESSION['mycart'])) {
        $_SESSION['mycart'] = array();
    }
    
    // Sanitize and validate inputs
    $product_id = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT);
    $product_name = sanitize_input($_POST['product_name']);
    $product_price = filter_input(INPUT_POST, 'product_price', FILTER_VALIDATE_FLOAT);
    $product_qty = filter_input(INPUT_POST, 'product_qty', FILTER_VALIDATE_INT);
    $product_category = sanitize_input($_POST['product_category']);
    $product_img = isset($_POST['product_img']) ? sanitize_input($_POST['product_img']) : '';
    
    // Additional validation
    if($product_price === false || $product_price <= 0) {
        $_SESSION['cart_error'] = "Invalid product price";
        header('Location: ' . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php'));
        exit();
    }
    
    if($product_qty === false || $product_qty <= 0) {
        $product_qty = 1; // Default to 1 if invalid
    }
    
    // Cap quantity at a reasonable maximum
    $max_qty = 99;
    if($product_qty > $max_qty) {
        $product_qty = $max_qty;
        $_SESSION['cart_error'] = "Quantity limited to maximum of " . $max_qty;
    }
    
    // Verify product stock availability before adding to cart
    include_once('./includes/db_connection.php');
    
    // Get current stock
    $stock_query = "SELECT ";
    $stock_available = 0;
    
    switch($product_category) {
        case 'Laptops':
            $stock_query .= "Laptops_quantity FROM Laptops WHERE Laptops_id = ?";
            break;
        case 'Desktops':
            $stock_query .= "Desktops_quantity FROM Desktops WHERE Desktops_id = ?";
            break;
        case 'Custom Builds':
            $stock_query .= "`Custom Builds_quantity` FROM `Custom Builds` WHERE `Custom Builds_id` = ?";
            break;
        case 'Processors':
            $stock_query .= "Processors_quantity FROM Processors WHERE Processors_id = ?";
            break;
        case 'Graphics Cards':
            $stock_query .= "`Graphics Cards_quantity` FROM `Graphics Cards` WHERE `Graphics Cards_id` = ?";
            break;
        case 'Keyboards':
            $stock_query .= "Keyboards_quantity FROM Keyboards WHERE Keyboards_id = ?";
            break;
        case 'Display Screens':
            $stock_query .= "`Display Screens_quantity` FROM `Display Screens` WHERE `Display Screens_id` = ?";
            break;
        default:
            $_SESSION['cart_error'] = "Invalid product category: " . $product_category;
            header('Location: ' . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php'));
            exit();
    }
    
    $stock_stmt = $conn->prepare($stock_query);
    $stock_stmt->bind_param("i", $product_id);
    $stock_stmt->execute();
    $stock_stmt->bind_result($stock_available);
    $stock_stmt->fetch();
    $stock_stmt->close();
    
    // Calculate total requested quantity (existing in cart + new request)
    $total_requested_qty = $product_qty;
    
    // Check if product already exists in cart
    $item_exists = false;
    foreach($_SESSION['mycart'] as $key => $item) {
        if($item['product_id'] == $product_id && $item['product_category'] == $product_category) {
            // Add existing quantity to requested quantity
            $total_requested_qty += $item['product_qty'];
            $item_exists = true;
            break;
        }
    }
    
    // Check if enough stock is available
    if($total_requested_qty > $stock_available) {
        $_SESSION['cart_error'] = "Not enough stock available for " . $product_name . ". Available: " . $stock_available . ", Requested: " . $total_requested_qty;
        header('Location: ' . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php'));
        exit();
    }
    
    // Check if product already exists in cart
    $item_exists = false;
    foreach($_SESSION['mycart'] as $key => $item) {
        if($item['product_id'] == $product_id && $item['product_category'] == $product_category) {
            // Update quantity, but enforce maximum
            $new_qty = $item['product_qty'] + $product_qty;
            if($new_qty > $max_qty) {
                $new_qty = $max_qty;
                $_SESSION['cart_error'] = "Quantity limited to maximum of " . $max_qty;
            }
            $_SESSION['mycart'][$key]['product_qty'] = $new_qty;
            $item_exists = true;
            break;
        }
    }
    
    // If product doesn't exist in cart, add it
    if(!$item_exists) {
        $item_array = array(
            'product_id' => $product_id,
            'product_name' => $product_name,
            'product_price' => $product_price,
            'product_qty' => $product_qty,
            'product_category' => $product_category,
            'product_img' => $product_img
        );
        
        array_push($_SESSION['mycart'], $item_array);
    }
    
    $_SESSION['cart_success'] = "Product added to cart successfully!";
    header('Location: cart.php');
    exit();
}

// Handle cart management actions
if(isset($_POST['action'])) {
    // Check if user is logged in
    if(!isset($_SESSION['user_id'])) {
        $_SESSION['cart_error'] = "Please login to manage your cart";
        header('location:login.php?redirect=cart.php');
        exit();
    }
    
    // Make sure cart exists
    if(!isset($_SESSION['mycart']) || !is_array($_SESSION['mycart'])) {
        $_SESSION['mycart'] = array();
        $_SESSION['cart_error'] = "Your cart is empty";
        header('Location: cart.php');
        exit();
    }
    
    $action = sanitize_input($_POST['action']);
    
    // Update quantity
    if($action === 'update_quantity') {
        if(!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
            $_SESSION['cart_error'] = "Invalid request: missing required parameters";
            header('Location: cart.php');
            exit();
        }
        
        $product_id = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT);
        $new_quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
        
        if($new_quantity === false || $new_quantity <= 0) {
            $new_quantity = 1; // Default to 1 if invalid
        }
        
        // Cap quantity at a reasonable maximum
        $max_qty = 99;
        if($new_quantity > $max_qty) {
            $new_quantity = $max_qty;
            $_SESSION['cart_error'] = "Quantity limited to maximum of " . $max_qty;
        }
        
        $product_found = false;
        $product_category = '';
        
        // Find the product in cart
        foreach($_SESSION['mycart'] as $key => $item) {
            if($item['product_id'] == $product_id) {
                $product_found = true;
                $product_category = $item['product_category'];
                break;
            }
        }
        
        if($product_found) {
            // Verify stock availability before updating quantity
            include_once('./includes/db_connection.php');
            
            // Get current stock
            $stock_query = "SELECT ";
            $stock_available = 0;
            
            switch($product_category) {
                case 'Laptops':
                    $stock_query .= "Laptops_quantity FROM Laptops WHERE Laptops_id = ?";
                    break;
                case 'Desktops':
                    $stock_query .= "Desktops_quantity FROM Desktops WHERE Desktops_id = ?";
                    break;
                case 'Custom Builds':
                    $stock_query .= "`Custom Builds_quantity` FROM `Custom Builds` WHERE `Custom Builds_id` = ?";
                    break;
                case 'Processors':
                    $stock_query .= "Processors_quantity FROM Processors WHERE Processors_id = ?";
                    break;
                case 'Graphics Cards':
                    $stock_query .= "`Graphics Cards_quantity` FROM `Graphics Cards` WHERE `Graphics Cards_id` = ?";
                    break;
                case 'Keyboards':
                    $stock_query .= "Keyboards_quantity FROM Keyboards WHERE Keyboards_id = ?";
                    break;
                case 'Display Screens':
                    $stock_query .= "`Display Screens_quantity` FROM `Display Screens` WHERE `Display Screens_id` = ?";
                    break;
                default:
                    $_SESSION['cart_error'] = "Invalid product category: " . $product_category;
                    header('Location: cart.php');
                    exit();
            }
            
            $stock_stmt = $conn->prepare($stock_query);
            $stock_stmt->bind_param("i", $product_id);
            $stock_stmt->execute();
            $stock_stmt->bind_result($stock_available);
            $stock_stmt->fetch();
            $stock_stmt->close();
            
            // Check if enough stock is available
            if($new_quantity > $stock_available) {
                $_SESSION['cart_error'] = "Not enough stock available. Available: " . $stock_available . ", Requested: " . $new_quantity;
                header('Location: cart.php');
                exit();
            }
            
            // Update cart quantity
            foreach($_SESSION['mycart'] as $key => $item) {
                if($item['product_id'] == $product_id) {
                    $_SESSION['mycart'][$key]['product_qty'] = $new_quantity;
                    $_SESSION['cart_success'] = "Cart updated successfully";
                    break;
                }
            }
        } else {
            $_SESSION['cart_error'] = "Product not found in your cart";
        }
        
        header('Location: cart.php');
        exit();
    }
    
    // Remove item
    if($action === 'remove_item') {
        if(!isset($_POST['product_id'])) {
            $_SESSION['cart_error'] = "Invalid request: missing product ID";
            header('Location: cart.php');
            exit();
        }
        
        $product_id = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT);
        $product_removed = false;
        
        foreach($_SESSION['mycart'] as $key => $item) {
            if($item['product_id'] == $product_id) {
                $product_name = isset($item['product_name']) ? $item['product_name'] : 'Item';
                unset($_SESSION['mycart'][$key]);
                $_SESSION['mycart'] = array_values($_SESSION['mycart']); // Reindex array
                $product_removed = true;
                $_SESSION['cart_success'] = $product_name . " removed from your cart";
                break;
            }
        }
        
        if(!$product_removed) {
            $_SESSION['cart_error'] = "Product not found in your cart";
        }
        
        header('Location: cart.php');
        exit();
    }
    
    // If no valid action is specified
    $_SESSION['cart_error'] = "Invalid cart action";
    header('Location: cart.php');
    exit();
}

// If no valid request, redirect to cart page
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['cart_error'] = "Invalid request";
}

header('Location: cart.php');
exit();
?>
