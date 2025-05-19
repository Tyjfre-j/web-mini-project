<?php
    require_once './includes/config.php';

    // get banner products and details
    function get_banners(){
        global $conn;
        $query = "SELECT * FROM banner WHERE banner.banner_status = true";

        return $result = mysqli_query($conn, $query);
    }

    // get categories 
    function get_categories(){
        global $conn;
        $query = "SELECT * FROM category WHERE category.category_status = true";

        return $result = mysqli_query($conn, $query);
    }

    // get settings
    function get_settings(){
        global $conn;
        $query = "SELECT * FROM settings";
        return $result = mysqli_query($conn, $query);
    }

    // get laptops with category name
    function get_laptops(){
        global $conn;
        $query = "SELECT laptop.*, category.category_name AS laptop_category_name FROM laptop JOIN category ON laptop.laptop_category_id = category.category_id WHERE laptop.laptop_status = true AND category.category_status = true";
        return mysqli_query($conn, $query);
    }

    // get desktops with category name
    function get_desktops(){
        global $conn;
        $query = "SELECT desktop.*, category.category_name AS desktop_category_name FROM desktop JOIN category ON desktop.desktop_category_id = category.category_id WHERE desktop.desktop_status = true AND category.category_status = true";
        return mysqli_query($conn, $query);
    }

    // get custom builds with category name
    function get_custombuilds(){
        global $conn;
        $query = "SELECT custombuild.*, category.category_name AS custombuild_category_name FROM custombuild JOIN category ON custombuild.custombuild_category_id = category.category_id WHERE custombuild.custombuild_status = true AND category.category_status = true";
        return mysqli_query($conn, $query);
    }

    // get displays with category name
    function get_displayscreens(){
        global $conn;
        $query = "SELECT displayscreen.*, category.category_name AS displayscreen_category_name FROM displayscreen JOIN category ON displayscreen.displayscreen_category_id = category.category_id WHERE displayscreen.displayscreen_status = true AND category.category_status = true";
        return mysqli_query($conn, $query);
    }

    // get gpus with category name
    function get_gpus(){
        global $conn;
        $query = "SELECT gpu.*, category.category_name AS gpu_category_name FROM gpu JOIN category ON gpu.gpu_category_id = category.category_id WHERE gpu.gpu_status = true AND category.category_status = true";
        return mysqli_query($conn, $query);
    }

    // get cpus with category name
    function get_cpus(){
        global $conn;
        $query = "SELECT cpu.*, category.category_name AS cpu_category_name FROM cpu JOIN category ON cpu.cpu_category_id = category.category_id WHERE cpu.cpu_status = true AND category.category_status = true";
        return mysqli_query($conn, $query);
    }

    // get keyboards with category name
    function get_keyboards(){
        global $conn;
        $query = "SELECT keyboard.*, category.category_name AS keyboard_category_name FROM keyboard JOIN category ON keyboard.keyboard_category_id = category.category_id WHERE keyboard.keyboard_status = true AND category.category_status = true";
        return mysqli_query($conn, $query);
    }
    
    ?>