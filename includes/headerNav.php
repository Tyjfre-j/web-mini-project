<?php  session_start();
	include_once 'includes/config.php';
	//  all functions
	require_once 'includes/functions.php';

	//run whenever this file is used no need of isset or any condition to get website image footer etc
	$settings = getSettings();
	$row5 = mysqli_fetch_assoc($settings);
	$_SESSION['web-name'] = "PeakGear";
	$_SESSION['web-img'] = "PeakGear.jpg";
	$_SESSION['web-footer'] = $row5['website_footer'];

	// TODO: Also update setting to add info email, whatsapp, address etc to change it dynamically
?>

<!DOCTYPE html>
<html lang="en">

  <head>
  	<meta charset="UTF-8" />
  	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PeakGear offers high-quality computer hardware including laptops, desktops, custom PCs, and components.">
    <meta name="author" content="">
    <meta name="robots" content="index, follow">


	<!-- Favicon -->
	<link rel="shortcut icon" href="./images/flogo/fav.png" type="image/x-icon" />
	
    <title>PeakGear</title>

	


	<!-- <link href="./css/font-awesome.css" rel="stylesheet" type="text/css"> -->
	<!-- font awesome code -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	
	<!--
		- custom css link
	-->
	
	<link rel="stylesheet" type="text/css" href="./css/style-prefix.css?<?php echo time(); ?>" />
	<!-- <link rel="stylesheet" href="./css/style-prefix.css" /> -->
	<link rel="stylesheet" type="text/css" href="./css/product-display.css?<?php echo time(); ?>" />
	<link rel="stylesheet" type="text/css" href="./css/pagination.css?<?php echo time(); ?>" />
	
	<!-- Additional header styles -->
	<style>
		/* Site title animation */
		.site-title {
			background-image: linear-gradient(90deg, var(--main-maroon), var(--deep-maroon), var(--main-maroon));
			background-size: 200% 100%;
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent;
			animation: gradientMove 8s ease infinite;
			margin-left: 15px; /* Move title to the right */
		}
		
		/* Override site title hover effect and underline */
		.site-title:after {
			display: none !important;
		}
		
		.header-logo:hover .site-title {
			transform: none !important;
			color: var(--main-maroon) !important;
		}
		
		/* Move cart and sign-in buttons to the left */
		.header-user-actions.desktop-menu-category-list {
			margin-right: 25px;
		}
		
		@keyframes gradientMove {
			0% { background-position: 0% 50%; }
			50% { background-position: 100% 50%; }
			100% { background-position: 0% 50%; }
		}

		/* Improved header spacing on smaller screens */
		@media (max-width: 768px) {
			.header-main {
				padding: 15px 15px 20px;
			}
			.site-branding {
				margin-bottom: 15px;
			}
		}

		/* Subtle header shadow on scroll */
		header.scroll-active {
			box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
		}
	</style>
	
	<!--
		- google font link
	-->
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />

  </head>

<body>

<!-- added manually to some places like contact and about page and footer -->
<?php
  //  site details
    // $site_name = "HCA E-Commerce";
    $site_address = "Rue Smail BENMOUNAH,EL Biar, Alger";
    $site_contact_num = "+213 555 55 55 55";
    $site_info_email = "schakibhad@.com";
?>