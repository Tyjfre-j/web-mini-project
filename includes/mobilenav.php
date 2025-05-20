<?php
$total_cart_items = 0;
if (isset($_SESSION['mycart'])) {
  $total_cart_items = count($_SESSION['mycart']);
}
?>

<!-- Mobile navigation menu - activated by the button in header -->
<nav class="mobile-navigation-menu has-scrollbar" data-mobile-menu>
  <div class="menu-top">
    <h2 class="menu-title">Menu</h2>

    <button class="menu-close-btn" data-mobile-menu-close-btn>
      <ion-icon name="close-outline"></ion-icon>
    </button>
  </div>

    <ul class="mobile-menu-category-list">
      <li class="menu-category">
        <a href="./index.php#home" class="menu-title">
          <ion-icon name="home-outline" class="menu-icon"></ion-icon>
          <span>Home</span>
        </a>
      </li>

      <li class="menu-category">
        <a href="./index.php#laptops-section" class="menu-title">
          <ion-icon name="laptop-outline" class="menu-icon"></ion-icon>
          <span>Laptops</span>
        </a>
      </li>
      
      <li class="menu-category">
        <a href="./index.php#desktops-section" class="menu-title">
          <ion-icon name="desktop-outline" class="menu-icon"></ion-icon>
          <span>Desktops</span>
        </a>
      </li>
      
      <li class="menu-category">
        <a href="./index.php#custom-builds-section" class="menu-title">
          <ion-icon name="construct-outline" class="menu-icon"></ion-icon>
          <span>Custom Build</span>
        </a>
      </li>

      <li class="menu-category">
        <button class="accordion-menu pc-parts-menu" data-accordion-btn>
          <div class="menu-title">
            <ion-icon name="hardware-chip-outline" class="menu-icon"></ion-icon>
            <span>PC Parts</span>
          </div>
          <ion-icon name="add-outline" class="add-icon"></ion-icon>
          <ion-icon name="remove-outline" class="remove-icon"></ion-icon>
        </button>

        <ul class="submenu-category-list" data-accordion>
          <li class="submenu-category">
            <a href="./index.php#display-screens-section" class="submenu-title">
              <ion-icon name="tv-outline" class="submenu-icon"></ion-icon>
              <span>Display Screens</span>
            </a>
          </li>
          <li class="submenu-category">
            <a href="./index.php#processors-section" class="submenu-title">
              <ion-icon name="flash-outline" class="submenu-icon"></ion-icon>
              <span>Processors</span>
            </a>
          </li>
          <li class="submenu-category">
            <a href="./index.php#graphics-cards-section" class="submenu-title">
              <ion-icon name="extension-puzzle-outline" class="submenu-icon"></ion-icon>
              <span>Graphics Cards</span>
            </a>
          </li>
          <li class="submenu-category">
            <a href="./index.php#keyboards-section" class="submenu-title">
              <ion-icon name="keypad-outline" class="submenu-icon"></ion-icon>
              <span>Keyboards</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="menu-category">
        <a href="#footer" class="menu-title">
          <ion-icon name="call-outline" class="menu-icon"></ion-icon>
          <span>Contact</span>
        </a>
      </li>
      
      <li class="menu-category">
        <a href="./cart.php" class="menu-title">
          <ion-icon name="bag-handle-outline" class="menu-icon"></ion-icon>
          <span>Cart</span>
          <?php if($total_cart_items > 0): ?>
            <span class="count"><?php echo $total_cart_items; ?></span>
          <?php endif; ?>
        </a>
      </li>
      
      <?php if (isset($_SESSION['id'])) { ?>
        <li class="menu-category">
          <a href="orders.php" class="menu-title">
            <ion-icon name="cube-outline" class="menu-icon"></ion-icon>
            <span>Orders</span>
          </a>
        </li>
        
        <li class="menu-category">
          <a href="profile.php?id=<?php echo (isset($_SESSION['customer_name'])) ? $_SESSION['id'] : 'unknown'; ?>" class="menu-title">
            <ion-icon name="person-outline" class="menu-icon"></ion-icon>
            <span>Profile</span>
          </a>
        </li>
        
        <li class="menu-category">
          <a href="logout.php" class="menu-title">
            <ion-icon name="log-out-outline" class="menu-icon"></ion-icon>
            <span>Logout</span>
          </a>
        </li>
      <?php } else { ?>
        <li class="menu-category">
          <a href="./login.php" class="menu-title">
            <ion-icon name="log-in-outline" class="menu-icon"></ion-icon>
            <span>Sign In</span>
          </a>
        </li>
        
        <li class="menu-category">
          <a href="./signup.php" class="menu-title">
            <ion-icon name="person-add-outline" class="menu-icon"></ion-icon>
            <span>Sign Up</span>
          </a>
        </li>
      <?php } ?>
    </ul>

    <div class="menu-bottom">
      <!-- Language, currency and social media sections removed -->
    </div>
</nav>