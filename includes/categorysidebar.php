<?php
// get best sellers
$best_sellers = getBestSellers();

// Get categories
$categories = getCategories();
?>

<!-- SIDEBAR -->
<div class="sidebar has-scrollbar" data-mobile-menu>
  <div class="sidebar-category">
    <div class="sidebar-top">
      <h2 class="sidebar-title">Category</h2>

      <button class="sidebar-close-btn" data-mobile-menu-close-btn>
        <ion-icon name="close-outline"></ion-icon>
      </button>
    </div>

    <ul class="sidebar-menu-category-list">
      <!-- Hardware Categories -->
      <li class="sidebar-menu-category">
        <button class="sidebar-accordion-menu" data-accordion-btn>
          <div class="menu-title-flex">
            <img src="./images/icons/laptop.png" alt="Laptops" width="20" height="20" class="menu-title-img" />
            <p class="menu-title">Laptops</p>
          </div>
          <div>
            <ion-icon name="add-outline" class="add-icon"></ion-icon>
            <ion-icon name="remove-outline" class="remove-icon"></ion-icon>
          </div>
        </button>
        <ul class="sidebar-submenu-category-list" data-accordion>
          <li class="sidebar-submenu-category">
            <form class="search-form" method="post" action="./search.php">
              <input type="hidden" name="search" value="Gaming Laptops" />
              <button type="submit" name="submit" class="sidebar-submenu-title">
                <p class="product-name">Gaming Laptops</p>
              </button>
            </form>
          </li>
          <li class="sidebar-submenu-category">
            <form class="search-form" method="post" action="./search.php">
              <input type="hidden" name="search" value="Business Laptops" />
              <button type="submit" name="submit" class="sidebar-submenu-title">
                <p class="product-name">Business Laptops</p>
              </button>
            </form>
          </li>
        </ul>
      </li>

      <li class="sidebar-menu-category">
        <button class="sidebar-accordion-menu" data-accordion-btn>
          <div class="menu-title-flex">
            <img src="./images/icons/desktop.png" alt="Desktops" width="20" height="20" class="menu-title-img" />
            <p class="menu-title">Desktops</p>
          </div>
          <div>
            <ion-icon name="add-outline" class="add-icon"></ion-icon>
            <ion-icon name="remove-outline" class="remove-icon"></ion-icon>
          </div>
        </button>
        <ul class="sidebar-submenu-category-list" data-accordion>
          <li class="sidebar-submenu-category">
            <form class="search-form" method="post" action="./search.php">
              <input type="hidden" name="search" value="Gaming Desktops" />
              <button type="submit" name="submit" class="sidebar-submenu-title">
                <p class="product-name">Gaming Desktops</p>
              </button>
            </form>
          </li>
          <li class="sidebar-submenu-category">
            <form class="search-form" method="post" action="./search.php">
              <input type="hidden" name="search" value="Workstations" />
              <button type="submit" name="submit" class="sidebar-submenu-title">
                <p class="product-name">Workstations</p>
              </button>
            </form>
          </li>
        </ul>
      </li>

      <li class="sidebar-menu-category">
        <button class="sidebar-accordion-menu" data-accordion-btn>
          <div class="menu-title-flex">
            <img src="./images/icons/custom.png" alt="Custom Builds" width="20" height="20" class="menu-title-img" />
            <p class="menu-title">Custom Builds</p>
          </div>
          <div>
            <ion-icon name="add-outline" class="add-icon"></ion-icon>
            <ion-icon name="remove-outline" class="remove-icon"></ion-icon>
          </div>
        </button>
        <ul class="sidebar-submenu-category-list" data-accordion>
          <li class="sidebar-submenu-category">
            <form class="search-form" method="post" action="./search.php">
              <input type="hidden" name="search" value="Gaming" />
              <button type="submit" name="submit" class="sidebar-submenu-title">
                <p class="product-name">Gaming</p>
              </button>
            </form>
          </li>
          <li class="sidebar-submenu-category">
            <form class="search-form" method="post" action="./search.php">
              <input type="hidden" name="search" value="Content Creation" />
              <button type="submit" name="submit" class="sidebar-submenu-title">
                <p class="product-name">Content Creation</p>
              </button>
            </form>
          </li>
        </ul>
      </li>

      <li class="sidebar-menu-category">
        <button class="sidebar-accordion-menu" data-accordion-btn>
          <div class="menu-title-flex">
            <img src="./images/icons/display.png" alt="Displays" width="20" height="20" class="menu-title-img" />
            <p class="menu-title">Displays</p>
          </div>
          <div>
            <ion-icon name="add-outline" class="add-icon"></ion-icon>
            <ion-icon name="remove-outline" class="remove-icon"></ion-icon>
          </div>
        </button>
        <ul class="sidebar-submenu-category-list" data-accordion>
          <li class="sidebar-submenu-category">
            <form class="search-form" method="post" action="./search.php">
              <input type="hidden" name="search" value="Gaming Monitors" />
              <button type="submit" name="submit" class="sidebar-submenu-title">
                <p class="product-name">Gaming Monitors</p>
              </button>
            </form>
          </li>
          <li class="sidebar-submenu-category">
            <form class="search-form" method="post" action="./search.php">
              <input type="hidden" name="search" value="Professional Displays" />
              <button type="submit" name="submit" class="sidebar-submenu-title">
                <p class="product-name">Professional Displays</p>
              </button>
            </form>
          </li>
        </ul>
      </li>
    </ul>
  </div>
  <!-- Best Sellers -->
  <div class="product-showcase">
    <h3 class="showcase-heading">best sellers</h3>

    <div class="showcase-wrapper">
      <div class="showcase-container">
        <!-- display data form best seller table -->
        <?php
        while ($row = mysqli_fetch_assoc($best_sellers)) {

        ?>

          <div class="showcase">
            <!-- sending two variables in url -->
            <a href="./viewdetail.php?id=<?php
                                          echo $row['product_id'];
                                          ?>&category=<?php
                                echo $row['category_id'];
                                ?>" class="showcase-img-box">
              <img src="./admin/upload/<?php
                                                  echo $row['product_img']
                                                  ?>" alt="best sellers img" width="75" height="75" class="showcase-img" />
            </a>

            <div class="showcase-content">
              <!-- sending two variables in url -->
              <a href="./viewdetail.php?id=<?php
                                            echo $row['product_id'];
                                            ?>&category=<?php
                                echo $row['category_id'];
                                ?>">
                <h4 class="showcase-title">
                  <?php echo $row['product_title'] ?>
                </h4>
              </a>

              <div class="showcase-rating">
                <ion-icon name="star"></ion-icon>
                <ion-icon name="star"></ion-icon>
                <ion-icon name="star"></ion-icon>
                <ion-icon name="star"></ion-icon>
                <ion-icon name="star"></ion-icon>
              </div>

              <div class="price-box">
                <del>$<?php
                      echo $row['product_price'] ?></del>
                <p class="price">$<?php
                                  echo $row['product_price']
                                  ?></p>
              </div>
            </div>
          </div>

        <?php

        }
        ?>

      </div>
    </div>
  </div>
</div>