<?php
  include_once('./includes/headerNav.php');
  include "includes/config.php";

?>
<div class="overlay" data-overlay></div>
<!--
    - HEADER
  -->

<header>
  <!-- top head action, search etc in php -->
  <?php require_once './includes/topheadactions.php'; ?>

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
              
              // UNION query to search across all product tables
              $search_query = "
              (SELECT 
                'laptop' as type,
                id,
                laptop_name as name,
                laptop_img as img_path,
                laptop_price as price,
                laptop_category_name as category,
                laptop_small_description as description
              FROM 
                laptop
              WHERE 
                laptop_name LIKE '{$search_pattern}')
              
              UNION
              
              (SELECT 
                'desktop' as type,
                id,
                desktop_name as name,
                table_img as img_path,
                desktop_price as price,
                desktop_category_name as category,
                desktop_small_description as description
              FROM 
                desktop
              WHERE 
                desktop_name LIKE '{$search_pattern}')
                
              UNION
              
              (SELECT 
                'customebuild' as type,
                id,
                customeBuild_name as name,
                table_img as img_path,
                customeBuild_price as price,
                customeBuild_category_name as category,
                customeBuild_small_description as description
              FROM 
                customeBuild
              WHERE 
                customeBuild_name LIKE '{$search_pattern}')
                
              UNION
              
              (SELECT 
                'display' as type,
                id,
                display_name as name,
                table_img as img_path,
                display_price as price,
                display_category_name as category,
                display_small_description as description
              FROM 
                display
              WHERE 
                display_name LIKE '{$search_pattern}')
                
              LIMIT {$offset}, {$limit}";
              
              $search_result = mysqli_query($conn, $search_query);
              
              // Count total results for pagination
              $count_query = "
              SELECT COUNT(*) as total FROM (
                (SELECT id FROM laptop WHERE laptop_name LIKE '{$search_pattern}')
                UNION
                (SELECT id FROM desktop WHERE desktop_name LIKE '{$search_pattern}')
                UNION
                (SELECT id FROM customeBuild WHERE customeBuild_name LIKE '{$search_pattern}')
                UNION
                (SELECT id FROM display WHERE display_name LIKE '{$search_pattern}')
              ) as results";
              
              $count_result = mysqli_query($conn, $count_query);
              $total_row = mysqli_fetch_assoc($count_result);
              $total_products = $total_row['total'];
              
              if($search_result && mysqli_num_rows($search_result) > 0) {
                while($row = mysqli_fetch_assoc($search_result)) {
                  // Determine correct image path format based on product type
                  $img_src = $row['type'] === 'laptop' ? $row['img_path'] : $row['img_path'];
                  ?>
                  
                  <a href="product.php?id=<?php echo $row['id']; ?>&type=<?php echo $row['type']; ?>" class="product-link">
                    <div class="showcase">
                      <div class="showcase-banner">
                        <img src="<?php echo htmlspecialchars($img_src); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="product-img" />
                      </div>
                      <div class="showcase-content">
                        <div class="showcase-category"><?php echo htmlspecialchars($row['category']); ?></div>
                        <h3 class="showcase-title"><?php echo htmlspecialchars($row['name']); ?></h3>
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
                echo "<h4 style='color:#0D8A91; text-align:center; padding:20px;'>No products found matching '" . htmlspecialchars($search_term) . "'</h4>";
              }
            } else {
              // If no search term, show recent products from all categories
              ?>
              <h4 style="color:#0D8A91; text-align:center; padding:10px;">Enter a search term to find products</h4>
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
  <div class="pag-cont-search">
    <div class="pagination">
      <?php
      $total_pages = ceil($total_products / $limit);
      
      for($i = 1; $i <= $total_pages; $i++) {
        $active = ($page == $i) ? "active" : "";
        echo "<a href='search.php?search=" . urlencode($search_term) . "&page={$i}' class='{$active}'>{$i}</a>";
      }
      ?>
    </div>
  </div>
  <?php endif; ?>

</main>

<!-- Add some CSS to style the search results -->
<style>
  .product-link {
    text-decoration: none;
    color: inherit;
    display: block;
  }
  
  .showcase {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    width: 100%; 
    max-width: none;
    margin: 0 auto;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
    transition: all 0.25s ease;
    border: 2px solid transparent;
    transform-origin: center;
  }

  .product-link:hover .showcase {
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
    transform: translateY(-8px) scale(1.03);
    border-color: var(--main-maroon);
    background-color: #fafcff;
  }

  .product-link:hover .showcase-title {
    color: var(--main-maroon);
    transform: scale(1.05);
    transition: all 0.25s ease;
  }

  .pagination {
    display: flex;
    justify-content: center;
    margin: 20px 0 40px 0;
  }

  .pagination a {
    color: var(--eerie-black);
    padding: 8px 16px;
    text-decoration: none;
    border: 1px solid #ddd;
    margin: 0 4px;
    transition: background-color 0.3s;
    border-radius: 4px;
  }

  .pagination a.active {
    background-color: var(--main-maroon);
    color: white;
    border: 1px solid var(--main-maroon);
  }

  .pagination a:hover:not(.active) {
    background-color: #ddd;
  }
</style>

<?php require_once './includes/footer.php'; ?>
