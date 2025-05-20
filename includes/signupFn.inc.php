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

function emailExists($email) {
    $serverName = "localhost";
    $dBUsername = "root";
    $dBPassword = "";
    $dBName = "site_database";
    
    $conn = new mysqli($serverName, $dBUsername, $dBPassword, $dBName);
    if(!$conn){
        die("Connection failed: ".$conn->connect_error());
    }
    
    $sql = $conn->prepare("SELECT customer_email FROM customer WHERE customer_email = ?");
    $sql->bind_param('s', $email);
    $sql->execute();
    $result = $sql->get_result();
    $rowCount = $result->num_rows;
    
    $sql->close();
    $conn->close();
    
    return $rowCount > 0;
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
    // Check if email already exists
    if (emailExists($email)) {
        header("location: ../signup.php?error=emailexists");
        exit();
    }

    //making config as we need this everytime we can just use it through include_once
//1st step for database php connection
$serverName = "localhost";
$dBUsername = "root";
$dBPassword = "";
$dBName = "site_database";

//Before we can access data in the MySQL database, we need to be able to connect to the server i.e php
$conn = new mysqli($serverName,$dBUsername,$dBPassword,$dBName );

// Check connection
if(!$conn){
    die("Connection failed: ".$conn->connect_error());
}

   //using prepare statement for preventing injection
   $sql = $conn->prepare("INSERT INTO customer (customer_fname, customer_email, customer_password, customer_phone, customer_address) VALUES (?, ?, ?, ?, ?)");
   
   $sql->bind_param('sssss', $name, $email, $pwd, $number, $address);
   $result = $sql->execute();
 
   //last step closing connection
   $sql->close(); //closing prepare statement
   $conn->close();
   
   if ($result) {
       // Successful signup
       // Check if session is not already started
       if (session_status() === PHP_SESSION_NONE) {
           session_start();
       }
       
       // Get redirect parameter if it exists
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
   } else {
       // Failed to insert into database
       header("location: ../signup.php?error=registrationfailed");
   }
   exit();
}
    