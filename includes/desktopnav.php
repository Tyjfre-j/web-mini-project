<!-- desktop navigation -->
<nav class="desktop-navigation-menu">
  <div class="container nav-container">
    <!-- Site name on the left -->
    <div class="nav-branding">
      <a href="./index.php?id=<?php echo (isset($_SESSION['customer_name'])) ? $_SESSION['id'] : 'unknown';?>" class="nav-logo-link">
        <img src="admin/upload/<?php echo $_SESSION['web-img']; ?>" alt="<?php echo $_SESSION['web-name']; ?>" class="nav-logo-image">
        <span class="nav-site-name"><?php echo $_SESSION['web-name']; ?></span>
      </a>
    </div>

    <!-- Navigation menu in the middle -->
    <div class="nav-menu-container">
      <ul class="desktop-menu-category-list">
        <li class="menu-category">
          <a href="index.php#home" class="menu-title">
            <ion-icon name="home-outline" class="menu-icon"></ion-icon>
            <span>Home</span>
          </a>
        </li>

        <li class="menu-category">
          <a href="index.php#laptops-heading" class="menu-title">
            <ion-icon name="laptop-outline" class="menu-icon"></ion-icon>
            <span>Laptops</span>
          </a>
        </li>

        <li class="menu-category">
          <a href="index.php#desktops-heading" class="menu-title">
            <ion-icon name="desktop-outline" class="menu-icon"></ion-icon>
            <span>Desktops</span>
          </a>
        </li>
        
        <li class="menu-category">
          <a href="index.php#custom-builds-heading" class="menu-title">
            <ion-icon name="construct-outline" class="menu-icon"></ion-icon>
            <span>Custom Build</span>
          </a>
        </li>

        <li class="menu-category has-dropdown">
          <div class="menu-title">
            <ion-icon name="hardware-chip-outline" class="menu-icon"></ion-icon>
            <span>PC Parts</span>
            <ion-icon name="chevron-down-outline" class="dropdown-icon"></ion-icon>
          </div>
          
          <div class="dropdown-panel">
            <ul class="dropdown-panel-list">
              <li class="panel-list-item">
                <a href="index.php#display-screens-heading" class="pc-part-item">
                  <div class="item-icon">
                    <ion-icon name="tv-outline" class="dropdown-icon"></ion-icon>
                  </div>
                  <span>Display Screens</span>
                </a>
              </li>
              <li class="panel-list-item">
                <a href="index.php#processors-heading" class="pc-part-item">
                  <div class="item-icon">
                    <ion-icon name="flash-outline" class="dropdown-icon"></ion-icon>
                  </div>
                  <span>Processors</span>
                </a>
              </li>
              <li class="panel-list-item">
                <a href="index.php#graphics-cards-heading" class="pc-part-item">
                  <div class="item-icon">
                    <ion-icon name="extension-puzzle-outline" class="dropdown-icon"></ion-icon>
                  </div>
                  <span>Graphics Cards</span>
                </a>
              </li>
              <li class="panel-list-item">
                <a href="index.php#keyboards-heading" class="pc-part-item">
                  <div class="item-icon">
                    <ion-icon name="keypad-outline" class="dropdown-icon"></ion-icon>
                  </div>
                  <span>Keyboards</span>
                </a>
              </li>
            </ul>
          </div>
        </li>

        <li class="menu-category">
          <a href="contact.php?id=<?php echo (isset($_SESSION['customer_name'])) ? $_SESSION['id']: 'unknown';?>" class="menu-title">
            <ion-icon name="call-outline" class="menu-icon"></ion-icon>
            <span>Contact</span>
          </a>
        </li>

        <!-- Admin links will show only for admin users -->
       <?php if(isset($_SESSION['logged-in'])) { ?>
          <li class="menu-category">
            <a href="admin/post.php" class="menu-title admin-menu">
              <ion-icon name="shield-outline" class="menu-icon"></ion-icon>
              <span>Admin</span>
            </a>
          </li>
       <?php } ?>
      </ul>
    </div>
    
    <!-- User action icons on the right -->
    <div class="nav-user-actions">
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
        <button class="action-btn action-signup" title="Create Account">
          <a href="./signup.php">
            <ion-icon name="person-add-outline"></ion-icon>
          </a>
        </button>
      <?php } ?>

      <!-- Cart Button with enhanced notification -->
      <button class="action-btn action-cart" title="Shopping Cart">
        <a href="./cart.php">
          <ion-icon name="bag-handle-outline"></ion-icon>
          <?php 
          $total_cart_items = 0;
          if(isset($_SESSION['mycart'])) {
            $total_cart_items = count($_SESSION['mycart']);
          }
          if($total_cart_items > 0): 
          ?>
            <span class="count"><?php echo $total_cart_items; ?></span>
          <?php endif; ?>
        </a>
      </button>
    </div>
  </div>
</nav>

<!-- Search bar under navigation -->
<div class="nav-search-container">
  <div class="container">
    <form class="nav-search-form" method="post" action="./search.php">
      <div class="nav-search-wrapper">
        <input 
          type="search" 
          name="search" 
          class="nav-search-field" 
          placeholder="Search for products..." 
          required 
          oninvalid="this.setCustomValidity('Please enter a search term')" 
          oninput="this.setCustomValidity('')" 
        />
        <button class="nav-search-btn" type="submit" name="submit" aria-label="Search">
          <ion-icon name="search-outline"></ion-icon>
        </button>
      </div>
    </form>
  </div>
</div>