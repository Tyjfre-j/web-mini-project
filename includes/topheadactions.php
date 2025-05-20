<?php
  $total_cart_items = 0;
  if(isset($_SESSION['mycart'])) {
    $total_cart_items = count($_SESSION['mycart']);
  }
?>
<div class="header-main">
  <!-- Site name on the left -->
  <div class="site-branding">
    <a href="./index.php" class="header-logo">
      <h1 class="site-title">PeakGear</h1>
    </a>
  </div>

  <!-- User actions and cart on the right -->
  <div class="header-user-actions desktop-menu-category-list">
    <!-- Cart Button -->
    <div class="menu-category">
      <a href="./cart.php" class="menu-title" title="Shopping Cart">
        <ion-icon name="bag-handle-outline" class="menu-icon"></ion-icon>
        <span>Cart</span>
        <?php if($total_cart_items > 0): ?>
          <span class="count"><?php echo $total_cart_items; ?></span>
        <?php endif; ?>
      </a>
    </div>
    
    <?php if(isset($_SESSION['id'])) { ?>
      <!-- Orders button for logged-in users -->
      <div class="menu-category">
        <a href="orders.php" class="menu-title" title="My Orders">
          <ion-icon name="cube-outline" class="menu-icon"></ion-icon>
          <span>Orders</span>
        </a>
      </div>
      
      <!-- Profile button for logged-in users -->
      <div class="menu-category">
        <a href="profile.php?id=<?php echo $_SESSION['id']; ?>" class="menu-title" title="My Profile">
          <ion-icon name="person-outline" class="menu-icon"></ion-icon>
          <span>Profile</span>
        </a>
      </div>
      
      <!-- Logout button for logged-in users -->
      <div class="menu-category">
        <a href="logout.php" class="menu-title" title="Logout">
          <ion-icon name="log-out-outline" class="menu-icon"></ion-icon>
          <span>Logout</span>
        </a>
      </div>
    <?php } else { ?>
      <!-- Sign In Button for non-logged users -->
      <div class="menu-category">
        <a href="./login.php" class="menu-title" title="Sign In">
          <ion-icon name="log-in-outline" class="menu-icon"></ion-icon>
          <span>Sign In</span>
        </a>
      </div>
    <?php } ?>
  </div>
  
  <!-- Menu button for mobile - positioned absolutely via CSS -->
  <button class="mobile-menu-btn" data-mobile-menu-open-btn>
    <ion-icon name="menu-outline"></ion-icon>
  </button>
</div>

      