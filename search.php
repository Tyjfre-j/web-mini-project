<?php
  include_once('./includes/headerNav.php');
  include "includes/config.php";

?>
<div class="overlay" data-overlay></div>
<!--
    - HEADER
  -->

<header>
  <!-- desktop navigation -->
  <?php require_once './includes/desktopnav.php' ?>
  
  <!-- mobile nav in php -->
  <?php require_once './includes/mobilenav.php'; ?>

</header>

<!--
    - MAIN
  -->

<main>

  <div class="product-container">
    <div class="container">
      <!--
          - SIDEBAR
        -->
      <!-- CATEGORY SIDE BAR MOBILE MENU -->
      <?php require_once './includes/categorysidebar.php'; ?>
      <!-- ############################# -->

      <div class="product-box">
        <!-- get id and url for each category and display its dat from table her in this secton -->
        <div class="product-main">

          <h2 class="title">
            Search Results for: 
            <?php 
              // Get search term
              if(isset($_POST['search'])) {
                echo htmlspecialchars($_POST['search']);
                $search_term = $_POST['search'];
              } elseif(isset($_GET['search'])) {
                echo htmlspecialchars($_GET['search']);
                $search_term = $_GET['search'];
              } else {
                $search_term = "";
                echo "All Products";
              }
            ?> 
          </h2>

          <div class="product-grid">
            <?php
            if(!empty($search_term)) {
              // Pagination setup
              if (isset($_GET['page'])) {
                $page = $_GET['page'];
              } else {
                $page = 1;
              }
              
              $limit = 8; // Items per page
              $offset = ($page - 1) * $limit;
              
              // Sanitize search term to prevent SQL injection
              $search_term = mysqli_real_escape_string($conn, $search_term);
              
              // Create search pattern for LIKE query
              $search_pattern = "%{$search_term}%";
              
              // UNION query to search across all product tables using correct table and column names
              $search_query = "
              (SELECT 
                'Laptops' as type,
                Laptops_id as id,
                Laptops_name as name,
                Laptops_image_path as img_path,
                Laptops_price as price,
                category.category_name as category,
                Laptops_small_description as description
              FROM 
                `Laptops`
              JOIN 
                category ON Laptops.Laptops_category_id = category.category_id
              WHERE 
                Laptops_name LIKE '{$search_pattern}'
                OR Laptops_small_description LIKE '{$search_pattern}'
                OR Laptops_long_description LIKE '{$search_pattern}'
              )
              
              UNION
              
              (SELECT 
                'Desktops' as type,
                Desktops_id as id,
                Desktops_name as name,
                Desktops_image_path as img_path,
                Desktops_price as price,
                category.category_name as category,
                Desktops_small_description as description
              FROM 
                `Desktops`
              JOIN 
                category ON Desktops.Desktops_category_id = category.category_id
              WHERE 
                Desktops_name LIKE '{$search_pattern}'
                OR Desktops_small_description LIKE '{$search_pattern}'
                OR Desktops_long_description LIKE '{$search_pattern}'
              )
                
              UNION
              
              (SELECT 
                'Custom Builds' as type,
                `Custom Builds_id` as id,
                `Custom Builds_name` as name,
                `Custom Builds_image_path` as img_path,
                `Custom Builds_price` as price,
                category.category_name as category,
                `Custom Builds_small_description` as description
              FROM 
                `Custom Builds`
              JOIN 
                category ON `Custom Builds`.`Custom Builds_category_id` = category.category_id
              WHERE 
                `Custom Builds_name` LIKE '{$search_pattern}'
                OR `Custom Builds_small_description` LIKE '{$search_pattern}'
                OR `Custom Builds_long_description` LIKE '{$search_pattern}'
              )
                
              UNION
              
              (SELECT 
                'Display Screens' as type,
                `Display Screens_id` as id,
                `Display Screens_name` as name,
                `Display Screens_image_path` as img_path,
                `Display Screens_price` as price,
                category.category_name as category,
                `Display Screens_small_description` as description
              FROM 
                `Display Screens`
              JOIN 
                category ON `Display Screens`.`Display Screens_category_id` = category.category_id
              WHERE 
                `Display Screens_name` LIKE '{$search_pattern}'
                OR `Display Screens_small_description` LIKE '{$search_pattern}'
                OR `Display Screens_long_description` LIKE '{$search_pattern}'
              )
              
              UNION
              
              (SELECT 
                'Graphics Cards' as type,
                `Graphics Cards_id` as id,
                `Graphics Cards_name` as name,
                `Graphics Cards_image_path` as img_path,
                `Graphics Cards_price` as price,
                category.category_name as category,
                `Graphics Cards_small_description` as description
              FROM 
                `Graphics Cards`
              JOIN 
                category ON `Graphics Cards`.`Graphics Cards_category_id` = category.category_id
              WHERE 
                `Graphics Cards_name` LIKE '{$search_pattern}'
                OR `Graphics Cards_small_description` LIKE '{$search_pattern}'
                OR `Graphics Cards_long_description` LIKE '{$search_pattern}'
              )
              
              UNION
              
              (SELECT 
                'Processors' as type,
                `Processors_id` as id,
                `Processors_name` as name,
                `Processors_image_path` as img_path,
                `Processors_price` as price,
                category.category_name as category,
                `Processors_small_description` as description
              FROM 
                `Processors`
              JOIN 
                category ON `Processors`.`Processors_category_id` = category.category_id
              WHERE 
                `Processors_name` LIKE '{$search_pattern}'
                OR `Processors_small_description` LIKE '{$search_pattern}'
                OR `Processors_long_description` LIKE '{$search_pattern}'
              )
              
              UNION
              
              (SELECT 
                'Keyboards' as type,
                `Keyboards_id` as id,
                `Keyboards_name` as name,
                `Keyboards_image_path` as img_path,
                `Keyboards_price` as price,
                category.category_name as category,
                `Keyboards_small_description` as description
              FROM 
                `Keyboards`
              JOIN 
                category ON `Keyboards`.`Keyboards_category_id` = category.category_id
              WHERE 
                `Keyboards_name` LIKE '{$search_pattern}'
                OR `Keyboards_small_description` LIKE '{$search_pattern}'
                OR `Keyboards_long_description` LIKE '{$search_pattern}'
              )
                
              LIMIT {$offset}, {$limit}";
              
              $search_result = mysqli_query($conn, $search_query);
              
              // Count total results for pagination
              $count_query = "
              SELECT COUNT(*) as total FROM (
                (SELECT Laptops_id FROM `Laptops` WHERE Laptops_name LIKE '{$search_pattern}' OR Laptops_small_description LIKE '{$search_pattern}' OR Laptops_long_description LIKE '{$search_pattern}')
                UNION
                (SELECT Desktops_id FROM `Desktops` WHERE Desktops_name LIKE '{$search_pattern}' OR Desktops_small_description LIKE '{$search_pattern}' OR Desktops_long_description LIKE '{$search_pattern}')
                UNION
                (SELECT `Custom Builds_id` FROM `Custom Builds` WHERE `Custom Builds_name` LIKE '{$search_pattern}' OR `Custom Builds_small_description` LIKE '{$search_pattern}' OR `Custom Builds_long_description` LIKE '{$search_pattern}')
                UNION
                (SELECT `Display Screens_id` FROM `Display Screens` WHERE `Display Screens_name` LIKE '{$search_pattern}' OR `Display Screens_small_description` LIKE '{$search_pattern}' OR `Display Screens_long_description` LIKE '{$search_pattern}')
                UNION
                (SELECT `Graphics Cards_id` FROM `Graphics Cards` WHERE `Graphics Cards_name` LIKE '{$search_pattern}' OR `Graphics Cards_small_description` LIKE '{$search_pattern}' OR `Graphics Cards_long_description` LIKE '{$search_pattern}')
                UNION
                (SELECT `Processors_id` FROM `Processors` WHERE `Processors_name` LIKE '{$search_pattern}' OR `Processors_small_description` LIKE '{$search_pattern}' OR `Processors_long_description` LIKE '{$search_pattern}')
                UNION
                (SELECT `Keyboards_id` FROM `Keyboards` WHERE `Keyboards_name` LIKE '{$search_pattern}' OR `Keyboards_small_description` LIKE '{$search_pattern}' OR `Keyboards_long_description` LIKE '{$search_pattern}')
              ) as results";
              
              $count_result = mysqli_query($conn, $count_query);
              $total_row = mysqli_fetch_assoc($count_result);
              $total_products = $total_row['total'];
              
              if($search_result && mysqli_num_rows($search_result) > 0) {
                while($row = mysqli_fetch_assoc($search_result)) {
                  // Get the correct product card class based on type
                  $section_id = strtolower(str_replace(' ', '-', $row['type']));
                  ?>
                  
                  <a href="product.php?id=<?php echo $row['id']; ?>&type=<?php echo urlencode($row['type']); ?>" class="product-link <?php echo $section_id; ?>-card">
                    <div class="showcase">
                      <div class="showcase-banner">
                        <img src="<?php echo htmlspecialchars($row['img_path']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="product-img" />
                      </div>
                      <div class="showcase-content">
                        <div class="showcase-category"><?php echo htmlspecialchars($row['category']); ?></div>
                        <h4 class="showcase-title"><?php echo htmlspecialchars($row['name']); ?></h4>
                        <div class="showcase-small-desc"><?php echo htmlspecialchars($row['description']); ?></div>
                        <div class="showcase-rating">
                          <ion-icon name="star"></ion-icon>
                          <ion-icon name="star"></ion-icon>
                          <ion-icon name="star"></ion-icon>
                          <ion-icon name="star"></ion-icon>
                          <ion-icon name="star"></ion-icon>
                        </div>
                        <div class="price-box">
                          <p class="price">Price: $<?php echo htmlspecialchars($row['price']); ?></p>
                        </div>
                      </div>
                    </div>
                  </a>
                  
                <?php
                }
              } else {
                ?>
                <div class="no-products-message">
                  <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line>
                  </svg>
                  <p>No products found matching '<?php echo htmlspecialchars($search_term); ?>'</p>
                  <p class="tip">Try using different keywords or check our <a href="index.php">product categories</a></p>
                </div>
                <?php
              }
            } else {
              // If no search term, show recent products or featured products
              ?>
              <div class="no-products-message">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <circle cx="11" cy="11" r="8"></circle>
                  <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <p>Enter a search term in the search box above</p>
                <p class="tip">Try searching for products by name, description, or category</p>
              </div>
              <?php
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Pagination -->
  <?php if(!empty($search_term) && isset($total_products) && $total_products > 0): ?>
  <div class="pagination-container">
    <div class="pagination">
      <?php
      $total_pages = ceil($total_products / $limit);
      
      // Previous page link
      if($page > 1) {
        echo "<a href='search.php?search=" . urlencode($search_term) . "&page=" . ($page - 1) . "' class='page-nav'>&laquo; Previous</a>";
      }
      
      // Page numbers
      $start_page = max(1, $page - 2);
      $end_page = min($total_pages, $page + 2);
      
      if($start_page > 1) {
        echo "<a href='search.php?search=" . urlencode($search_term) . "&page=1'>1</a>";
        if($start_page > 2) {
          echo "<span class='dots'>...</span>";
        }
      }
      
      for($i = $start_page; $i <= $end_page; $i++) {
        $active = ($page == $i) ? "active" : "";
        echo "<a href='search.php?search=" . urlencode($search_term) . "&page={$i}' class='{$active}'>{$i}</a>";
      }
      
      if($end_page < $total_pages) {
        if($end_page < $total_pages - 1) {
          echo "<span class='dots'>...</span>";
        }
        echo "<a href='search.php?search=" . urlencode($search_term) . "&page={$total_pages}'>{$total_pages}</a>";
      }
      
      // Next page link
      if($page < $total_pages) {
        echo "<a href='search.php?search=" . urlencode($search_term) . "&page=" . ($page + 1) . "' class='page-nav'>Next &raquo;</a>";
      }
      ?>
    </div>
  </div>
  <?php endif; ?>

</main>

<!-- Add CSS for search results and pagination -->
<style>
  .product-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-top: 20px;
  }
  
  .no-products-message {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    color: var(--davys-gray);
    font-size: 1.1rem;
    background-color: #f9f9f9;
    border-radius: 10px;
    border: 1px dashed #ddd;
  }
  
  .no-products-message svg {
    margin-bottom: 15px;
    color: #ccc;
  }
  
  .no-products-message p {
    margin-bottom: 10px;
  }
  
  .no-products-message .tip {
    font-size: 0.9rem;
    color: var(--sonic-silver);
  }
  
  .no-products-message a {
    color: var(--main-maroon);
    text-decoration: none;
    font-weight: 500;
  }
  
  .no-products-message a:hover {
    text-decoration: underline;
  }
  
  .title {
    font-size: 1.8rem;
    margin-bottom: 20px;
    color: var(--eerie-black);
    border-bottom: 2px solid var(--main-maroon);
    padding-bottom: 10px;
    display: inline-block;
  }
  
  /* Pagination styling */
  .pagination-container {
    margin: 30px 0 50px;
    display: flex;
    justify-content: center;
  }
  
  .pagination {
    display: flex;
    align-items: center;
    gap: 5px;
  }
  
  .pagination a {
    display: inline-block;
    padding: 8px 12px;
    background-color: #f5f5f5;
    color: var(--eerie-black);
    border-radius: 4px;
    text-decoration: none;
    transition: all 0.3s ease;
  }
  
  .pagination a.active {
    background-color: var(--main-maroon);
    color: white;
  }
  
  .pagination a:hover:not(.active) {
    background-color: #e0e0e0;
  }
  
  .pagination .page-nav {
    background-color: white;
    border: 1px solid #ddd;
  }
  
  .pagination .dots {
    padding: 0 5px;
  }
  
  @media (max-width: 1024px) {
    .product-grid {
      grid-template-columns: repeat(3, 1fr);
    }
  }
  
  @media (max-width: 768px) {
    .product-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }
  
  @media (max-width: 480px) {
    .product-grid {
      grid-template-columns: 1fr;
    }
    
    .pagination {
      flex-wrap: wrap;
      justify-content: center;
    }
  }
</style>

<?php require_once './includes/footer.php'; ?>
