<?php
    require_once './includes/config.php';

    // get banner products and details
    function get_banners(){
        global $conn;
        $query = "SELECT * FROM banner WHERE banner.banner_status = 1";

        return $result = mysqli_query($conn, $query);
    }

    // get categories 
    function get_categories(){
        global $conn;
        $query = "SELECT * FROM category WHERE category.status = 1";

        return $result = mysqli_query($conn, $query);
    }

  
            
      
   

  

    function get_new_products($offset, $limit){
        global $conn;
        // Use UNION to get data from all product tables
        $query = "
        (SELECT 
            'laptop' as type,
            id,
            laptop_name as name,
            laptop_img as img_path,
            laptop_price as price,
            laptop_category_name as category,
            laptop_small_description as description
        FROM 
            laptop)
        
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
            desktop)
            
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
            customeBuild)
            
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
            display)
        
        LIMIT {$offset}, {$limit}";
        
        return $result = mysqli_query($conn, $query);
    }

    // get product through id from product tables 
    function get_product($id, $type = 'laptop'){
        global $conn;
        
        // Based on product type, query the appropriate table
        switch($type) {
            case 'laptop':
                $query = "SELECT *, 'laptop' as type FROM laptop WHERE id = $id";
                break;
            case 'desktop':
                $query = "SELECT *, 'desktop' as type FROM desktop WHERE id = $id";
                break;
            case 'customebuild':
                $query = "SELECT *, 'customebuild' as type FROM customeBuild WHERE id = $id";
                break;
            case 'display':
                $query = "SELECT *, 'display' as type FROM display WHERE id = $id";
                break;
            default:
                $query = "SELECT *, 'laptop' as type FROM laptop WHERE id = $id";
        }
        
        return $result = mysqli_query($conn, $query);
    }

    // get items by category
    function get_items_by_category_items($category){
        global $conn;
        // Use UNION to search across all product tables
        $query = "
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
            laptop_category_name LIKE '%$category%')
        
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
            desktop_category_name LIKE '%$category%')
            
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
            customeBuild_category_name LIKE '%$category%')
            
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
            display_category_name LIKE '%$category%')";
        
        return $result = mysqli_query($conn, $query);
    }
?>