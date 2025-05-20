<?php  session_start();
 include_once 'includes/config.php';
//  all functions
require_once 'functions/functions.php';

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
 $row5 = $result5->fetch_assoc();
 $_SESSION['web-name'] = $row5['website_name'];
 $_SESSION['web-img'] = $row5['website_logo'];
 $_SESSION['web-footer'] = $row5['website_footer'];

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
    <title>Login - <?php echo $_SESSION['web-name']; ?></title>
    <style>
      * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
      }
      body {
        display: flex;
        flex-direction: column;
        height: 100vh;
        justify-content: center;
        align-items: center;
        background-color: #f8f9fa;
      }
      .login-container {
        width: 100%;
        max-width: 420px;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        background-color: white;
      }
      .logo-box {
        padding: 10px;
        display: flex;
        justify-content: center;
        flex-direction: column;
        align-items: center;
        margin-bottom: 20px;
      }
      .form-title {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
      }
      .error-message {
        color: #dc3545;
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 15px;
        text-align: center;
      }
      .btn-container {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
      }
      .signup-link {
        display: inline-block;
        margin-top: 15px;
        text-align: center;
        width: 100%;
      }
    </style>
  </head>
  <body>
     <?php 
     if(!(isset($_SESSION['id']))){
     ?>
     <div class="login-container">
        <div class="logo-box">
          <img
            src="admin/upload/<?php echo $_SESSION['web-img']; ?>"
            alt="logo"
            width="200px"
          />
          <h3 class="form-title">User Login</h3>
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
          <div class="mb-3">
            <label for="inputEmail" class="form-label">Email address</label>
            <input
              id="inputEmail"
              name="email"
              type="email"
              class="form-control"
              placeholder="Enter your email"
              value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
            />
          </div>
          <div class="mb-3">
            <label for="inputPassword" class="form-label">Password</label>
            <input
              id="inputPassword"
              name="pwd"
              type="password"
              class="form-control"
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
                Sign in
            </button>
            <a href="./signup.php" class="btn btn-outline-secondary">
                Create Account
            </a>
          </div>
        </form>
      </div>
    <?php } else { ?>
      <div class="login-container">
        <div class="logo-box">
          <img
            src="admin/upload/<?php echo $_SESSION['web-img']; ?>"
            alt="logo"
            width="200px"
          />
          <h3 class="form-title">You're already logged in</h3>
        </div>
        <div class="btn-container">
          <a href="profile.php?id=<?php echo $_SESSION['id']; ?>" class="btn btn-primary">
            Go to Profile
          </a>
          <a href="logout.php" class="btn btn-outline-danger">
            Logout
          </a>
        </div>
      </div>
    <?php } ?>
  </body>
</html>
