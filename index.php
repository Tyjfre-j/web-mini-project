<?php
include_once('./includes/headerNav.php');

$banner_products = getBanners();
$categories = getCategories();
$product_types = getProductTypes();

// Get all products
$laptops = getLaptops();
$desktops = getDesktops();
$customBuilds = getCustomBuilds();
$displayScreens = getDisplayScreens();
$graphicsCards = getGraphicsCards();
$processors = getProcessors();
$keyboards = getKeyboards();
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
    <div class="container">
      <h2 class="main-heading">Featured Products</h2>
      <p class="main-heading-desc">Explore our selection of high-quality computer hardware and accessories</p>
    </div>
    <?php
    // Loop through product types and display sections dynamically
    if ($product_types && mysqli_num_rows($product_types) > 0) {
      while ($type = mysqli_fetch_assoc($product_types)) {
        $type_name = $type['type_name'];
        $variable_name = '';
        
        // Map table name to variable name
        switch($type_name) {
          case 'Laptops':
            $variable_name = 'laptops';
            break;
          case 'Desktops':
            $variable_name = 'desktops';
            break;
          case 'Custom Builds':
            $variable_name = 'customBuilds';
            break;
          case 'Display Screens':
            $variable_name = 'displayScreens';
            break;
          case 'Graphics Cards':
            $variable_name = 'graphicsCards';
            break;
          case 'Processors':
            $variable_name = 'processors';
            break;
          case 'Keyboards':
            $variable_name = 'keyboards';
            break;
          default:
            continue 2; // Skip this iteration of the while loop if type name is unknown
        }
        
        // Access the appropriate products variable
        $products = $$variable_name;
        
        // Check if we have products for this type
        if (!$products || mysqli_num_rows($products) == 0) {
          continue; // Skip this product type if no products are available
        }
        
        // Create CSS-friendly ID from type name (lowercase, hyphens)
        $section_id = strtolower(str_replace(' ', '-', $type_name));
        
        // Special case for Graphics Cards and Processors for CSS class compatibility
        $css_class = $section_id;
        if ($section_id === 'graphics-cards') {
            $css_class = 'graphics-cards';
        } else if ($section_id === 'processors') {
            $css_class = 'processors';
        }
    ?>
    <div class="section-container" style="margin-bottom: 2rem;">
      <div class="container title-only-container" id="<?php echo $section_id; ?>-title">
        <div class="title-with-button">
          <h2 class="product-section-title"><?php echo $type_name; ?></h2>
          <a href="category.php?category=<?php echo urlencode($type_name); ?>" class="btn-primary view-all-btn">View All <?php echo $type_name; ?></a>
        </div>
      </div>
      
      <div class="container" id="<?php echo $section_id; ?>-section">
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
                  <img src="<?php echo htmlspecialchars($row[$image_field]); ?>" alt="<?php echo htmlspecialchars($row[$name_field]); ?>" class="product-img" />
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
      </div>
    </div>
    <?php
      }
    }
    ?>
  </div>
</main>

<?php require_once './includes/footer.php'; ?>

