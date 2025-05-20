<?php
// Check if session is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
           
    
            if(isset($_POST['add_to_cart']))
            {    
                // Check if user is logged in
                if(!isset($_SESSION['user_id'])) {
                    $_SESSION['error_message'] = "Please login to add items to your cart";
                    // Support both product.php and viewdetail.php (for backward compatibility)
                    if(isset($_POST['product_category']) && $_POST['product_category'] == 'viewdetail') {
                        $redirect = "viewdetail.php?id=".$_POST['product_id']."&category=".$_POST['product_type'];
                        header('location:login.php?redirect='.urlencode($redirect));
                    } else {
                        $redirect = "product.php?id=".$_POST['product_id']."&type=".$_POST['product_category'];
                        header('location:login.php?redirect='.urlencode($redirect));
                    }
                    exit();
                }
               
                // Add item to cart session
                if(!isset($_SESSION['mycart'])) {
                    $_SESSION['mycart'] = array();
                }
                
                $product_id = $_POST['product_id'];
                $product_name = $_POST['product_name'];
                $product_price = $_POST['product_price'];
                $product_qty = $_POST['product_qty'];
                $product_category = $_POST['product_category'];
                $product_img = $_POST['product_img'];
                
                // Check if product already exists in cart
                $item_exists = false;
                foreach($_SESSION['mycart'] as $key => $item) {
                    if($item['product_id'] == $product_id && $item['product_category'] == $product_category) {
                        // Update quantity
                        $_SESSION['mycart'][$key]['product_qty'] += $product_qty;
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
                
                $_SESSION['success_message'] = "Product added to cart successfully!";
                header('Location: cart.php');
                exit();
            }

            if(isset($_POST['action'])) {
              // Check if user is logged in
              if(!isset($_SESSION['user_id'])) {
                  $_SESSION['error_message'] = "Please login to manage your cart";
                  header('location:login.php?redirect=cart.php');
                  exit();
              }
              
              $action = $_POST['action'];
              
              // Update quantity
              if($action === 'update_quantity') {
                  $product_id = $_POST['product_id'];
                  $new_quantity = (int)$_POST['quantity'];
                  
                  if($new_quantity > 0) {
                      foreach($_SESSION['mycart'] as $key => $item) {
                          if($item['product_id'] == $product_id) {
                              $_SESSION['mycart'][$key]['product_qty'] = $new_quantity;
                              break;
                          }
                      }
                  }
                  header('Location: cart.php');
                  exit();
              }
              
              // Remove item
              if($action === 'remove_item') {
                  $product_id = $_POST['product_id'];
                  
                  foreach($_SESSION['mycart'] as $key => $item) {
                      if($item['product_id'] == $product_id) {
                          unset($_SESSION['mycart'][$key]);
                          $_SESSION['mycart'] = array_values($_SESSION['mycart']); // Reindex array
                          break;
                      }
                  }
                  header('Location: cart.php');
                  exit();
              }
          }





             ?>
