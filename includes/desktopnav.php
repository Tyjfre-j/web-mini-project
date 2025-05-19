
<!-- desktop navigation -->
<nav class="desktop-navigation-menu">
  <div class="container">
    <ul class="desktop-menu-category-list">

      <li class="menu-category">
        <a href="index.php?id=<?php echo (isset( $_SESSION['customer_name']))? $_SESSION['id']: 'unknown';?>" class="menu-title">
          Home
        </a>
      </li>

      <li class="menu-category">
        <a href="index.php#laptops-heading" class="menu-title">Laptops</a>
      </li>

      <li class="menu-category">
        <a href="index.php#desktops-heading" class="menu-title">Desktops</a>
      </li>
      
      <li class="menu-category">
        <a href="index.php#custom-builds-heading" class="menu-title">Custom Build</a>
      </li>

      <li class="menu-category">
        <a href="#" class="menu-title">PC Parts</a>
        
        <div class="dropdown-panel">
          <ul class="dropdown-panel-list">
            <li class="panel-list-item">
              <a href="index.php#display-screens-heading">Display Screens</a>
            </li>
            <li class="panel-list-item">
              <a href="index.php#processors-heading">Processors</a>
            </li>
            <li class="panel-list-item">
              <a href="index.php#graphics-cards-heading">Graphics Cards</a>
            </li>
            <li class="panel-list-item">
              <a href="index.php#keyboards-heading">Keyboards</a>
            </li>
          </ul>
        </div>
      </li>

      <li class="menu-category">
        <a href="contact.php?id=<?php echo (isset( $_SESSION['customer_name']))? $_SESSION['id']: 'unknown';?>" class="menu-title">
          Contact
        </a>
      </li>

      <li class="menu-category">
        <a href="about.php?id=<?php echo (isset( $_SESSION['customer_name']))? $_SESSION['id']: 'unknown';?>" class="menu-title">About</a>
      </li>

      <!-- Profile Link Setup -->
      <?php if(isset($_SESSION['id'])) { ?>
        <!-- if logged in -->
        <li class="menu-category">
          <a href="profile.php?id=<?php echo (isset($_SESSION['customer_name']))? $_SESSION['id']: 'unknown';?>" class="menu-title">
            Profile
          </a>
        </li>
      <?php } else { ?>
        <!-- if not logged in -->
        <li class="menu-category">
          <a href="signup.php" class="menu-title">
            Sign Up
          </a>
        </li>
      <?php } ?>

      <!-- Visit Admin Panel After Login -->
	 <?php  if(isset($_SESSION['logged-in'])){?>
        <li class="menu-category">
          <a href="admin/post.php" class="menu-title">
            Admin Panel
          </a>
        </li> 
	<?php } ?>
      
      

    </ul>
  </div>
</nav>