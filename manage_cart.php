<?php
            session_start();
           
    
            if(isset($_POST['add_to_cart']))
            {    
               
                     
            if(isset($_SESSION['mycart']))
            {
               $item_id = array_column($_SESSION['mycart'],'product_id');
               $item_check_id = in_array($_POST['product_id'],$item_id);
               
               if($item_check_id==true)
               {
                header('location:viewdetail.php?id='.$_POST['product_id'].'&category='.$_POST['product_category'].'');

               }else{
                $count_card = count($_SESSION['mycart']);
                $_SESSION['mycart'][$count_card]=array('name'=>$_POST['product_name'],'price'=>$_POST['product_price'],'product_id'=>$_POST['product_id'],'category'=>$_POST['product_category'],'product_qty'=>$_POST['product_qty'],'product_img'=>$_POST['product_img']);

                header('location:viewdetail.php?id='.$_POST['product_id'].'&category='.$_POST['product_category'].'');

               }

                

              


            }else{
              $_SESSION['mycart'][0]=array('name'=>$_POST['product_name'],'price'=>$_POST['product_price'],'product_id'=>$_POST['product_id'],'category'=>$_POST['product_category'],'product_qty'=>$_POST['product_qty'],'product_img'=>$_POST['product_img']);
             
              header('location:viewdetail.php?id='.$_POST['product_id'].'&category='.$_POST['product_category'].'');
            }

            }

            if(isset($_POST['action'])) {
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
