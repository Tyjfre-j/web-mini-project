<?php
ob_start(); // Start output buffering to prevent "headers already sent" errors
session_start();
include_once 'includes/config.php';
//  all functions
require_once 'includes/functions.php';

// Debug mode
$debug = false;
if($debug && isset($_SESSION)) {
    echo "<pre>SESSION: ";
    print_r($_SESSION);
    echo "</pre>";
    echo "<pre>GET: ";
    print_r($_GET);
    echo "</pre>";
    echo "<pre>REFERER: " . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "Not set") . "</pre>";
}

 //run whenever this file is used no need of isset or any condition to get website image footer etc
 $sql5 ="SELECT * FROM  settings;";
 $result5 = $conn->query($sql5);
 if(mysqli_num_rows($result5) > 0) {
  $row5 = mysqli_fetch_assoc($result5);
  $_SESSION['web-name'] = "PeakGear";
  $_SESSION['web-img'] = $row5['website_logo'];
  $_SESSION['web-footer'] = $row5['website_footer'];
}

// Store referrer URL if no explicit redirect parameter is provided
if(!isset($_GET['redirect']) && isset($_SERVER['HTTP_REFERER'])) {
    $referer = $_SERVER['HTTP_REFERER'];
    // Only store internal referrers from this website
    if(strpos($referer, $_SERVER['SERVER_NAME']) !== false) {
        // Extract only the path portion of the URL, not the full URL
        $parsed_url = parse_url($referer);
        $path = isset($parsed_url['path']) ? $parsed_url['path'] : '';
        
        // Include the query string if present
        if(isset($parsed_url['query']) && !empty($parsed_url['query'])) {
            $path .= '?' . $parsed_url['query'];
        }
        
        // Don't redirect back to login or signup pages
        if(strpos($path, 'login.php') === false && strpos($path, 'signup.php') === false) {
            $_SESSION['login_referrer'] = $path;
        }
    }
}

