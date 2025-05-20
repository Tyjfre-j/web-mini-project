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
    
    // Search across all product types
    function searchProducts($search_term, $sort_by = 'default', $min_price = 0, $max_price = 10000, $page = 1, $items_per_page = 12, $filter_type = 'all', $filter_category = 'all') {
        global $conn;
        
        // Sanitize search term
        $search_term = mysqli_real_escape_string($conn, $search_term);
        // If search term is just a single character, make sure to find all products containing it
        $search_pattern = "%{$search_term}%";
        
        // Calculate offset for pagination
        $offset = ($page - 1) * $items_per_page;
        
        // Base query for all product types with sorting
        $sort_clause = "";
        switch ($sort_by) {
            case 'price_low':
                $sort_clause = "ORDER BY price ASC";
                break;
            case 'price_high':
                $sort_clause = "ORDER BY price DESC";
                break;
            case 'newest':
                $sort_clause = "ORDER BY created_at DESC";
                break;
            default:
                $sort_clause = ""; // Default sorting
        }
        
        // Build query parts for each product type
        $queries = [];
        $count_queries = [];
        
        // Only include the tables that match the filter_type, or all if filter_type is 'all'
        if ($filter_type == 'all' || $filter_type == 'Laptops') {
            $category_filter = $filter_category != 'all' ? "AND category.category_name = '" . mysqli_real_escape_string($conn, $filter_category) . "'" : "";
            
            $queries[] = "(SELECT 
                'Laptops' as product_type,
                Laptops_id as id,
                Laptops_name as name,
                Laptops_image_path as image_path,
                Laptops_price as price,
                category.category_name as category,
                Laptops_small_description as small_description,
                Laptops_long_description as long_description,
                Laptops_created_at as created_at
            FROM 
                `Laptops`
            JOIN 
                category ON Laptops.Laptops_category_id = category.category_id
            WHERE 
                LOWER(Laptops_name) LIKE LOWER('{$search_pattern}')
                AND Laptops_price BETWEEN {$min_price} AND {$max_price}
                AND Laptops_status = true
                {$category_filter}
            )";
            
            $count_queries[] = "(SELECT Laptops_id FROM `Laptops` 
                JOIN category ON Laptops.Laptops_category_id = category.category_id
                WHERE LOWER(Laptops_name) LIKE LOWER('{$search_pattern}')
                AND Laptops_price BETWEEN {$min_price} AND {$max_price}
                AND Laptops_status = true
                {$category_filter})";
        }
        
        if ($filter_type == 'all' || $filter_type == 'Desktops') {
            $category_filter = $filter_category != 'all' ? "AND category.category_name = '" . mysqli_real_escape_string($conn, $filter_category) . "'" : "";
            
            $queries[] = "(SELECT 
                'Desktops' as product_type,
                Desktops_id as id,
                Desktops_name as name,
                Desktops_image_path as image_path,
                Desktops_price as price,
                category.category_name as category,
                Desktops_small_description as small_description,
                Desktops_long_description as long_description,
                Desktops_created_at as created_at
            FROM 
                `Desktops`
            JOIN 
                category ON Desktops.Desktops_category_id = category.category_id
            WHERE 
                LOWER(Desktops_name) LIKE LOWER('{$search_pattern}')
                AND Desktops_price BETWEEN {$min_price} AND {$max_price}
                AND Desktops_status = true
                {$category_filter}
            )";
            
            $count_queries[] = "(SELECT Desktops_id FROM `Desktops` 
                JOIN category ON Desktops.Desktops_category_id = category.category_id
                WHERE LOWER(Desktops_name) LIKE LOWER('{$search_pattern}')
                AND Desktops_price BETWEEN {$min_price} AND {$max_price}
                AND Desktops_status = true
                {$category_filter})";
        }
        
        if ($filter_type == 'all' || $filter_type == 'Custom Builds') {
            $category_filter = $filter_category != 'all' ? "AND category.category_name = '" . mysqli_real_escape_string($conn, $filter_category) . "'" : "";
            
            $queries[] = "(SELECT 
                'Custom Builds' as product_type,
                `Custom Builds_id` as id,
                `Custom Builds_name` as name,
                `Custom Builds_image_path` as image_path,
                `Custom Builds_price` as price,
                category.category_name as category,
                `Custom Builds_small_description` as small_description,
                `Custom Builds_long_description` as long_description,
                `Custom Builds_created_at` as created_at
            FROM 
                `Custom Builds`
            JOIN 
                category ON `Custom Builds`.`Custom Builds_category_id` = category.category_id
            WHERE 
                LOWER(`Custom Builds_name`) LIKE LOWER('{$search_pattern}')
                AND `Custom Builds_price` BETWEEN {$min_price} AND {$max_price}
                AND `Custom Builds_status` = true
                {$category_filter}
            )";
            
            $count_queries[] = "(SELECT `Custom Builds_id` FROM `Custom Builds` 
                JOIN category ON `Custom Builds`.`Custom Builds_category_id` = category.category_id
                WHERE LOWER(`Custom Builds_name`) LIKE LOWER('{$search_pattern}')
                AND `Custom Builds_price` BETWEEN {$min_price} AND {$max_price}
                AND `Custom Builds_status` = true
                {$category_filter})";
        }
        
        if ($filter_type == 'all' || $filter_type == 'Display Screens') {
            $category_filter = $filter_category != 'all' ? "AND category.category_name = '" . mysqli_real_escape_string($conn, $filter_category) . "'" : "";
            
            $queries[] = "(SELECT 
                'Display Screens' as product_type,
                `Display Screens_id` as id,
                `Display Screens_name` as name,
                `Display Screens_image_path` as image_path,
                `Display Screens_price` as price,
                category.category_name as category,
                `Display Screens_small_description` as small_description,
                `Display Screens_long_description` as long_description,
                `Display Screens_created_at` as created_at
            FROM 
                `Display Screens`
            JOIN 
                category ON `Display Screens`.`Display Screens_category_id` = category.category_id
            WHERE 
                LOWER(`Display Screens_name`) LIKE LOWER('{$search_pattern}')
                AND `Display Screens_price` BETWEEN {$min_price} AND {$max_price}
                AND `Display Screens_status` = true
                {$category_filter}
            )";
            
            $count_queries[] = "(SELECT `Display Screens_id` FROM `Display Screens` 
                JOIN category ON `Display Screens`.`Display Screens_category_id` = category.category_id
                WHERE LOWER(`Display Screens_name`) LIKE LOWER('{$search_pattern}')
                AND `Display Screens_price` BETWEEN {$min_price} AND {$max_price}
                AND `Display Screens_status` = true
                {$category_filter})";
        }
        
        if ($filter_type == 'all' || $filter_type == 'Graphics Cards') {
            $category_filter = $filter_category != 'all' ? "AND category.category_name = '" . mysqli_real_escape_string($conn, $filter_category) . "'" : "";
            
            $queries[] = "(SELECT 
                'Graphics Cards' as product_type,
                `Graphics Cards_id` as id,
                `Graphics Cards_name` as name,
                `Graphics Cards_image_path` as image_path,
                `Graphics Cards_price` as price,
                category.category_name as category,
                `Graphics Cards_small_description` as small_description,
                `Graphics Cards_long_description` as long_description,
                `Graphics Cards_created_at` as created_at
            FROM 
                `Graphics Cards`
            JOIN 
                category ON `Graphics Cards`.`Graphics Cards_category_id` = category.category_id
            WHERE 
                `Graphics Cards_name` LIKE '{$search_pattern}'
                AND `Graphics Cards_price` BETWEEN {$min_price} AND {$max_price}
                AND `Graphics Cards_status` = true
                {$category_filter}
            )";
            
            $count_queries[] = "(SELECT `Graphics Cards_id` FROM `Graphics Cards` 
                JOIN category ON `Graphics Cards`.`Graphics Cards_category_id` = category.category_id
                WHERE `Graphics Cards_name` LIKE '{$search_pattern}'
                AND `Graphics Cards_price` BETWEEN {$min_price} AND {$max_price}
                AND `Graphics Cards_status` = true
                {$category_filter})";
        }
        
        if ($filter_type == 'all' || $filter_type == 'Processors') {
            $category_filter = $filter_category != 'all' ? "AND category.category_name = '" . mysqli_real_escape_string($conn, $filter_category) . "'" : "";
            
            $queries[] = "(SELECT 
                'Processors' as product_type,
                `Processors_id` as id,
                `Processors_name` as name,
                `Processors_image_path` as image_path,
                `Processors_price` as price,
                category.category_name as category,
                `Processors_small_description` as small_description,
                `Processors_long_description` as long_description,
                `Processors_created_at` as created_at
            FROM 
                `Processors`
            JOIN 
                category ON `Processors`.`Processors_category_id` = category.category_id
            WHERE 
                `Processors_name` LIKE '{$search_pattern}'
                AND `Processors_price` BETWEEN {$min_price} AND {$max_price}
                AND `Processors_status` = true
                {$category_filter}
            )";
            
            $count_queries[] = "(SELECT `Processors_id` FROM `Processors` 
                JOIN category ON `Processors`.`Processors_category_id` = category.category_id
                WHERE `Processors_name` LIKE '{$search_pattern}'
                AND `Processors_price` BETWEEN {$min_price} AND {$max_price}
                AND `Processors_status` = true
                {$category_filter})";
        }
        
        if ($filter_type == 'all' || $filter_type == 'Keyboards') {
            $category_filter = $filter_category != 'all' ? "AND category.category_name = '" . mysqli_real_escape_string($conn, $filter_category) . "'" : "";
            
            $queries[] = "(SELECT 
                'Keyboards' as product_type,
                `Keyboards_id` as id,
                `Keyboards_name` as name,
                `Keyboards_image_path` as image_path,
                `Keyboards_price` as price,
                category.category_name as category,
                `Keyboards_small_description` as small_description,
                `Keyboards_long_description` as long_description,
                `Keyboards_created_at` as created_at
            FROM 
                `Keyboards`
            JOIN 
                category ON `Keyboards`.`Keyboards_category_id` = category.category_id
            WHERE 
                `Keyboards_name` LIKE '{$search_pattern}'
                AND `Keyboards_price` BETWEEN {$min_price} AND {$max_price}
                AND `Keyboards_status` = true
                {$category_filter}
            )";
            
            $count_queries[] = "(SELECT `Keyboards_id` FROM `Keyboards` 
                JOIN category ON `Keyboards`.`Keyboards_category_id` = category.category_id
                WHERE `Keyboards_name` LIKE '{$search_pattern}'
                AND `Keyboards_price` BETWEEN {$min_price} AND {$max_price}
                AND `Keyboards_status` = true
                {$category_filter})";
        }
        
        // Combine the queries with UNION
        $search_query = implode("\n\nUNION\n\n", $queries) . "\n\n{$sort_clause}\nLIMIT {$offset}, {$items_per_page}";
        
        $search_result = mysqli_query($conn, $search_query);
        
        // Count total results for pagination - combining all count queries
        $count_query = "SELECT COUNT(*) as total FROM (" . implode("\nUNION ALL\n", $count_queries) . ") as results";
        
        $count_result = mysqli_query($conn, $count_query);
        $total_row = mysqli_fetch_assoc($count_result);
        $total_products = $total_row['total'];
        
        // For debugging: Get counts by product type
        $debug_counts = [];
        foreach (['Laptops', 'Desktops', 'Custom Builds', 'Display Screens', 'Graphics Cards', 'Processors', 'Keyboards'] as $type) {
            if ($filter_type == 'all' || $filter_type == $type) {
                $type_count_query = "SELECT COUNT(*) as type_count FROM (";
                
                // Find the matching count query for this type
                foreach ($count_queries as $query) {
                    if (strpos($query, "`$type") !== false || strpos($query, "$type") !== false) {
                        $type_count_query .= $query;
                        break;
                    }
                }
                
                $type_count_query .= ") as type_results";
                
                $type_count_result = mysqli_query($conn, $type_count_query);
                if ($type_count_result) {
                    $type_count_row = mysqli_fetch_assoc($type_count_result);
                    $debug_counts[$type] = $type_count_row['type_count'];
                } else {
                    $debug_counts[$type] = "Error: " . mysqli_error($conn);
                }
            } else {
                $debug_counts[$type] = "N/A (filtered out)";
            }
        }
        
        // Calculate total pages
        $total_pages = ceil($total_products / $items_per_page);
        
        return [
            'results' => $search_result,
            'total_products' => $total_products,
            'total_pages' => $total_pages,
            'current_page' => $page,
            'debug_counts' => $debug_counts
        ];
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