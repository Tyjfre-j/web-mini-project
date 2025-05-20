<?php
//this all are input validity functions that will provide true/false for error finding 
//in case condition not matched--it executes during signup 

function emptyInputSignup($name, $email,  $number,$address, $pwd,$rpwd){
    $result = false;
    if (empty($name) ||empty($email) ||empty($number) ||empty($address) ||empty($pwd) ||empty($rpwd) ) {
                 $result = true;   
    }
     else{
                 $result = false;
     }
                 return $result;
}

function invalidPhone($number){
    $result = false;
    if (strlen($number) !=10) { 
                 $result = true;   
    }
     else{
                 $result = false;
     }
                 return $result;
}

function invalidEmail($email){
    $result = false;
    if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {//this return true if var is proper email(built in func)
                 $result = true;   
    }
     else{
                 $result = false;
     }
                 return $result;
}


function pwdMatch($pwd,$rpwd) {
    $result=false;
    if ($pwd !== $rpwd) {
                 $result = false;   
    }
     else{
                 $result = true;
     }
                 return $result;
}



function createUser($name,$email,$address,$pwd,$number){
    //making config as we need this everytime we can just use it through include_once
//1st step for database php connection
$serverName = "localhost";
$dBUsername = "root";
$dBPassword = "";
$dBName = "db_ecommerce";

//Before we can access data in the MySQL database, we need to be able to connect to the server i.e php
$conn = new mysqli($serverName,$dBUsername,$dBPassword,$dBName );

// Check connection
if(!$conn){
    die("Connection failed: ".$conn->connect_error());
}

   //using prepare statement for preventing injection
   $sql = $conn->prepare("INSERT INTO customer (customer_fname,customer_email,customer_password,customer_phone,customer_address) VALUES (?,?,?,?,?)");

   $sql->bind_param('sssss',$name,$email,$pwd,$number,$address);
   $sql->execute();
 
   //last step closing connection
   $sql->close(); //closing prepare statement
   $conn->close();
   
   // Get redirect parameter if it exists
   session_start();
   if (isset($_SESSION['redirect_after_signup']) && !empty($_SESSION['redirect_after_signup'])) {
       $redirect = $_SESSION['redirect_after_signup'];
       unset($_SESSION['redirect_after_signup']); // Clear it after use
       
       // Check if it's an absolute URL or just a path
       if (filter_var($redirect, FILTER_VALIDATE_URL)) {
           // For security, extract just the path
           $parsed_url = parse_url($redirect);
           $path = isset($parsed_url['path']) ? $parsed_url['path'] : '';
           
           // Include the query string if present
           if(isset($parsed_url['query']) && !empty($parsed_url['query'])) {
               $path .= '?' . $parsed_url['query'];
           }
           
           header("location: ../login.php?signup=success&redirect=" . urlencode($path));
       } else {
           header("location: ../login.php?signup=success&redirect=" . urlencode($redirect));
       }
   } else {
       header("location: ../login.php?signup=success");
   }
}
    