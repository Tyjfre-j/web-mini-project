<?php
include_once('./includes/headerNav.php');
// Get all banner products
$banner_products = get_banners();
// Get categories
$categories = get_categories();
// 
?>

<div class="overlay" data-overlay></div>
<script src="js/index.js"></script>

<!--
    - HEADER
  -->
<header>
  <!-- top head action, search etc in php -->
  <!-- inc/topheadactions.php -->
  <?php require_once './includes/topheadactions.php'; ?>
  <!-- desktop navigation -->
  <!-- inc/desktopnav.php -->
  <?php require_once './includes/desktopnav.php' ?>
  <!-- mobile nav in php -->
  <!-- inc/mobilenav.php -->
  <?php require_once './includes/mobilenav.php'; ?>
</header>

<!--
    - MAIN
  -->
<main>
  <!--
      - BANNER: Coursal
    -->
  <div class="banner">
    <div class="container">
      <div class="slider-container has-scrollbar">
        <!-- Display data from db in banner -->
        <?php
        $banner_count = 0;
        while ($row = mysqli_fetch_assoc($banner_products)) {
          $banner_count++;
        ?>
          <div class="slider-item" data-banner="<?php echo $banner_count; ?>" style="display: none;">
            <img src="images/carousel/<?php echo $row['banner_image']; ?>" class="banner-img" />

            <div class="banner-content">
              <h2 class="banner-title">
                <?php echo $row['banner_title']; ?>
              </h2>
              <p class="banner-text"><?php echo $row['banner_text'];?></p>
              <a href="#" class="banner-btn">Shop now</a>
            </div>
          </div>
        <?php
        }
        ?>
      </div>
    </div>
  </div>

  <!--
      - PRODUCT
    -->
  <div class="product-container">
    <!-- LAPTOPS -->
    <div class="container" style="margin-bottom: 1.2rem;" id="laptops-section">
      <div class="product-main">
        <h2 class="title">Laptops</h2>
        <div class="product-grid laptop-grid">
          <?php
          $query = "SELECT * FROM laptop";
          $result = mysqli_query($conn, $query);
          if ($result && $result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
          ?>
              <a href="product.php?id=<?php echo $row['id']; ?>&type=laptop" class="product-link">
                <div class="showcase">
                  <div class="showcase-banner">
                    <img src="<?php echo htmlspecialchars($row['laptop_img']); ?>" alt="<?php echo htmlspecialchars($row['laptop_name']); ?>" class="product-img" />
                  </div>
                  <div class="showcase-content">
                    <div class="showcase-category"><?php echo htmlspecialchars($row['laptop_category_name']); ?></div>
                    <h3 class="showcase-title"><?php echo htmlspecialchars($row['laptop_name']); ?></h3>
                    <div class="showcase-small-desc"><?php echo htmlspecialchars($row['laptop_small_description']); ?></div>
                    <div class="showcase-rating">
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                    </div>
                    <div class="price-box">
                      <p class="price">Price: $<?php echo htmlspecialchars($row['laptop_price']); ?></p>
                    </div>
                  </div>
                </div>
              </a>
          <?php
            }
          } else {
            echo "No Laptops Found";
          }
          ?>
        </div>
      </div>
    </div>
    <!-- DESKTOPS -->
    <div class="container" style="margin-bottom: 1.2rem; " id="desktops-section">
      <div class="product-main">
        <h2 class="title">Desktops</h2>
        <div class="product-grid">
          <?php
          $query = "SELECT * FROM desktop";
          $result = mysqli_query($conn, $query);
          if ($result && $result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
          ?>
              <a href="product.php?id=<?php echo $row['id']; ?>&type=desktop" class="product-link">
                <div class="showcase">
                  <div class="showcase-banner">
                    <img src="<?php echo htmlspecialchars($row['table_img']); ?>" alt="<?php echo htmlspecialchars($row['desktop_name']); ?>" class="product-img" />
                  </div>
                  <div class="showcase-content">
                    <div class="showcase-category"><?php echo htmlspecialchars($row['desktop_category_name']); ?></div>
                    <h3 class="showcase-title"><?php echo htmlspecialchars($row['desktop_name']); ?></h3>
                    <div class="showcase-small-desc"><?php echo htmlspecialchars($row['desktop_small_description']); ?></div>
                    <div class="showcase-rating">
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                    </div>
                    <div class="price-box">
                      <p class="price">Price: $<?php echo htmlspecialchars($row['desktop_price']); ?></p>
                    </div>
                  </div>
                </div>
              </a>
          <?php
            }
          } else {
            echo "No Desktops Found";
          }
          ?>
        </div>
      </div>
    </div>
    <!-- CUSTOM BUILDS -->
    <div class="container" style="margin-bottom: 1.2rem;" id="custombuilds-section">
      <div class="product-main">
        <h2 class="title">Custom Builds</h2>
        <div class="product-grid">
          <?php
          $query = "SELECT * FROM customeBuild";
          $result = mysqli_query($conn, $query);
          if ($result && $result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
          ?>
              <a href="product.php?id=<?php echo $row['id']; ?>&type=customebuild" class="product-link">
                <div class="showcase">
                  <div class="showcase-banner">
                    <img src="<?php echo htmlspecialchars($row['table_img']); ?>" alt="<?php echo htmlspecialchars($row['customeBuild_name']); ?>" class="product-img" />
                  </div>
                  <div class="showcase-content">
                    <div class="showcase-category"><?php echo htmlspecialchars($row['customeBuild_category_name']); ?></div>
                    <h3 class="showcase-title"><?php echo htmlspecialchars($row['customeBuild_name']); ?></h3>
                    <div class="showcase-small-desc"><?php echo htmlspecialchars($row['customeBuild_small_description']); ?></div>
                    <div class="showcase-rating">
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                    </div>
                    <div class="price-box">
                      <p class="price">Price: $<?php echo htmlspecialchars($row['customeBuild_price']); ?></p>
                    </div>
                  </div>
                </div>
              </a>
          <?php
            }
          } else {
            echo "No Custom Builds Found";
          }
          ?>
        </div>
      </div>
    </div>
    <!-- DISPLAYS -->
    <div class="container" style="margin-bottom: 1.2rem;" id="displays-section">
      <div class="product-main">
        <h2 class="title">Displays</h2>
        <div class="product-grid">
          <?php
          $query = "SELECT * FROM display";
          $result = mysqli_query($conn, $query);
          if ($result && $result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
          ?>
              <a href="product.php?id=<?php echo $row['id']; ?>&type=display" class="product-link">
                <div class="showcase">
                  <div class="showcase-banner">
                    <img src="<?php echo htmlspecialchars($row['table_img']); ?>" alt="<?php echo htmlspecialchars($row['display_name']); ?>" class="product-img" />
                  </div>
                  <div class="showcase-content">
                    <div class="showcase-category"><?php echo htmlspecialchars($row['display_category_name']); ?></div>
                    <h3 class="showcase-title"><?php echo htmlspecialchars($row['display_name']); ?></h3>
                    <div class="showcase-small-desc"><?php echo htmlspecialchars($row['display_small_description']); ?></div>
                    <div class="showcase-rating">
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                    </div>
                    <div class="price-box">
                      <p class="price">Price: $<?php echo htmlspecialchars($row['display_price']); ?></p>
                    </div>
                  </div>
                </div>
              </a>
          <?php
            }
          } else {
            echo "No Displays Found";
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</main>

<?php require_once './includes/footer.php'; ?>

