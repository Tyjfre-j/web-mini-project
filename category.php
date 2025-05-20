<!--  -->
<?php include_once('./includes/headerNav.php'); ?>

<?php
// Check if it's a search query or a regular category page request
$is_search = isset($_GET['search_query']) && !empty($_GET['search_query']);

// For search queries
$search_query = $is_search ? $_GET['search_query'] : '';

// For category browsing
$category = '';
if (!$is_search) {
    $category = isset($_GET['category']) ? $_GET['category'] : '';
    if (empty($category)) {
        header("Location: index.php");
        exit();
    }
}

// Get sort parameters if present (always from GET for both search and direct category access)
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'default';
$filter_category = isset($_GET['filter_category']) ? $_GET['filter_category'] : 'all';
$filter_type = isset($_GET['filter_type']) ? $_GET['filter_type'] : 'all';
$min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
$max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : 10000;

// If there was a POST request (for category browsing with filters), get values from POST instead
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sort_by = isset($_POST['sort_by']) ? $_POST['sort_by'] : $sort_by;
    $filter_category = isset($_POST['filter_category']) ? $_POST['filter_category'] : $filter_category;
    $filter_type = isset($_POST['filter_type']) ? $_POST['filter_type'] : $filter_type;
    $min_price = isset($_POST['min_price']) ? floatval($_POST['min_price']) : $min_price;
    $max_price = isset($_POST['max_price']) ? floatval($_POST['max_price']) : $max_price;
    
    // Redirect to ensure filters are in the URL for bookmark/refresh
    $redirect_url = "category.php?";
    if ($is_search) {
        $redirect_url .= "search_query=" . urlencode($search_query);
    } else {
        $redirect_url .= "category=" . urlencode($category);
    }
    $redirect_url .= "&sort_by=" . urlencode($sort_by);
    $redirect_url .= "&filter_category=" . urlencode($filter_category);
    if ($is_search) {
        $redirect_url .= "&filter_type=" . urlencode($filter_type);
    }
    $redirect_url .= "&min_price=" . $min_price;
    $redirect_url .= "&max_price=" . $max_price;
    
    header("Location: " . $redirect_url);
    exit();
}

// Get the current page for pagination
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$items_per_page = 8; // Number of items per page

// Get all categories for filtering
$categories = getCategories();

