<?php
$total_cart_items = 0;
if (isset($_SESSION['mycart'])) {
  $total_cart_items = count($_SESSION['mycart']);
}


?>

<!-- mobile bottom navigation -->
<div class="mobile-bottom-navigation">
  <button class="action-btn" data-mobile-menu-open-btn>
    <ion-icon name="menu-outline"></ion-icon>
  </button>

  <button class="action-btn">
    <a href="./cart.php">
      <ion-icon name="bag-handle-outline"></ion-icon>
    </a>

    <span class="count">
      <?php
      echo $total_cart_items;

      ?>
    </span>
  </button>

  <button class="action-btn">
    <a href="./index.php#home">
      <ion-icon name="home-outline"></ion-icon>
    </a>
  </button>

  <?php if (isset($_SESSION['id'])) { ?>
    <!-- Logout Button for logged-in users -->
    <button class="action-btn">
      <a href="logout.php" id="a" role="button">
        <ion-icon name="log-out-outline"></ion-icon>
      </a>
    </button>

  <?php } else { ?>
    <!-- Sign Up Button for non-logged users -->
    <button class="action-btn">
      <a href="./signup.php" id="a">
        <ion-icon name="person-add-outline"></ion-icon>
      </a>
    </button>

  <?php } ?>

  <button class="action-btn" data-mobile-menu-open-btn>
    <ion-icon name="grid-outline"></ion-icon>
  </button>
</div>

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
        <a href="./index.php#laptops-heading" class="menu-title">
          <ion-icon name="laptop-outline" class="menu-icon"></ion-icon>
          <span>Laptops</span>
        </a>
      </li>
      
      <li class="menu-category">
        <a href="./index.php#desktops-heading" class="menu-title">
          <ion-icon name="desktop-outline" class="menu-icon"></ion-icon>
          <span>Desktops</span>
        </a>
      </li>
      
      <li class="menu-category">
        <a href="./index.php#custom-builds-heading" class="menu-title">
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
            <a href="./index.php#display-screens-heading" class="submenu-title">
              <ion-icon name="tv-outline" class="submenu-icon"></ion-icon>
              <span>Display Screens</span>
            </a>
          </li>
          <li class="submenu-category">
            <a href="./index.php#processors-heading" class="submenu-title">
              <ion-icon name="flash-outline" class="submenu-icon"></ion-icon>
              <span>Processors</span>
            </a>
          </li>
          <li class="submenu-category">
            <a href="./index.php#graphics-cards-heading" class="submenu-title">
              <ion-icon name="extension-puzzle-outline" class="submenu-icon"></ion-icon>
              <span>Graphics Cards</span>
            </a>
          </li>
          <li class="submenu-category">
            <a href="./index.php#keyboards-heading" class="submenu-title">
              <ion-icon name="keypad-outline" class="submenu-icon"></ion-icon>
              <span>Keyboards</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="menu-category">
        <a href="contact.php" class="menu-title">
          <ion-icon name="call-outline" class="menu-icon"></ion-icon>
          <span>Contact</span>
        </a>
      </li>
      
      <?php if (isset($_SESSION['id'])) { ?>
        <li class="menu-category">
          <a href="profile.php?id=<?php echo (isset($_SESSION['customer_name'])) ? $_SESSION['id'] : 'unknown'; ?>" class="menu-title">
            <ion-icon name="person-outline" class="menu-icon"></ion-icon>
            <span>Profile</span>
          </a>
        </li>
      <?php } ?>

    </ul>

    <div class="menu-bottom">
      <ul class="menu-category-list">
        <li class="menu-category">
          <button class="accordion-menu" data-accordion-btn>
            <p class="menu-title">Language</p>

            <ion-icon name="caret-back-outline" class="caret-back"></ion-icon>
          </button>

          <ul class="submenu-category-list" data-accordion>
            <li class="submenu-category">
              <a href="#" class="submenu-title">English</a>
            </li>

            <li class="submenu-category">
              <a href="#" class="submenu-title">Espa&ntilde;ol</a>
            </li>

            <li class="submenu-category">
              <a href="#" class="submenu-title">Fren&ccedil;h</a>
            </li>
          </ul>
        </li>

        <li class="menu-category">
          <button class="accordion-menu" data-accordion-btn>
            <p class="menu-title">Currency</p>
            <ion-icon name="caret-back-outline" class="caret-back"></ion-icon>
          </button>

          <ul class="submenu-category-list" data-accordion>
            <li class="submenu-category">
              <a href="#" class="submenu-title">USD &dollar;</a>
            </li>

            <li class="submenu-category">
              <a href="#" class="submenu-title">EUR &euro;</a>
            </li>
          </ul>
        </li>
      </ul>

      <ul class="menu-social-container">
        <li>
          <a href="#" class="social-link">
            <ion-icon name="logo-facebook"></ion-icon>
          </a>
        </li>

        <li>
          <a href="#" class="social-link">
            <ion-icon name="logo-twitter"></ion-icon>
          </a>
        </li>

        <li>
          <a href="#" class="social-link">
            <ion-icon name="logo-instagram"></ion-icon>
          </a>
        </li>

        <li>
          <a href="#" class="social-link">
            <ion-icon name="logo-linkedin"></ion-icon>
          </a>
        </li>
      </ul>
    </div>
</nav>