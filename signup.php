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
    <link rel="stylesheet" href="./css/auth.css">
    <title>Sign Up - PeakGear</title>
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
    <div class="auth-container signup-container">
      <div class="auth-logo-box">
        <h1 class="auth-site-title">PeakGear</h1>
        <h2 class="auth-form-title">Create Your Account</h2>
        <div class="auth-divider"></div>
      </div>
      
      <?php
      if(isset($_GET['error'])) {
          $error = $_GET['error'];
          $message = '';
          $errorField = '';
          
          switch($error) {
              case 'emptyinput':
                  $message = 'Please fill all required fields';
                  break;
              case 'invalidname':
                  $message = 'Please enter a valid name';
                  $errorField = 'name';
                  break;
              case 'invalidphone':
                  $message = 'Please enter a valid 10-digit phone number';
                  $errorField = 'number';
                  break;
              case 'invalidemail':
                  $message = 'Please enter a valid email address';
                  $errorField = 'email';
                  break;
              case 'passwordsdontmatch':
                  $message = 'Passwords do not match';
                  $errorField = 'password';
                  break;
              case 'emailtaken':
              case 'emailexists':
                  $message = 'Email is already registered';
                  $errorField = 'email';
                  break;
              default:
                  $message = 'Something went wrong';
          }
          
          echo "<div class='auth-error-message'>";
          if (!empty($errorField)) {
              echo "<strong>Error in " . ucfirst($errorField) . ":</strong> ";
          }
          echo $message . "</div>";
          
          // Add JavaScript to highlight the field with error
          if (!empty($errorField)) {
              echo "<script>
                  document.addEventListener('DOMContentLoaded', function() {
                      let fieldId = 'input" . ucfirst($errorField) . "';
                      if (fieldId === 'inputPassword') {
                          document.getElementById('inputPassword').style.borderColor = '#ff006e';
                          document.getElementById('inputConfirmPassword').style.borderColor = '#ff006e';
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
      }
      
      if(isset($_GET['signup']) && $_GET['signup'] == 'success') {
          echo "<div class='auth-success-message'>Registration successful! You can now log in.</div>";
      }
      ?>
      
      <form action="includes/signup.inc.php" method="post">
        <div class="auth-form-row">
          <div class="auth-form-col">
            <div class="auth-input-group">
              <i class="fas fa-user auth-input-icon"></i>
              <input
                type="text"
                class="auth-input"
                id="inputName"
                name="name"
                required="required"
                placeholder="Enter your full name"
              />
            </div>
          </div>
          <div class="auth-form-col">
            <div class="auth-input-group">
              <i class="fas fa-phone auth-input-icon"></i>
              <input
                type="tel"
                class="auth-input"
                id="inputNumber"
                name="number"
                required="required"
                placeholder="Enter your phone number"
              />
            </div>
          </div>
        </div>
        
        <div class="auth-form-row">
          <div class="auth-form-col">
            <div class="auth-input-group">
              <i class="fas fa-envelope auth-input-icon"></i>
              <input 
                type="email" 
                class="auth-input"
                id="inputEmail"
                name="email"
                placeholder="Enter your email"
                required="required"
              />
            </div>
          </div>
          <div class="auth-form-col">
            <div class="auth-input-group">
              <i class="fas fa-map-marker-alt auth-input-icon"></i>
              <input
                type="text"
                class="auth-input"
                id="inputAddress"
                name="address"
                required="required"
                placeholder="Enter your address"
              />
            </div>
          </div>
        </div>

        <div class="auth-form-row">
          <div class="auth-form-col">
            <div class="auth-input-group">
              <i class="fas fa-lock auth-input-icon"></i>
              <input 
                type="password"
                class="auth-input"
                id="inputPassword"
                name="pwd"
                placeholder="Create a password"
                required="required" 
              />
              <i class="fas fa-eye-slash password-toggle" onclick="togglePasswordVisibility('inputPassword', this)"></i>
            </div>
          </div>
          <div class="auth-form-col">
            <div class="auth-input-group">
              <i class="fas fa-check-circle auth-input-icon"></i>
              <input 
                type="password" 
                class="auth-input" 
                id="inputConfirmPassword"
                name="rpwd"
                placeholder="Confirm your password"
                required="required"
              />
              <i class="fas fa-eye-slash password-toggle" onclick="togglePasswordVisibility('inputConfirmPassword', this)"></i>
            </div>
          </div>
        </div>

        <div class="auth-btn-container">
          <a href="login.php<?php echo isset($_SESSION['redirect_after_signup']) ? '?redirect=' . urlencode($_SESSION['redirect_after_signup']) : ''; ?>" class="auth-btn-secondary">
            Back to Login
          </a>
          <button 
            type="submit" 
            class="auth-btn-primary"
            name="submit"
          >
            Create Account
          </button>
        </div>
      </form>
      
      <a href="index.php" class="auth-link">Return to Homepage</a>
    </div>

    <!-- Script Tags -->
    <script src="./js/jquery.js" type="text/javascript"></script>
    <script src="./js/bootstrap.min.js" type="text/javascript"></script>
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
  </body>
</html>