// Process page differently depending on whether it's a search or category browsing
if ($is_search) {
    // Use the searchProducts function to get search results with pagination
    $search_results = searchProducts($search_query, $sort_by, $min_price, $max_price, $page, $items_per_page, $filter_type, $filter_category);
    $search_data = $search_results['results'];
    $total_products = $search_results['total_products'];
    $total_pages = $search_results['total_pages'];
    
    // Convert search results to array for display
    $productsArray = [];
    if ($search_data && mysqli_num_rows($search_data) > 0) {
        while($row = mysqli_fetch_assoc($search_data)) {
            $productsArray[] = $row;
        }
    }
    
    // No additional filtering needed as it's done in the SQL query
    $filteredProducts = $productsArray;
} else {
    // Regular category browsing
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
        
        // Basic pagination (not as efficient as SQL-based pagination but works with the filtered results)
        $total_products = count($filteredProducts);
        $total_pages = ceil($total_products / $items_per_page);
        
        // Get the subset of products for the current page
        $start_index = ($page - 1) * $items_per_page;
        $filteredProducts = array_slice($filteredProducts, $start_index, $items_per_page);
    } else {
        $filteredProducts = [];
        $total_products = 0;
        $total_pages = 0;
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
  <div class="product-container" id="home">
    
      <div class="category-header">
        <?php if ($is_search): ?>
            <h2 class="main-heading">Search Results</h2>
            <p class="main-heading-desc">Showing products with names matching: <strong>"<?php echo htmlspecialchars($search_query); ?>"</strong></p>
        <?php else: ?>
            <h2 class="main-heading"><?php echo $category; ?></h2>
            <p class="main-heading-desc">Browse our collection of <?php echo strtolower($category); ?></p>
        <?php endif; ?>
        
        <?php if (isset($filteredProducts)): ?>
        <div class="product-count">
          <span>
            <?php 
            echo number_format($total_products); 
            echo ' products found'; 
            ?>
          </span>
        </div>
        <?php endif; ?>
      </div>

    <!-- Filter and sort section -->
    <div class="container">
      <div class="filter-container">
        <form action="" method="get" class="filter-form">
          <!-- If it's a search, we need to preserve the search_query parameter -->
          <?php if ($is_search): ?>
            <input type="hidden" name="search_query" value="<?php echo htmlspecialchars($search_query); ?>">
            
            <!-- Product Type Filter - Only show for search results -->
            <div class="filter-group">
              <label for="filter_type">Product Type:</label>
              <select name="filter_type" id="filter_type" class="filter-select">
                <option value="all" <?php echo (isset($filter_type) && $filter_type === 'all') || !isset($filter_type) ? 'selected' : ''; ?>>All Product Types</option>
                <option value="Laptops" <?php echo isset($filter_type) && $filter_type === 'Laptops' ? 'selected' : ''; ?>>Laptops</option>
                <option value="Desktops" <?php echo isset($filter_type) && $filter_type === 'Desktops' ? 'selected' : ''; ?>>Desktops</option>
                <option value="Custom Builds" <?php echo isset($filter_type) && $filter_type === 'Custom Builds' ? 'selected' : ''; ?>>Custom Builds</option>
                <option value="Display Screens" <?php echo isset($filter_type) && $filter_type === 'Display Screens' ? 'selected' : ''; ?>>Display Screens</option>
                <option value="Graphics Cards" <?php echo isset($filter_type) && $filter_type === 'Graphics Cards' ? 'selected' : ''; ?>>Graphics Cards</option>
                <option value="Processors" <?php echo isset($filter_type) && $filter_type === 'Processors' ? 'selected' : ''; ?>>Processors</option>
                <option value="Keyboards" <?php echo isset($filter_type) && $filter_type === 'Keyboards' ? 'selected' : ''; ?>>Keyboards</option>
              </select>
            </div>
          <?php else: ?>
            <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
          <?php endif; ?>
          
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
            <?php if ($is_search): ?>
                <a href="category.php?search_query=<?php echo urlencode($search_query); ?>" class="btn-reset-filter">Reset Filters</a>
            <?php else: ?>
                <a href="category.php?category=<?php echo urlencode($category); ?>" class="btn-reset-filter">Reset Filters</a>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>

    <!-- Products grid -->
    <div class="container">
      <div class="product-main">
        <!-- Page information text -->
        <?php if ($total_products > count($filteredProducts)): ?>
        <div class="page-info-container">
          <div class="page-info">
            Showing page <span class="page-number"><?php echo $page; ?></span> of <span class="page-number"><?php echo $total_pages; ?></span>
          </div>
          
          <!-- Debug information for admin - uncomment when debugging -->
          <?php if(isset($_GET['debug']) && $_GET['debug'] == 'true'): ?>
          <div style='text-align:left; margin-top:15px; padding:10px; background:#f5f5f5; border-radius:5px;'>
            <strong>Debug Info:</strong><br>
            Search Term: '<?php echo htmlspecialchars($search_query); ?>'<br>
            Filter Type: <?php echo htmlspecialchars($filter_type); ?><br>
            Filter Category: <?php echo htmlspecialchars($filter_category); ?><br>
            Price Range: $<?php echo $min_price; ?> - $<?php echo $max_price; ?><br>
            Sort By: <?php echo htmlspecialchars($sort_by); ?><br>
            
            <?php if ($is_search && isset($search_results['debug_counts'])): ?>
            <strong>Product Type Counts:</strong><br>
            <?php foreach ($search_results['debug_counts'] as $type => $count): ?>
            <?php echo $type; ?>: <?php echo $count; ?> products<br>
            <?php endforeach; ?>
            <?php endif; ?>
          </div>
          <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <div class="product-grid">
            <?php
          if (isset($filteredProducts) && count($filteredProducts) > 0) {
            foreach ($filteredProducts as $product) {
              if ($is_search) {
                // Handle search result products
                $product_type = $product['product_type'];
                $section_id = strtolower(str_replace(' ', '-', $product_type));
              ?>
                <a href="product.php?id=<?php echo $product['id']; ?>&type=<?php echo urlencode($product_type); ?>" class="product-link <?php echo $section_id; ?>-card">
                  <div class="showcase">
                    <div class="showcase-banner">
                      <img src="<?php echo htmlspecialchars($product['image_path']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-img" />
                    </div>
                    <div class="showcase-content">
                      <div class="showcase-category"><?php echo htmlspecialchars($product['category']); ?></div>
                      <h4 class="showcase-title"><?php echo htmlspecialchars($product['name']); ?></h4>
                      <div class="showcase-small-desc"><?php echo htmlspecialchars($product['small_description']); ?></div>
                      <div class="showcase-rating">
                        <ion-icon name="star"></ion-icon>
                        <ion-icon name="star"></ion-icon>
                        <ion-icon name="star"></ion-icon>
                        <ion-icon name="star"></ion-icon>
                        <ion-icon name="star"></ion-icon>
                      </div>
                      <div class="price-box">
                        <p class="price">Price: $<?php echo htmlspecialchars($product['price']); ?></p>
                      </div>
                    </div>
                  </div>
                </a>
              <?php
              } else {
                // Original category page display
                $section_id = strtolower(str_replace(' ', '-', $category));
                
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
            }
          } else {
            echo '<div class="no-products-message">';
            
            if ($is_search) {
              echo '<p>No products found with names matching your search query.</p>
                    <p class="tip">Try a different product name or <a href="index.php">browse all products</a></p>';
            } else {
              echo '<p>No products found matching your filters.</p>
                    <p class="tip">Try adjusting your search criteria or <a href="category.php?category=' . urlencode($category) . '">view all ' . $category . '</a></p>';
            }
            
            echo '</div>';
          }
          ?>
        </div>
        
        <!-- Pagination - Moved back below the product grid -->
        <?php if ($total_pages > 1): ?>
        <div class="pagination">
          <ul class="pagination-list">
            <?php if ($page > 1): ?>
              <li>
                <?php if ($is_search): ?>
                  <a href="category.php?search_query=<?php echo urlencode($search_query); ?>&page=<?php echo $page-1; ?>&sort_by=<?php echo urlencode($sort_by); ?>&filter_category=<?php echo urlencode($filter_category); ?>&filter_type=<?php echo urlencode($filter_type); ?>&min_price=<?php echo $min_price; ?>&max_price=<?php echo $max_price; ?>" class="pagination-link">
                    Prev
                  </a>
                <?php else: ?>
                  <a href="category.php?category=<?php echo urlencode($category); ?>&page=<?php echo $page-1; ?>&sort_by=<?php echo urlencode($sort_by); ?>&filter_category=<?php echo urlencode($filter_category); ?>&filter_type=<?php echo urlencode($filter_type); ?>&min_price=<?php echo $min_price; ?>&max_price=<?php echo $max_price; ?>" class="pagination-link">
                    Prev
                  </a>
                <?php endif; ?>
              </li>
            <?php endif; ?>
            
            <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
              <li>
                <?php if ($is_search): ?>
                  <a href="category.php?search_query=<?php echo urlencode($search_query); ?>&page=<?php echo $i; ?>&sort_by=<?php echo urlencode($sort_by); ?>&filter_category=<?php echo urlencode($filter_category); ?>&filter_type=<?php echo urlencode($filter_type); ?>&min_price=<?php echo $min_price; ?>&max_price=<?php echo $max_price; ?>" 
                     class="pagination-link <?php echo ($i == $page) ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                  </a>
                <?php else: ?>
                  <a href="category.php?category=<?php echo urlencode($category); ?>&page=<?php echo $i; ?>&sort_by=<?php echo urlencode($sort_by); ?>&filter_category=<?php echo urlencode($filter_category); ?>&filter_type=<?php echo urlencode($filter_type); ?>&min_price=<?php echo $min_price; ?>&max_price=<?php echo $max_price; ?>" 
                     class="pagination-link <?php echo ($i == $page) ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                  </a>
                <?php endif; ?>
              </li>
            <?php endfor; ?>
            
            <?php if ($page < $total_pages): ?>
              <li>
                <?php if ($is_search): ?>
                  <a href="category.php?search_query=<?php echo urlencode($search_query); ?>&page=<?php echo $page+1; ?>&sort_by=<?php echo urlencode($sort_by); ?>&filter_category=<?php echo urlencode($filter_category); ?>&filter_type=<?php echo urlencode($filter_type); ?>&min_price=<?php echo $min_price; ?>&max_price=<?php echo $max_price; ?>" class="pagination-link">
                    Next
                  </a>
                <?php else: ?>
                  <a href="category.php?category=<?php echo urlencode($category); ?>&page=<?php echo $page+1; ?>&sort_by=<?php echo urlencode($sort_by); ?>&filter_category=<?php echo urlencode($filter_category); ?>&filter_type=<?php echo urlencode($filter_type); ?>&min_price=<?php echo $min_price; ?>&max_price=<?php echo $max_price; ?>" class="pagination-link">
                    Next
                  </a>
                <?php endif; ?>
              </li>
            <?php endif; ?>
          </ul>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</main>

<?php require_once './includes/footer.php'; ?>

<!-- Include ionic icons (which is likely already in the headerNav.php but including here as a safeguard) -->
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

<!-- Include Category Page JavaScript -->
<script src="js/category.js"></script>
