<?php
   session_start();
   
   // Check if user is logged in
   if(isset($_SESSION['id'])) {
      session_unset();
      session_destroy();
      header("location:index.php?message=successfully_logged_out");
      exit;
   } else {
      // If no session exists, redirect to login page
      header("location:login.php");
      exit;
   }
?>