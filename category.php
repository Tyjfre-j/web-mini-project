<!--  -->
<?php include_once('./includes/headerNav.php'); ?>

<?php
// Get category from URL (GET method is appropriate for navigation)
$category = isset($_GET['category']) ? $_GET['category'] : '';
if (empty($category)) {
    header("Location: index.php");
    exit();
}

// Get sort parameters if present (from either POST for filters or GET for direct access)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Form was submitted, get values from POST
    $sort_by = isset($_POST['sort_by']) ? $_POST['sort_by'] : 'default';
    $filter_category = isset($_POST['filter_category']) ? $_POST['filter_category'] : 'all';
    $min_price = isset($_POST['min_price']) ? floatval($_POST['min_price']) : 0;
    $max_price = isset($_POST['max_price']) ? floatval($_POST['max_price']) : 10000;
} else {
    // Direct access via URL, get values from GET if available
    $sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'default';
    $filter_category = isset($_GET['filter_category']) ? $_GET['filter_category'] : 'all';
    $min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
    $max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : 10000;
}

// Get all categories for filtering
$categories = getCategories();

// Get products of the specified category
$products = getItemsByCategoryItems($category);

// If we found products, process them
if ($products && $products->num_rows > 0) {
    // Store products in an array for sorting
    $productsArray = [];
    
    while ($row = mysqli_fetch_assoc($products)) {
        $productsArray[] = $row;
    }
    
    // Apply filters
    $filteredProducts = [];
    
    foreach ($productsArray as $product) {
        $price_field = $category . '_price';
        $category_field = $category . '_category_name';
        
        // Apply price filter
        $price = floatval($product[$price_field]);
        if ($price < $min_price || $price > $max_price) {
            continue;
        }
        
        // Apply category filter if not "all"
        if ($filter_category !== 'all') {
            if ($product[$category_field] !== $filter_category) {
                continue;
            }
        }
        
        $filteredProducts[] = $product;
    }
    
    // Sort products
    if ($sort_by === 'price_low') {
        usort($filteredProducts, function($a, $b) use ($category) {
            $price_field = $category . '_price';
            return $a[$price_field] - $b[$price_field];
        });
    } elseif ($sort_by === 'price_high') {
        usort($filteredProducts, function($a, $b) use ($category) {
            $price_field = $category . '_price';
            return $b[$price_field] - $a[$price_field];
        });
    } elseif ($sort_by === 'newest') {
        usort($filteredProducts, function($a, $b) use ($category) {
            $date_field = $category . '_created_at';
            return strtotime($b[$date_field]) - strtotime($a[$date_field]);
        });
    }
}
?>

<!-- Include CSS files -->
<link rel="stylesheet" href="css/category.css">
<!-- Use the same product display styles as index page -->
<link rel="stylesheet" href="css/product-display.css">

<!-- Include JavaScript files -->
<script src="js/index.js"></script>
<script src="js/product-effects.js"></script>

<div class="overlay" data-overlay></div>
<!--
    - HEADER
  -->
