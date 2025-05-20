<!-- desktop navigation -->
<nav class="desktop-navigation-menu">
  <div class="container">
    <ul class="desktop-menu-category-list">

      <li class="menu-category">
        <a href="index.php#home" class="menu-title">
          <ion-icon name="home-outline" class="menu-icon"></ion-icon>
          <span>Home</span>
        </a>
      </li>

      <li class="menu-category">
        <a href="index.php#laptops-section" class="menu-title">
          <ion-icon name="laptop-outline" class="menu-icon"></ion-icon>
          <span>Laptops</span>
        </a>
      </li>

      <li class="menu-category">
        <a href="index.php#desktops-section" class="menu-title">
          <ion-icon name="desktop-outline" class="menu-icon"></ion-icon>
          <span>Desktops</span>
        </a>
      </li>
      
      <li class="menu-category">
        <a href="index.php#custom-builds-section" class="menu-title">
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
              <a href="index.php#display-screens-section" class="pc-part-item">
                <div class="item-icon">
                  <ion-icon name="tv-outline" class="dropdown-icon"></ion-icon>
                </div>
                <span>Display Screens</span>
              </a>
            </li>
            <li class="panel-list-item">
              <a href="index.php#processors-section" class="pc-part-item">
                <div class="item-icon">
                  <ion-icon name="flash-outline" class="dropdown-icon"></ion-icon>
                </div>
                <span>Processors</span>
              </a>
            </li>
            <li class="panel-list-item">
              <a href="index.php#graphics-cards-section" class="pc-part-item">
                <div class="item-icon">
                  <ion-icon name="extension-puzzle-outline" class="dropdown-icon"></ion-icon>
                </div>
                <span>Graphics Cards</span>
              </a>
            </li>
            <li class="panel-list-item">
              <a href="index.php#keyboards-section" class="pc-part-item">
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

      <!-- Profile Link Setup -->
      <?php if(isset($_SESSION['id'])) { ?>
        <!-- if logged in -->
        <li class="menu-category">
          <a href="profile.php?id=<?php echo (isset($_SESSION['customer_name'])) ? $_SESSION['id']: 'unknown';?>" class="menu-title highlight-menu">
            <ion-icon name="person-outline" class="menu-icon"></ion-icon>
            <span>Profile</span>
          </a>
        </li>
      <?php } ?>

      <!-- Visit Admin Panel After Login -->
     <?php if(isset($_SESSION['logged-in'])) { ?>
        <li class="menu-category">
          <a href="admin/post.php" class="menu-title admin-menu">
            <ion-icon name="shield-outline" class="menu-icon"></ion-icon>
            <span>Admin Panel</span>
          </a>
        </li>
        
        <!-- Database Schema Info -->
        <li class="menu-category">
          <a href="schema_info.php" class="menu-title admin-menu">
            <ion-icon name="server-outline" class="menu-icon"></ion-icon>
            <span>DB Schema</span>
          </a>
        </li>
    <?php } ?>

    </ul>
  </div>
</nav>