<?php include_once('./includes/headerNav.php'); ?>

<?php
// Store redirect parameter if present
if(isset($_GET['redirect']) && !empty($_GET['redirect'])) {
    $_SESSION['redirect_after_signup'] = $_GET['redirect'];
}
// If no redirect is specified, check for HTTP_REFERER
else if(isset($_SERVER['HTTP_REFERER'])) {
    $referer = $_SERVER['HTTP_REFERER'];
    // Only store internal referrers from this website
    if(strpos($referer, $_SERVER['SERVER_NAME']) !== false) {
        // Extract only the path portion of the URL
        $parsed_url = parse_url($referer);
        $path = isset($parsed_url['path']) ? $parsed_url['path'] : '';
        
        // Include the query string if present
        if(isset($parsed_url['query']) && !empty($parsed_url['query'])) {
            $path .= '?' . $parsed_url['query'];
        }
        
        // Don't redirect back to login or signup pages
        if(strpos($path, 'login.php') === false && strpos($path, 'signup.php') === false) {
            $_SESSION['redirect_after_signup'] = $path;
        }
    }
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
    <title>Sign Up - PeakGear</title>
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
      
      .signup-container {
        width: 100%;
        max-width: 800px;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        background-color: white;
        position: relative;
        overflow: hidden;
        margin: 20px;
      }
      
      .logo-box {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        margin-bottom: 25px;
      }
      
      .signup-title {
        text-align: center;
        margin: 15px 0 5px;
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
        margin-bottom: 1rem;
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
      
      .alert-success {
        color: white !important;
        background-color: var(--success-color) !important;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        text-align: center;
        font-weight: 500;
        box-shadow: 0 4px 12px rgba(56, 176, 0, 0.2);
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
      
      .form-row {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
      }
      
      .form-col {
        flex: 1;
        min-width: 250px;
      }
      
      @media (max-width: 768px) {
        .signup-container {
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
    <div class="signup-container">
      <div class="logo-box">
        <h1 style="font-size: 2.6rem; font-weight: 800; background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 10px;">PeakGear</h1>
        <h2 class="signup-title">Create Your Account</h2>
        <div class="divider"></div>
      </div>
      
      <?php
      if(isset($_GET['error'])) {
          $error = $_GET['error'];
          $message = '';
          
          switch($error) {
              case 'emptyinput':
                  $message = 'Please fill all fields';
                  break;
              case 'invalidname':
                  $message = 'Please enter a valid name';
                  break;
              case 'invalidemail':
                  $message = 'Please enter a valid email';
                  break;
              case 'passwordsdontmatch':
                  $message = 'Passwords do not match';
                  break;
              case 'emailtaken':
                  $message = 'Email is already registered';
                  break;
              default:
                  $message = 'Something went wrong';
          }
          
          echo "<div class='error-message'>{$message}</div>";
      }
      
      if(isset($_GET['signup']) && $_GET['signup'] == 'success') {
          echo "<div class='alert alert-success text-center'>Registration successful! You can now log in.</div>";
      }
      ?>
      
      <form action="includes/signup.inc.php" method="post" class="row g-3">
        <div class="form-row">
          <div class="form-col">
            <div class="input-group">
              <i class="fas fa-user input-icon"></i>
              <input
                type="text"
                class="form-control input-with-icon"
                id="inputName"
                name="name"
                required="required"
                placeholder="Enter your full name"
              />
            </div>
          </div>
          <div class="form-col">
            <div class="input-group">
              <i class="fas fa-phone input-icon"></i>
              <input
                type="tel"
                class="form-control input-with-icon"
                id="inputNumber"
                name="number"
                required="required"
                placeholder="Enter your phone number"
              />
            </div>
          </div>
        </div>
        
        <div class="form-row">
          <div class="form-col">
            <div class="input-group">
              <i class="fas fa-envelope input-icon"></i>
              <input 
                type="email" 
                class="form-control input-with-icon"
                id="inputEmail"
                name="email"
                placeholder="Enter your email"
                required="required"
              />
            </div>
          </div>
          <div class="form-col">
            <div class="input-group">
              <i class="fas fa-map-marker-alt input-icon"></i>
              <input
                type="text"
                class="form-control input-with-icon"
                id="inputAddress"
                name="address"
                required="required"
                placeholder="Enter your address"
              />
            </div>
          </div>
        </div>

        <div class="form-row">
          <div class="form-col">
            <div class="input-group">
              <i class="fas fa-lock input-icon"></i>
              <input 
                type="password"
                class="form-control input-with-icon"
                id="inputPassword"
                name="pwd"
                placeholder="Create a password"
                required="required" 
              />
            </div>
          </div>
          <div class="form-col">
            <div class="input-group">
              <i class="fas fa-check-circle input-icon"></i>
              <input 
                type="password" 
                class="form-control input-with-icon" 
                id="inputConfirmPassword"
                name="rpwd"
                placeholder="Confirm your password"
                required="required"
              />
            </div>
          </div>
        </div>

        <div class="btn-container col-12">
          <a href="login.php<?php echo isset($_SESSION['redirect_after_signup']) ? '?redirect=' . urlencode($_SESSION['redirect_after_signup']) : ''; ?>" class="btn btn-outline-secondary">
            Back to Login
          </a>
          <button 
            type="submit" 
            class="btn btn-primary"
            name="submit"
          >
            Create Account
          </button>
        </div>
      </form>
      
      <a href="index.php" class="return-home">Return to Homepage</a>
    </div>

    <!-- Script Tags -->
    <script src="./js/jquery.js" type="text/javascript"></script>
    <script src="./js/bootstrap.min.js" type="text/javascript"></script>
  </body>
</html>