<header>
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
  <div class="product-container" id="home">
    
      <div class="category-header">
        <h2 class="main-heading"><?php echo $category; ?></h2>
        <p class="main-heading-desc">Browse our collection of <?php echo strtolower($category); ?></p>
        
        <?php if (isset($filteredProducts)): ?>
        <div class="product-count">
          <span><?php echo count($filteredProducts); ?> products found</span>
        </div>
        <?php endif; ?>
      </div>

    <!-- Filter and sort section -->
    <div class="container">
      <div class="filter-container">
        <form action="" method="POST" class="filter-form" onchange="return false;">
          <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
          
          <div class="filter-group">
            <label for="sort_by">Sort By:</label>
            <select name="sort_by" id="sort_by" class="filter-select">
              <option value="default" <?php echo $sort_by === 'default' ? 'selected' : ''; ?>>Default</option>
              <option value="price_low" <?php echo $sort_by === 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
              <option value="price_high" <?php echo $sort_by === 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
              <option value="newest" <?php echo $sort_by === 'newest' ? 'selected' : ''; ?>>Newest First</option>
            </select>
          </div>
          
          <div class="filter-group">
            <label for="filter_category">Category:</label>
            <select name="filter_category" id="filter_category" class="filter-select">
              <option value="all" <?php echo $filter_category === 'all' ? 'selected' : ''; ?>>All Categories</option>
              <?php 
              // Reset the categories result pointer
              mysqli_data_seek($categories, 0);
              while($cat = mysqli_fetch_assoc($categories)) { 
              ?>
                <option value="<?php echo $cat['category_name']; ?>" <?php echo $filter_category === $cat['category_name'] ? 'selected' : ''; ?>>
                  <?php echo ucfirst($cat['category_name']); ?>
                </option>
              <?php } ?>
            </select>
          </div>
          
          <div class="filter-group price-filter">
            <label>Price Range:</label>
            <div class="price-inputs">
              <div class="price-input">
                <label for="min_price">$</label>
                <input type="number" name="min_price" id="min_price" min="0" max="10000" step="10" value="<?php echo $min_price; ?>">
              </div>
              <span>to</span>
              <div class="price-input">
                <label for="max_price">$</label>
                <input type="number" name="max_price" id="max_price" min="0" max="10000" step="10" value="<?php echo $max_price; ?>">
              </div>
            </div>
          </div>
          
          <div class="filter-actions">
            <button type="submit" class="btn-filter">Apply Filters</button>
            <a href="category.php?category=<?php echo urlencode($category); ?>" class="btn-reset-filter">Reset</a>
          </div>
        </form>
      </div>
    </div>

    <!-- Products grid -->
    <div class="container">
      <div class="product-main">
        <div class="product-grid">
            <?php
          if (isset($filteredProducts) && count($filteredProducts) > 0) {
            $section_id = strtolower(str_replace(' ', '-', $category));
            
            foreach ($filteredProducts as $product) {
              // Define field names based on category
              $id_field = $category . '_id';
              $name_field = $category . '_name';
              $image_field = $category . '_image_path';
              $desc_field = $category . '_small_description';
              $price_field = $category . '_price';
              $category_field = $category . '_category_name';
          ?>
            <a href="product.php?id=<?php echo $product[$id_field]; ?>&type=<?php echo urlencode($category); ?>" class="product-link <?php echo $section_id; ?>-card">
              <div class="showcase">
                <div class="showcase-banner">
                  <img src="<?php echo htmlspecialchars($product[$image_field]); ?>" alt="<?php echo htmlspecialchars($product[$name_field]); ?>" class="product-img" />
                </div>
                <div class="showcase-content">
                  <div class="showcase-category"><?php echo htmlspecialchars($product[$category_field]); ?></div>
                  <h4 class="showcase-title"><?php echo htmlspecialchars($product[$name_field]); ?></h4>
                  <div class="showcase-small-desc"><?php echo htmlspecialchars($product[$desc_field]); ?></div>
                  <div class="showcase-rating">
                    <ion-icon name="star"></ion-icon>
                    <ion-icon name="star"></ion-icon>
                    <ion-icon name="star"></ion-icon>
                    <ion-icon name="star"></ion-icon>
                    <ion-icon name="star"></ion-icon>
                  </div>
                  <div class="price-box">
                    <p class="price">Price: $<?php echo htmlspecialchars($product[$price_field]); ?></p>
                  </div>
                </div>
              </div>
            </a>
            <?php
            }
          } else {
            echo '<div class="no-products-message">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <circle cx="12" cy="12" r="10"></circle>
                      <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line>
                    </svg>
                    <p>No products found matching your filters.</p>
                    <p class="tip">Try adjusting your search criteria or <a href="category.php?category=' . urlencode($category) . '">view all ' . $category . '</a></p>
                  </div>';
          }
          ?>
        </div>
      </div>
    </div>
  </div>

  <!--
      - TESTIMONIALS, CTA & SERVICE
    -->

  <!--
      - BLOG
    -->

</main>

<?php require_once './includes/footer.php'; ?>

<!-- Include ionic icons (which is likely already in the headerNav.php but including here as a safeguard) -->
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

<!-- Include Category Page JavaScript -->
<script src="js/category.js"></script>
