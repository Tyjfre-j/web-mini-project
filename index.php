<?php
include_once('./includes/headerNav.php');
// Get all banner products
$banner_products = get_banners();
// Get categories
$categories = get_categories();
// Get products using new functions
$laptops = get_laptops();
$desktops = get_desktops();
$custombuilds = get_custombuilds();
$displayscreens = get_displayscreens();
$gpus = get_gpus();
$cpus = get_cpus();
$keyboards = get_keyboards();
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
            <img src="<?php echo htmlspecialchars($row['banner_image_path']); ?>" class="banner-img" />

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
          if ($laptops && $laptops->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($laptops)) {
          ?>
              <a href="product.php?id=<?php echo $row['laptop_id']; ?>&type=laptop" class="product-link">
                <div class="showcase">
                  <div class="showcase-banner">
                    <img src="<?php echo htmlspecialchars($row['laptop_image_path']); ?>" alt="<?php echo htmlspecialchars($row['laptop_name']); ?>" class="product-img" />
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
          if ($desktops && $desktops->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($desktops)) {
          ?>
              <a href="product.php?id=<?php echo $row['desktop_id']; ?>&type=desktop" class="product-link">
                <div class="showcase">
                  <div class="showcase-banner">
                    <img src="<?php echo htmlspecialchars($row['desktop_image_path']); ?>" alt="<?php echo htmlspecialchars($row['desktop_name']); ?>" class="product-img" />
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
          if ($custombuilds && $custombuilds->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($custombuilds)) {
          ?>
              <a href="product.php?id=<?php echo $row['custombuild_id']; ?>&type=custombuild" class="product-link">
                <div class="showcase">
                  <div class="showcase-banner">
                    <img src="<?php echo htmlspecialchars($row['custombuild_image_path']); ?>" alt="<?php echo htmlspecialchars($row['custombuild_name']); ?>" class="product-img" />
                  </div>
                  <div class="showcase-content">
                    <div class="showcase-category"><?php echo htmlspecialchars($row['custombuild_category_name']); ?></div>
                    <h3 class="showcase-title"><?php echo htmlspecialchars($row['custombuild_name']); ?></h3>
                    <div class="showcase-small-desc"><?php echo htmlspecialchars($row['custombuild_small_description']); ?></div>
                    <div class="showcase-rating">
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                    </div>
                    <div class="price-box">
                      <p class="price">Price: $<?php echo htmlspecialchars($row['custombuild_price']); ?></p>
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
    <!-- DISPLAYSCREENS -->
    <div class="container" style="margin-bottom: 1.2rem;" id="displays-section">
      <div class="product-main">
        <h2 class="title">Displayscreens</h2>
        <div class="product-grid">
          <?php
          if ($displayscreens && $displayscreens->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($displayscreens)) {
          ?>
              <a href="product.php?id=<?php echo $row['displayscreen_id']; ?>&type=display" class="product-link">
                <div class="showcase">
                  <div class="showcase-banner">
                    <img src="<?php echo htmlspecialchars($row['displayscreen_image_path']); ?>" alt="<?php echo htmlspecialchars($row['displayscreen_name']); ?>" class="product-img" />
                  </div>
                  <div class="showcase-content">
                    <div class="showcase-category"><?php echo htmlspecialchars($row['displayscreen_category_name']); ?></div>
                    <h3 class="showcase-title"><?php echo htmlspecialchars($row['displayscreen_name']); ?></h3>
                    <div class="showcase-small-desc"><?php echo htmlspecialchars($row['displayscreen_small_description']); ?></div>
                    <div class="showcase-rating">
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                      <ion-icon name="star"></ion-icon>
                    </div>
                    <div class="price-box">
                      <p class="price">Price: $<?php echo htmlspecialchars($row['displayscreen_price']); ?></p>
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
    <!-- GPUS -->
    <div class="container" style="margin-bottom: 1.2rem;" id="gpus-section">
      <div class="product-main">
        <div style="display: flex; align-items: center; justify-content: space-between;">
          <h2 class="title">GPUs</h2>
          <button class="show-toggle-btn" data-target="gpu">Show all</button>
        </div>
        <div class="product-grid gpu-grid">
          <?php
          $gpu_count = 0;
          if ($gpus && $gpus->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($gpus)) {
              $gpu_count++;
          ?>
            <a href="product.php?id=<?php echo $row['gpu_id']; ?>&type=gpu" class="product-link gpu-card" style="<?php echo $gpu_count > 4 ? 'display:none;' : '' ?>">
              <div class="showcase">
                <div class="showcase-banner">
                  <img src="<?php echo htmlspecialchars($row['gpu_image_path']); ?>" alt="<?php echo htmlspecialchars($row['gpu_name']); ?>" class="product-img" />
                </div>
                <div class="showcase-content">
                  <div class="showcase-category"><?php echo htmlspecialchars($row['gpu_category_name']); ?></div>
                  <h3 class="showcase-title"><?php echo htmlspecialchars($row['gpu_name']); ?></h3>
                  <div class="showcase-small-desc"><?php echo htmlspecialchars($row['gpu_small_description']); ?></div>
                  <div class="showcase-rating">
                    <ion-icon name="star"></ion-icon>
                    <ion-icon name="star"></ion-icon>
                    <ion-icon name="star"></ion-icon>
                    <ion-icon name="star"></ion-icon>
                    <ion-icon name="star"></ion-icon>
                  </div>
                  <div class="price-box">
                    <p class="price">Price: $<?php echo htmlspecialchars($row['gpu_price']); ?></p>
                  </div>
                </div>
              </div>
            </a>
          <?php
            }
          } else {
            echo "No GPUs Found";
          }
          ?>
        </div>
      </div>
    </div>
    <!-- CPUS -->
    <div class="container" style="margin-bottom: 1.2rem;" id="cpus-section">
      <div class="product-main">
        <div style="display: flex; align-items: center; justify-content: space-between;">
          <h2 class="title">CPUs</h2>
          <button class="show-toggle-btn" data-target="cpu">Show all</button>
        </div>
        <div class="product-grid cpu-grid">
          <?php
          $cpu_count = 0;
          if ($cpus && $cpus->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($cpus)) {
              $cpu_count++;
          ?>
            <a href="product.php?id=<?php echo $row['cpu_id']; ?>&type=cpu" class="product-link cpu-card" style="<?php echo $cpu_count > 4 ? 'display:none;' : '' ?>">
              <div class="showcase">
                <div class="showcase-banner">
                  <img src="<?php echo htmlspecialchars($row['cpu_image_path']); ?>" alt="<?php echo htmlspecialchars($row['cpu_name']); ?>" class="product-img" />
                </div>
                <div class="showcase-content">
                  <div class="showcase-category"><?php echo htmlspecialchars($row['cpu_category_name']); ?></div>
                  <h3 class="showcase-title"><?php echo htmlspecialchars($row['cpu_name']); ?></h3>
                  <div class="showcase-small-desc"><?php echo htmlspecialchars($row['cpu_small_description']); ?></div>
                  <div class="showcase-rating">
                    <ion-icon name="star"></ion-icon>
                    <ion-icon name="star"></ion-icon>
                    <ion-icon name="star"></ion-icon>
                    <ion-icon name="star"></ion-icon>
                    <ion-icon name="star"></ion-icon>
                  </div>
                  <div class="price-box">
                    <p class="price">Price: $<?php echo htmlspecialchars($row['cpu_price']); ?></p>
                  </div>
                </div>
              </div>
            </a>
          <?php
            }
          } else {
            echo "No CPUs Found";
          }
          ?>
        </div>
      </div>
    </div>
    <!-- KEYBOARDS -->
    <div class="container" style="margin-bottom: 1.2rem;" id="keyboards-section">
      <div class="product-main">
        <div style="display: flex; align-items: center; justify-content: space-between;">
          <h2 class="title">Keyboards</h2>
          <button class="show-toggle-btn" data-target="keyboard">Show all</button>
        </div>
        <div class="product-grid keyboard-grid">
          <?php
          $keyboard_count = 0;
          if ($keyboards && $keyboards->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($keyboards)) {
              $keyboard_count++;
          ?>
            <a href="product.php?id=<?php echo $row['keyboard_id']; ?>&type=keyboard" class="product-link keyboard-card" style="<?php echo $keyboard_count > 4 ? 'display:none;' : '' ?>">
              <div class="showcase">
                <div class="showcase-banner">
                  <img src="<?php echo htmlspecialchars($row['keyboard_image_path']); ?>" alt="<?php echo htmlspecialchars($row['keyboard_name']); ?>" class="product-img" />
                </div>
                <div class="showcase-content">
                  <div class="showcase-category"><?php echo htmlspecialchars($row['keyboard_category_name']); ?></div>
                  <h3 class="showcase-title"><?php echo htmlspecialchars($row['keyboard_name']); ?></h3>
                  <div class="showcase-small-desc"><?php echo htmlspecialchars($row['keyboard_small_description']); ?></div>
                  <div class="showcase-rating">
                    <ion-icon name="star"></ion-icon>
                    <ion-icon name="star"></ion-icon>
                    <ion-icon name="star"></ion-icon>
                    <ion-icon name="star"></ion-icon>
                    <ion-icon name="star"></ion-icon>
                  </div>
                  <div class="price-box">
                    <p class="price">Price: $<?php echo htmlspecialchars($row['keyboard_price']); ?></p>
                  </div>
                </div>
              </div>
            </a>
          <?php
            }
          } else {
            echo "No Keyboards Found";
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</main>

<script>
// Toggle show all/show less for each product type
const toggleButtons = document.querySelectorAll('.show-toggle-btn');
toggleButtons.forEach(btn => {
  btn.addEventListener('click', function() {
    const type = btn.getAttribute('data-target');
    const cards = document.querySelectorAll('.' + type + '-card');
    const isShowingAll = btn.textContent === 'Show less';
    cards.forEach((card, idx) => {
      if (isShowingAll) {
        card.style.display = idx < 4 ? '' : 'none';
      } else {
        card.style.display = '';
      }
    });
    btn.textContent = isShowingAll ? 'Show all' : 'Show less';
  });
});
</script>
<style>
.product-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1.2rem;
}
</style>

<?php require_once './includes/footer.php'; ?>

