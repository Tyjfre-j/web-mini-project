<?php
    require_once './includes/config.php';

    // get product types
    function getProductTypes(){
        global $conn;
        $query = "SELECT * FROM product_types WHERE product_types.type_status = true ORDER BY product_types.type_display_order ASC";
        return $result = mysqli_query($conn, $query);
    }

    // get banner products and details
    function getBanners(){
        global $conn;
        $query = "SELECT * FROM banner WHERE banner.banner_status = true";

        return $result = mysqli_query($conn, $query);
    }

    // get categories 
    function getCategories(){
        global $conn;
        $query = "SELECT * FROM category WHERE category.category_status = true";

        return $result = mysqli_query($conn, $query);
    }

    // get settings
    function getSettings(){
        global $conn;
        $query = "SELECT * FROM settings";
        return $result = mysqli_query($conn, $query);
    }

    // get laptops with category name
    function getLaptops(){
        global $conn;
        $query = "SELECT `Laptops`.*, category.category_name AS Laptops_category_name FROM `Laptops` JOIN category ON `Laptops`.`Laptops_category_id` = category.category_id WHERE `Laptops`.`Laptops_status` = true AND category.category_status = true";
        return mysqli_query($conn, $query);
    }

    // get desktops with category name
    function getDesktops(){
        global $conn;
        $query = "SELECT `Desktops`.*, category.category_name AS Desktops_category_name FROM `Desktops` JOIN category ON `Desktops`.`Desktops_category_id` = category.category_id WHERE `Desktops`.`Desktops_status` = true AND category.category_status = true";
        return mysqli_query($conn, $query);
    }

    // get custom builds with category name
    function getCustomBuilds(){
        global $conn;
        $query = "SELECT `Custom Builds`.*, category.category_name AS `Custom Builds_category_name` FROM `Custom Builds` JOIN category ON `Custom Builds`.`Custom Builds_category_id` = category.category_id WHERE `Custom Builds`.`Custom Builds_status` = true AND category.category_status = true";
        return mysqli_query($conn, $query);
    }

    // get displays with category name
    function getDisplayScreens(){
        global $conn;
        $query = "SELECT `Display Screens`.*, category.category_name AS `Display Screens_category_name` FROM `Display Screens` JOIN category ON `Display Screens`.`Display Screens_category_id` = category.category_id WHERE `Display Screens`.`Display Screens_status` = true AND category.category_status = true";
        return mysqli_query($conn, $query);
    }

    // get gpus with category name
    function getGraphicsCards(){
        global $conn;
        $query = "SELECT `Graphics Cards`.*, category.category_name AS `Graphics Cards_category_name` FROM `Graphics Cards` JOIN category ON `Graphics Cards`.`Graphics Cards_category_id` = category.category_id WHERE `Graphics Cards`.`Graphics Cards_status` = true AND category.category_status = true";
        return mysqli_query($conn, $query);
    }

    // get cpus with category name
    function getProcessors(){
        global $conn;
        $query = "SELECT `Processors`.*, category.category_name AS Processors_category_name FROM `Processors` JOIN category ON `Processors`.`Processors_category_id` = category.category_id WHERE `Processors`.`Processors_status` = true AND category.category_status = true";
        return mysqli_query($conn, $query);
    }

    // get keyboards with category name
    function getKeyboards(){
        global $conn;
        $query = "SELECT `Keyboards`.*, category.category_name AS Keyboards_category_name FROM `Keyboards` JOIN category ON `Keyboards`.`Keyboards_category_id` = category.category_id WHERE `Keyboards`.`Keyboards_status` = true AND category.category_status = true";
        return mysqli_query($conn, $query);
    }
    
    // get items by category
    function getItemsByCategoryItems($category_name) {
        global $conn;
        // This function needs to be implemented based on your database structure
        // For example, it might need to check which table to query based on the category name
        
        // Sample implementation (adapt based on your actual database structure)
        switch($category_name) {
            case 'Laptops':
                return getLaptops();
            case 'Desktops':
                return getDesktops();
            case 'Custom Builds':
                return getCustomBuilds();
            case 'Display Screens':
                return getDisplayScreens();
            case 'Graphics Cards':
                return getGraphicsCards();
            case 'Processors':
                return getProcessors();
            case 'Keyboards':
                return getKeyboards();
            default:
                // Default query if category not found
                return false;
        }
    }
    
    // Keep old function for backward compatibility
    function get_items_by_category_items($category_name) {
        return getItemsByCategoryItems($category_name);
    }
    
    // Best sellers function (placeholder to be implemented)
    function get_best_sellers() {
        global $conn;
        // Implement based on your database structure
        // Placeholder implementation
        $query = "SELECT * FROM Laptops LIMIT 3";  // Adjust this based on your actual database
        return mysqli_query($conn, $query);
    }
    
    // Keep old best sellers function alias for backward compatibility
    function getBestSellers() {
        return get_best_sellers();
    }
    
    // Get product by ID from appropriate table based on category
    function get_product($product_id) {
        global $conn;
        
        // Check which category is passed in the URL
        if(isset($_GET['category'])) {
            $category = $_GET['category'];
            
            switch($category) {
                case 'Laptops':
                    $query = "SELECT *, Laptops_name as product_title, Laptops_price as product_price, 
                             Laptops_price as discounted_price, Laptops_id as product_id, 
                             Laptops_long_description as product_desc, 
                             Laptops_image_path as product_img 
                             FROM Laptops WHERE Laptops_id = $product_id";
                    break;
                case 'Desktops':
                    $query = "SELECT *, Desktops_name as product_title, Desktops_price as product_price, 
                             Desktops_price as discounted_price, Desktops_id as product_id, 
                             Desktops_long_description as product_desc, 
                             Desktops_image_path as product_img 
                             FROM Desktops WHERE Desktops_id = $product_id";
                    break;
                case 'Custom Builds':
                    $query = "SELECT *, `Custom Builds_name` as product_title, `Custom Builds_price` as product_price, 
                             `Custom Builds_price` as discounted_price, `Custom Builds_id` as product_id, 
                             `Custom Builds_long_description` as product_desc, 
                             `Custom Builds_image_path` as product_img 
                             FROM `Custom Builds` WHERE `Custom Builds_id` = $product_id";
                    break;
                case 'Display Screens':
                    $query = "SELECT *, `Display Screens_name` as product_title, `Display Screens_price` as product_price, 
                             `Display Screens_price` as discounted_price, `Display Screens_id` as product_id, 
                             `Display Screens_long_description` as product_desc, 
                             `Display Screens_image_path` as product_img 
                             FROM `Display Screens` WHERE `Display Screens_id` = $product_id";
                    break;
                case 'Graphics Cards':
                    $query = "SELECT *, `Graphics Cards_name` as product_title, `Graphics Cards_price` as product_price, 
                             `Graphics Cards_price` as discounted_price, `Graphics Cards_id` as product_id, 
                             `Graphics Cards_long_description` as product_desc, 
                             `Graphics Cards_image_path` as product_img 
                             FROM `Graphics Cards` WHERE `Graphics Cards_id` = $product_id";
                    break;
                case 'Processors':
                    $query = "SELECT *, Processors_name as product_title, Processors_price as product_price, 
                             Processors_price as discounted_price, Processors_id as product_id, 
                             Processors_long_description as product_desc, 
                             Processors_image_path as product_img 
                             FROM Processors WHERE Processors_id = $product_id";
                    break;
                case 'Keyboards':
                    $query = "SELECT *, Keyboards_name as product_title, Keyboards_price as product_price, 
                             Keyboards_price as discounted_price, Keyboards_id as product_id, 
                             Keyboards_long_description as product_desc, 
                             Keyboards_image_path as product_img 
                             FROM Keyboards WHERE Keyboards_id = $product_id";
                    break;
                default:
                    return false;
            }
            
            return mysqli_query($conn, $query);
        }
        
        return false;
    }
    
    ?>