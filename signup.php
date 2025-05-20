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
    <title>Sign Up - PeakGear</title>
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
      .signup-container {
        width: 100%;
        max-width: 800px;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        background-color: white;
      }
      .logo-box {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        margin-bottom: 20px;
      }
      .signup-title {
        text-align: center;
        margin: 10px 0 20px;
        color: #333;
        font-size: 24px;
      }
      .form-label {
        font-weight: 500;
      }
      .btn-container {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
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
    </style>
  </head>
  <body>
    <div class="signup-container">
      <div class="logo-box">
        <img
          src="admin/upload/<?php echo $_SESSION['web-img']; ?>"
          alt="logo"
          width="200px"
        />
        <h2 class="signup-title">Create Account</h2>
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
        <div class="col-md-6">
          <label for="inputName" class="form-label">Full Name</label>
          <input
            type="text"
            class="form-control"
            id="inputName"
            name="name"
            required="required"
            placeholder="Enter your full name"
          />
        </div>
        <div class="col-md-6">
          <label for="inputNumber" class="form-label">Phone Number</label>
          <input
            type="tel"
            class="form-control"
            id="inputNumber"
            name="number"
            required="required"
            placeholder="Enter your phone number"
          />
        </div>
        <div class="col-md-6">
          <label for="inputEmail" class="form-label">Email</label>
          <input 
            type="email" 
            class="form-control"
            id="inputEmail"
            name="email"
            placeholder="Enter your email"
            required="required"
          />
        </div>
        <div class="col-md-6">
          <label for="inputAddress" class="form-label">Address</label>
          <input
            type="text"
            class="form-control"
            id="inputAddress"
            name="address"
            required="required"
            placeholder="Enter your address"
          />
        </div>

        <div class="col-md-6">
          <label for="inputPassword" class="form-label">Password</label>
          <input 
            type="password"
            class="form-control"
            id="inputPassword"
            name="pwd"
            placeholder="Create a password"
            required="required" 
          />
        </div>
        <div class="col-md-6">
          <label for="inputConfirmPassword" class="form-label">Confirm Password</label>
          <input 
            type="password" 
            class="form-control" 
            id="inputConfirmPassword"
            name="rpwd"
            placeholder="Confirm your password"
            required="required"
          />
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
    </div>

    <!-- Script Tags -->
    <script src="./js/jquery.js" type="text/javascript"></script>
    <script src="./js/bootstrap.min.js" type="text/javascript"></script>
  </body>
</html>
