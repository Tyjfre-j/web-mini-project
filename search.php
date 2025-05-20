<?php
  // Redirect old search.php requests to the new category.php search functionality
  
  // Get search query from POST or GET
  $search_query = '';
  if (isset($_POST['search_query'])) {
    $search_query = $_POST['search_query'];
  } elseif (isset($_GET['search_query'])) {
    $search_query = $_GET['search_query'];
  } elseif (isset($_POST['search'])) {
    // Handle old form submissions
    $search_query = $_POST['search'];
  } elseif (isset($_GET['search'])) {
    // Handle old URL parameters
    $search_query = $_GET['search'];
  }
  
  // Redirect to category.php with the search query
  if (!empty($search_query)) {
    header("Location: category.php?search_query=" . urlencode($search_query));
  } else {
    // If no search query, redirect to home page
    header("Location: index.php");
  }
  exit();
?>