// Direct redirect parameter has higher priority
if(isset($_GET['redirect']) && !empty($_GET['redirect'])) {
    // Make sure the redirect URL is properly decoded if it was encoded
    $redirect = urldecode($_GET['redirect']);
    $_SESSION['login_referrer'] = $redirect; 
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
      integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Login - PeakGear</title>
    <style>
      :root {
        --primary-color: #3a86ff;
        --primary-dark: #2667cc;
        --secondary-color: #8338ec;
        --accent-color: #ff006e;
        --success-color: #38b000;
        --text-dark: #333333;
        --text-light: #f8f9fa;
        --gray-light: #f8f9fa;
        --gray-medium: #e9ecef;
        --gray-dark: #6c757d;
      }
    
      * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
      }
      
      body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        justify-content: center;
        align-items: center;
        background: linear-gradient(135deg, var(--gray-light), var(--gray-medium));
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        position: relative;
        overflow-x: hidden;
      }
      
      body::before {
        content: "";
        position: absolute;
        top: -50%;
        left: -10%;
        width: 120%;
        height: 80%;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        transform: rotate(-6deg);
        z-index: -1;
        border-radius: 0 0 50% 50% / 0 0 100% 100%;
      }
      
      .login-container {
        width: 100%;
        max-width: 450px;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        background-color: white;
        position: relative;
        overflow: hidden;
        margin: 20px;
      }
      
      .logo-box {
        padding: 10px;
        display: flex;
        justify-content: center;
        flex-direction: column;
        align-items: center;
        margin-bottom: 25px;
      }
      
      .form-title {
        text-align: center;
        margin: 15px 0;
        color: var(--text-dark);
        font-size: 1.8rem;
        font-weight: 600;
      }
      
      .divider {
        height: 2px;
        background: linear-gradient(to right, transparent, var(--primary-color), transparent);
        margin: 10px auto 25px;
        width: 60%;
      }
      
      .error-message {
        color: white;
        background-color: var(--accent-color);
        border-radius: 8px;
        padding: 12px 15px;
        margin-bottom: 20px;
        text-align: center;
        font-weight: 500;
        box-shadow: 0 4px 12px rgba(255, 0, 110, 0.2);
      }
      
      .form-label {
        color: var(--text-dark);
        font-weight: 500;
        margin-bottom: 8px;
      }
      
      .form-control {
        padding: 12px 15px;
        border: 1px solid var(--gray-medium);
        border-radius: 8px;
        transition: all 0.3s ease;
        font-size: 1rem;
      }
      
      .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(58, 134, 255, 0.2);
      }
      
      .input-group {
        position: relative;
        margin-bottom: 1.5rem;
      }
      
      .input-icon {
        position: absolute;
        top: 50%;
        left: 15px;
        transform: translateY(-50%);
        color: var(--gray-dark);
      }
      
      .input-with-icon {
        padding-left: 45px;
      }
      
      .btn-container {
        display: flex;
        justify-content: space-between;
        margin-top: 25px;
        gap: 15px;
      }
      
      .btn-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(58, 134, 255, 0.3);
        flex: 1;
      }
      
      .btn-primary:hover {
        background: linear-gradient(135deg, var(--primary-dark), var(--secondary-color));
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(58, 134, 255, 0.35);
      }
      
      .btn-outline-secondary {
        background: transparent;
        border: 2px solid var(--gray-medium);
        color: var(--text-dark);
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        flex: 1;
      }
      
      .btn-outline-secondary:hover {
        background-color: var(--gray-light);
        border-color: var(--gray-dark);
        transform: translateY(-2px);
      }
      
      .form-check-input {
        width: 18px;
        height: 18px;
        margin-right: 8px;
        border: 1px solid var(--gray-medium);
      }
      
      .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
      }
      
      .form-check-label {
        font-size: 0.95rem;
        color: var(--gray-dark);
      }
      
      .return-home {
        display: block;
        margin-top: 20px;
        text-align: center;
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.2s ease;
      }
      
      .return-home:hover {
        color: var(--secondary-color);
        text-decoration: underline;
      }
      
      @media (max-width: 576px) {
        .login-container {
          padding: 30px 20px;
        }
        
        .btn-container {
          flex-direction: column;
        }
        
        .btn-primary, .btn-outline-secondary {
          width: 100%;
        }
      }
    </style>
  </head>
  <body>
     <?php 
     if(!(isset($_SESSION['id']))){
     ?>
     <div class="login-container">
        <div class="logo-box">
          <h1 style="font-size: 2.6rem; font-weight: 800; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 10px;">PeakGear</h1>
          <h3 class="form-title">Welcome Back</h3>
          <div class="divider"></div>
        </div>
        
        <?php
        if(isset($_POST['login'])){
            $hasError = false;
            if(empty($_POST['email'])){
                echo "<div class='error-message'>Please enter your email</div>";
                $hasError = true;
            }
            
            if(empty($_POST['pwd'])){
                echo "<div class='error-message'>Please enter your password</div>";
                $hasError = true;
            }
            
            if(!$hasError) {
                $email = mysqli_real_escape_string($conn,$_POST['email']);
                $password = mysqli_real_escape_string($conn,$_POST['pwd']);
                
                $sql ="SELECT * FROM customer WHERE customer_email='{$email}';";
                $result = $conn->query($sql);
                
                if($result->num_rows==1){ //if any one data found go inside it
                    $row = $result->fetch_assoc();
                    // For security: This should use password_verify() instead of direct comparison
                    // But keeping existing logic for now
                    if($password == $row['customer_password']){
                        // Session already started at the top of the file
                        $_SESSION['id'] = $row['customer_id'];
                        $_SESSION['user_id'] = $row['customer_id'];
                        $_SESSION['customer_role'] = $row['customer_role'];
                        
                        $redirect_to = "profile.php?id={$_SESSION['id']}"; // Default redirect
                        
                        // Check if there's a stored referrer
                        if(isset($_SESSION['login_referrer']) && !empty($_SESSION['login_referrer'])) {
                            $redirect_to = $_SESSION['login_referrer'];
                            unset($_SESSION['login_referrer']); // Clear it after using
                        }
                        
                        header("location: " . $redirect_to);
                        exit();
                    } else {
                        echo "<div class='error-message'>Incorrect password</div>";
                    }
                } else {
                    if(!empty($_POST['email'])){
                        echo "<div class='error-message'>Account not found. Please sign up first.</div>";
                    }
                }
            }
        }
        ?>
        
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <div class="mb-3 input-group">
            <i class="fas fa-envelope input-icon"></i>
            <input
              id="inputEmail"
              name="email"
              type="email"
              class="form-control input-with-icon"
              placeholder="Enter your email"
              value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
            />
          </div>
          <div class="mb-3 input-group">
            <i class="fas fa-lock input-icon"></i>
            <input
              id="inputPassword"
              name="pwd"
              type="password"
              class="form-control input-with-icon"
              placeholder="Enter your password"
            />
          </div>

          <div class="mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="rememberMe" name="remember" />
              <label class="form-check-label" for="rememberMe">
                Remember Me
              </label>
            </div>
          </div>
          
          <div class="btn-container">
            <button type="submit" name="login" class="btn btn-primary">
                Sign In
            </button>
            <a href="./signup.php" class="btn btn-outline-secondary">
                Create Account
            </a>
          </div>
        </form>
        
        <a href="index.php" class="return-home">Return to Homepage</a>
      </div>
    <?php } else { ?>
      <div class="login-container">
        <div class="logo-box">
          <h1 style="font-size: 2.6rem; font-weight: 800; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 10px;">PeakGear</h1>
          <h3 class="form-title">You're logged in</h3>
          <div class="divider"></div>
        </div>
        <div class="btn-container">
          <a href="profile.php?id=<?php echo $_SESSION['id']; ?>" class="btn btn-primary">
            Go to Profile
          </a>
          <a href="logout.php" class="btn btn-outline-secondary">
            Logout
          </a>
        </div>
        
        <a href="index.php" class="return-home">Return to Homepage</a>
      </div>
    <?php } ?>
  </body>
</html>
<?php ob_end_flush(); // End output buffering and send content to browser ?>
