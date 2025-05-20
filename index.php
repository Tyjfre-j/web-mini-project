<?php
include_once('./includes/headerNav.php');

$banner_products = getBanners();
$categories = getCategories();
$product_types = getProductTypes();

// We'll load product data on demand rather than all at once
?>

<!-- Fix spacing between content and footer -->
<style>
  .product-container {
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
  }
  
  main {
    margin-bottom: 0 !important;
  }
  
  .container:last-child {
    margin-bottom: 0 !important;
  }
</style>

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
  <div class="banner" id="home">
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
    <div class="container">
      <h2 class="main-heading">Welcome to our store</h2>
      <p class="main-heading-desc">Explore our selection of high-quality computer hardware</p>
    </div>
    <?php
    // Loop through product types and display sections dynamically
    if ($product_types && mysqli_num_rows($product_types) > 0) {
      while ($type = mysqli_fetch_assoc($product_types)) {
        $type_name = $type['type_name'];
        
        // Create CSS-friendly ID from type name (lowercase, hyphens)
        $section_id = strtolower(str_replace(' ', '-', $type_name));
        
        // Special case for Graphics Cards and Processors for CSS class compatibility
        $css_class = $section_id;
        if ($section_id === 'graphics-cards') {
            $css_class = 'graphics-cards';
        } else if ($section_id === 'processors') {
            $css_class = 'processors';
        }
        
        // Only get products for this specific type (load on demand)
        $products = null;
        switch($type_name) {
          case 'Laptops':
            $products = getLaptops();
            break;
          case 'Desktops':
            $products = getDesktops();
            break;
          case 'Custom Builds':
            $products = getCustomBuilds();
            break;
          case 'Display Screens':
            $products = getDisplayScreens();
            break;
          case 'Graphics Cards':
            $products = getGraphicsCards();
            break;
          case 'Processors':
            $products = getProcessors();
            break;
          case 'Keyboards':
            $products = getKeyboards();
            break;
          default:
            continue 2; // Skip this iteration of the while loop if type name is unknown
        }
        
        // Check if we have products for this type
        if (!$products || mysqli_num_rows($products) == 0) {
          continue; // Skip this product type if no products are available
        }
    ?>
    <!-- Product section for <?php echo $type_name; ?> -->
    <div class="container product-section" id="<?php echo $section_id; ?>-section">
      <div class="title-with-button" id="<?php echo $section_id; ?>-title">
        <h2 class="product-section-title" id="<?php echo $section_id; ?>-heading"><?php echo $type_name; ?></h2>
        <a href="category.php?category=<?php echo urlencode($type_name); ?>" class="btn-primary view-all-btn">View All</a>
      </div>
      
      <div class="product-main">
        <div class="product-grid <?php echo $css_class; ?>-grid">
        <?php
        // Show only the first 4 products
        $count = 0;
        $max_items = 4;
        
        if ($products && $products->num_rows > 0) {
          while ($row = mysqli_fetch_assoc($products)) {
            $count++;
            if ($count <= $max_items) {
              // Define field names based on type name
              $id_field = $type_name . '_id';
              $name_field = $type_name . '_name';
              $image_field = $type_name . '_image_path';
              $desc_field = $type_name . '_small_description';
              $price_field = $type_name . '_price';
              $category_field = $type_name . '_category_name';
        ?>
          <a href="product.php?id=<?php echo $row[$id_field]; ?>&type=<?php echo urlencode($type_name); ?>" class="product-link <?php echo $section_id; ?>-card">
            <div class="showcase">
              <div class="showcase-banner">
                <img src="<?php echo htmlspecialchars($row[$image_field]); ?>" alt="<?php echo htmlspecialchars($row[$name_field]); ?>" class="product-img" loading="lazy" />
              </div>
              <div class="showcase-content">
                <div class="showcase-category"><?php echo htmlspecialchars($row[$category_field]); ?></div>
                <h4 class="showcase-title"><?php echo htmlspecialchars($row[$name_field]); ?></h4>
                <div class="showcase-small-desc"><?php echo htmlspecialchars($row[$desc_field]); ?></div>
                <div class="showcase-rating">
                  <ion-icon name="star"></ion-icon>
                  <ion-icon name="star"></ion-icon>
                  <ion-icon name="star"></ion-icon>
                  <ion-icon name="star"></ion-icon>
                  <ion-icon name="star"></ion-icon>
                </div>
                <div class="price-box">
                  <p class="price">Price: $<?php echo htmlspecialchars($row[$price_field]); ?></p>
                </div>
              </div>
            </div>
          </a>
        <?php
            }
          }
          // If we have fewer than 4 products, add empty placeholders to maintain grid
          $remaining = $max_items - $count;
          for ($i = 0; $i < $remaining; $i++) {
            echo '<div class="empty-product-slot"></div>';
          }
        } else {
          echo "No " . $type_name . " Found";
        }
        ?>
        </div>
      </div>
    </div>
    <?php
      }
    }
    ?>
  </div>
</main>

<?php require_once './includes/footer.php'; ?>

