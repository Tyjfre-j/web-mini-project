<?php
  $total_cart_items = 0;
  if(isset($_SESSION['mycart'])) {
    $total_cart_items = count($_SESSION['mycart']);
  }
?>
<div class="header-main">
  <div class="container">
    <!-- Site name on the left -->
    <div class="site-branding">
      <a href="./index.php" class="header-logo">
        <div class="logo-container">
          <img src="admin/upload/<?php echo $_SESSION['web-img']; ?>" alt="logo" class="logo-image">
          <h1 class="site-title"><?php echo $_SESSION['web-name']; ?></h1>
        </div>
      </a>
    </div>

    <!-- User actions and cart on the right -->
    <div class="header-right">
      <!-- Search input -->
      <div class="header-search-container">
        <form class="search-form" method="post" action="./search.php">
          <div class="search-wrapper">
            <input 
              type="search" 
              name="search" 
              class="search-field" 
              placeholder="Search for products..." 
              required 
              oninvalid="this.setCustomValidity('Please enter a search term')" 
              oninput="this.setCustomValidity('')" 
            />
            <button class="search-btn" type="submit" name="submit">
              <ion-icon name="search-outline"></ion-icon>
            </button>
          </div>
        </form>
      </div>

      <div class="header-user-actions">
        <!-- Cart Button -->
        <button class="action-btn action-cart" title="Shopping Cart">
          <a href="./cart.php">
            <ion-icon name="bag-handle-outline"></ion-icon>
          </a>
          <?php if($total_cart_items > 0): ?>
            <span class="count"><?php echo $total_cart_items; ?></span>
          <?php endif; ?>
        </button>

        <?php if(isset($_SESSION['id'])) { ?>
          <!-- Profile button for logged-in users -->
          <button class="action-btn action-profile" title="My Profile">
            <a href="profile.php?id=<?php echo $_SESSION['id']; ?>">
              <ion-icon name="person-outline"></ion-icon>
            </a>
          </button>
          
          <!-- Logout button for logged-in users -->
          <button class="action-btn action-logout" title="Logout">
            <a href="logout.php">
              <ion-icon name="log-out-outline"></ion-icon>
            </a>
          </button> 
        <?php } else { ?>
          <!-- Sign Up Button for non-logged users -->
          <button class="action-btn action-signup" title="Sign In">
            <a href="./login.php">
              <ion-icon name="log-in-outline"></ion-icon>
            </a>
          </button>
        <?php } ?>
      </div>
    </div>
  </div>
</div>

      