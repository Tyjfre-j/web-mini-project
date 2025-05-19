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
    
    ?>