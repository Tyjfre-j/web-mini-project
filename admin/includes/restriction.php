<!-- <?php 
    
   //this is restriction for normal user to access admin panel
   // Check if session is not already started
   if (session_status() === PHP_SESSION_NONE) {
       session_start();
   }
   if($_SESSION['customer_role']!='admin'){
   header("location:../index.php?AdminRestricted");
  } 

  ?> -->