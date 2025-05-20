<?php
// Check if session is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
   unset($_SESSION['logged-in']);
   header("Location:login.php");
   ?>