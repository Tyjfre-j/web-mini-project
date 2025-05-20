<?php
ob_start(); // Start output buffering to prevent "headers already sent" errors
// Check if session is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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

// Process form submission
if(isset($_POST['login'])){
    $hasError = false;
    
    if(empty($_POST['email'])){
        $_SESSION['login_error'] = "Please enter your email";
        $_SESSION['error_field'] = "email";
        $hasError = true;
    }
    
    if(empty($_POST['pwd'])){
        $_SESSION['login_error'] = "Please enter your password";
        $_SESSION['error_field'] = "password";
        $hasError = true;
    }
    
    if(!$hasError) {
        $email = mysqli_real_escape_string($conn,$_POST['email']);
        $password = mysqli_real_escape_string($conn,$_POST['pwd']);
        
        $sql = "SELECT * FROM customer WHERE customer_email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows == 1){ //if any one data found go inside it
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
                $_SESSION['login_error'] = "Incorrect password";
                $_SESSION['error_field'] = "password";
            }
        } else {
            if(!empty($_POST['email'])){
                $_SESSION['login_error'] = "Email not found. Please check your email or sign up first.";
                $_SESSION['error_field'] = "email";
            }
        }
        $stmt->close();
    }
    
    // Redirect back to login page with error indicator
    header("Location: login.php?error=1");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./css/auth.css">
    <title>Login - PeakGear</title>
    <style>
      .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: var(--gray-dark);
        transition: color 0.3s;
      }
      
      .password-toggle:hover {
        color: var(--primary-color);
      }
    </style>
  </head>
  <body class="auth-body">
     <?php 
     if(!(isset($_SESSION['id']))){
     ?>
     <div class="auth-container">
        <div class="auth-logo-box">
          <h1 class="auth-site-title">PeakGear</h1>
          <h3 class="auth-form-title">Welcome Back</h3>
          <div class="auth-divider"></div>
        </div>
        
        <?php
        // Display error messages if they exist
        if(isset($_GET['error']) && isset($_SESSION['login_error'])) {
            $errorMessage = $_SESSION['login_error'];
            $errorField = isset($_SESSION['error_field']) ? $_SESSION['error_field'] : '';
            
            echo "<div class='auth-error-message'><strong>Error:</strong> $errorMessage</div>";
            
            // Add JavaScript to highlight the field with error
            if (!empty($errorField)) {
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        let fieldId = 'input" . ucfirst($errorField) . "';
                        if (fieldId === 'inputPassword') {
                            document.getElementById('inputPassword').style.borderColor = '#ff006e';
                            document.getElementById('inputPassword').focus();
                        } else {
                            let field = document.getElementById(fieldId);
                            if (field) {
                                field.style.borderColor = '#ff006e';
                                field.focus();
                            }
                        }
                    });
                </script>";
            }
            
            // Clear the session error variables
            unset($_SESSION['login_error']);
            unset($_SESSION['error_field']);
        }
        
        if(isset($_GET['signup']) && $_GET['signup'] == 'success') {
            echo "<div class='auth-success-message'>Registration successful! You can now log in.</div>";
        }
        ?>
        
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <div class="auth-input-group">
            <i class="fas fa-envelope auth-input-icon"></i>
            <input
              id="inputEmail"
              name="email"
              type="email"
              class="auth-input"
              placeholder="Enter your email"
            />
          </div>
          <div class="auth-input-group">
            <i class="fas fa-lock auth-input-icon"></i>
            <input
              id="inputPassword"
              name="pwd"
              type="password"
              class="auth-input"
              placeholder="Enter your password"
            />
            <i class="fas fa-eye-slash password-toggle" onclick="togglePasswordVisibility('inputPassword', this)"></i>
          </div>

          <div class="auth-check-container">
            <input class="auth-check-input" type="checkbox" id="rememberMe" name="remember" />
            <label class="auth-check-label" for="rememberMe">
              Remember Me
            </label>
          </div>
          
          <div class="auth-btn-container">
            <button type="submit" name="login" class="auth-btn-primary">
                Sign In
            </button>
            <a href="./signup.php" class="auth-btn-secondary">
                Create Account
            </a>
          </div>
        </form>
        
        <a href="index.php" class="auth-link">Return to Homepage</a>
      </div>
    <?php } else { ?>
      <div class="auth-container">
        <div class="auth-logo-box">
          <h1 class="auth-site-title">PeakGear</h1>
          <h3 class="auth-form-title">You're logged in</h3>
          <div class="auth-divider"></div>
        </div>
        <div class="auth-btn-container">
          <a href="profile.php?id=<?php echo $_SESSION['id']; ?>" class="auth-btn-primary">
            Go to Profile
          </a>
          <a href="logout.php" class="auth-btn-secondary">
            Logout
          </a>
        </div>
        
        <a href="index.php" class="auth-link">Return to Homepage</a>
      </div>
    <?php } ?>
  </body>
</html>
<script>
  // Password visibility toggle function
  function togglePasswordVisibility(inputId, icon) {
    const passwordInput = document.getElementById(inputId);
    if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      icon.classList.remove('fa-eye-slash');
      icon.classList.add('fa-eye');
    } else {
      passwordInput.type = 'password';
      icon.classList.remove('fa-eye');
      icon.classList.add('fa-eye-slash');
    }
  }

  // Clear form fields and error parameters when page is reloaded
  document.addEventListener('DOMContentLoaded', function() {
    // Reset form if it was a page refresh/reload
    if (performance.navigation.type === 1 || window.location.href.indexOf('?') > -1) {
      // Reset all form fields
      document.querySelectorAll('form').forEach(form => form.reset());
      
      // Remove error styling
      document.querySelectorAll('.auth-input').forEach(input => {
        input.style.borderColor = '';
      });
      
      // Hide any error messages
      const errorMessages = document.querySelectorAll('.auth-error-message');
      errorMessages.forEach(msg => {
        msg.style.display = 'none';
      });
      
      // Clean URL if it has parameters
      if (window.history.replaceState && window.location.href.indexOf('?') > -1) {
        const cleanUrl = window.location.href.split('?')[0];
        window.history.replaceState(null, null, cleanUrl);
      }
    }
  });
</script>
<?php ob_end_flush(); // End output buffering and send content to browser ?>
