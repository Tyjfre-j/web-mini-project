<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define a function to sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Process form submissions
if(isset($_POST['save'])){
   $success_type = "";
    $error_message = "";
    
    // Include database connection
    require_once "includes/config.php";
    
    // Handle name update
    if(isset($_POST['update_name'])){
        if(empty($_POST['name'])) {
            $error_message = "Name cannot be empty";
        } else {
            $name = sanitize_input($_POST['name']);
            
            // Check if name is too short or too long
            if(strlen($name) < 3) {
                $error_message = "Name is too short (minimum 3 characters)";
            } elseif(strlen($name) > 50) {
                $error_message = "Name is too long (maximum 50 characters)";
            } else {
                $sql = "UPDATE customer 
                        SET customer_fname = ? 
                        WHERE customer_id = ?";
                        
                // Use prepared statement to prevent SQL injection
                if($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("si", $name, $_SESSION['id']);
                    
                    if($stmt->execute()){
                        $_SESSION['success_message'] = 'Name updated successfully!';
                        $_SESSION['customer_name'] = $name;
                        $success_type = "profile";
                    } else {
                        $error_message = "Error updating name: " . $conn->error;
                    }
                    
                    $stmt->close();
                } else {
                    $error_message = "Error preparing statement: " . $conn->error;
                }
            }
        }
    }
    
    // Handle email update - only if no error occurred in previous operations
    if(isset($_POST['update_email']) && empty($error_message)){
        if(empty($_POST['email'])) {
            $error_message = "Email address cannot be empty";
        } else {
            $email = sanitize_input($_POST['email']);
            
            // Validate email format
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error_message = "Invalid email format";
            } else {
                // Check if email already exists for a different user
                $check_sql = "SELECT customer_id FROM customer WHERE customer_email = ? AND customer_id != ?";
                if($check_stmt = $conn->prepare($check_sql)) {
                    $check_stmt->bind_param("si", $email, $_SESSION['id']);
                    $check_stmt->execute();
                    $check_result = $check_stmt->get_result();
                    
                    if($check_result->num_rows > 0) {
                        $error_message = "This email is already in use by another account";
                    } else {
                        $sql = "UPDATE customer 
                                SET customer_email = ? 
                                WHERE customer_id = ?";
                                
                        if($stmt = $conn->prepare($sql)) {
                            $stmt->bind_param("si", $email, $_SESSION['id']);
                            
                            if($stmt->execute()){
                                $_SESSION['success_message'] = 'Email updated successfully!';
                                $_SESSION['customer_email'] = $email;
                                $success_type = "profile";
                            } else {
                                $error_message = "Error updating email: " . $conn->error;
                            }
                            
                            $stmt->close();
                        } else {
                            $error_message = "Error preparing statement: " . $conn->error;
                        }
                    }
                    $check_stmt->close();
                } else {
                    $error_message = "Error checking email uniqueness: " . $conn->error;
                }
            }
        }
    }
    
    // Combined name and email update (backward compatibility) - only if no error occurred in previous operations
    if(!empty($_POST['name']) && !empty($_POST['email']) && !isset($_POST['update_name']) && !isset($_POST['update_email']) && empty($error_message)){
        $name = sanitize_input($_POST['name']);
        $email = sanitize_input($_POST['email']);
        
        // Check if name is too short or too long
        if(strlen($name) < 3) {
            $error_message = "Name is too short (minimum 3 characters)";
        } elseif(strlen($name) > 50) {
            $error_message = "Name is too long (maximum 50 characters)";
        } 
        // Validate email format
        elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message = "Invalid email format";
        } else {
            // Check if email already exists for a different user
            $check_sql = "SELECT customer_id FROM customer WHERE customer_email = ? AND customer_id != ?";
            if($check_stmt = $conn->prepare($check_sql)) {
                $check_stmt->bind_param("si", $email, $_SESSION['id']);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();
                
                if($check_result->num_rows > 0) {
                    $error_message = "This email is already in use by another account";
                } else {
                    $sql = "UPDATE customer 
                            SET customer_fname = ?, customer_email = ? 
                            WHERE customer_id = ?";
                            
                    if($stmt = $conn->prepare($sql)) {
                        $stmt->bind_param("ssi", $name, $email, $_SESSION['id']);
                        
                        if($stmt->execute()){
         $_SESSION['success_message'] = 'Profile updated successfully!';
                            $_SESSION['customer_name'] = $name;
                            $_SESSION['customer_email'] = $email;
         $success_type = "profile";
                        } else {
                            $error_message = "Error updating profile: " . $conn->error;
                        }
                        
                        $stmt->close();
                    } else {
                        $error_message = "Error preparing statement: " . $conn->error;
                    }
                }
                $check_stmt->close();
            } else {
                $error_message = "Error checking email uniqueness: " . $conn->error;
            }
        }
    }
    
    // Handle address update - only if no error occurred in previous operations
    if(isset($_POST['address']) && empty($error_message)){
        // Address can be empty to clear it
        $address = sanitize_input($_POST['address']);
        
        if(strlen($address) > 255) {
            $error_message = "Address is too long (maximum 255 characters)";
        } else {
            $sql = "UPDATE customer 
                    SET customer_address = ? 
                    WHERE customer_id = ?";
                    
            if($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("si", $address, $_SESSION['id']);
                
                if($stmt->execute()){
         $_SESSION['success_message'] = 'Address updated successfully!';
                    $_SESSION['customer_address'] = $address;
         $success_type = "address";
                } else {
                    $error_message = "Error updating address: " . $conn->error;
                }
                
                $stmt->close();
            } else {
                $error_message = "Error preparing statement: " . $conn->error;
            }
        }
    }
    
    // Handle phone update - only if no error occurred in previous operations
    if(isset($_POST['number']) && empty($error_message)){
        $phone = sanitize_input($_POST['number']);
        
        if(empty($phone)) {
            $error_message = "Phone number cannot be empty";
        } 
        // Simple phone validation - can be enhanced based on your requirements
        elseif(!preg_match("/^[0-9\+\-\(\) ]{6,20}$/", $phone)) {
            $error_message = "Invalid phone number format. Use only digits, +, -, (, ) and spaces, between 6-20 characters";
        } else {
            $sql = "UPDATE customer 
                    SET customer_phone = ? 
                    WHERE customer_id = ?";
                    
            if($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("si", $phone, $_SESSION['id']);
                
                if($stmt->execute()){
         $_SESSION['success_message'] = 'Contact number updated successfully!';
                    $_SESSION['customer_phone'] = $phone;
         $success_type = "contact";
                } else {
                    $error_message = "Error updating phone: " . $conn->error;
                }
                
                $stmt->close();
            } else {
                $error_message = "Error preparing statement: " . $conn->error;
            }
        }
    }
    
    // Close database connection
      $conn->close();
    
    // Store error message if any
    if(!empty($error_message)) {
        $_SESSION['error_message'] = $error_message;
   }
   
   // Redirect to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF'] . ($success_type ? "?update=" . $success_type : ""));
   exit();
}

include_once('./includes/headerNav.php');

// Check user authentication
if(!(isset($_SESSION['id']))){
   header("location:index.php?UnathorizedUser");
   exit();
}

// Fetch user data from database to ensure we have the latest information
include "includes/config.php";
$sql8 = "SELECT * FROM customer WHERE customer_id = ?";

if($stmt = $conn->prepare($sql8)) {
    $stmt->bind_param("i", $_SESSION['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows > 0) {
        $row8 = $result->fetch_assoc();
$_SESSION['customer_name'] = $row8['customer_fname'];
$_SESSION['customer_email'] = $row8['customer_email'];
$_SESSION['customer_phone'] = $row8['customer_phone'];
$_SESSION['customer_address'] = $row8['customer_address'];
    }
    
    $stmt->close();
}
$conn->close();
?>
<title>My Profile - PeakGear</title>    
<style>
    :root {
        --primary-color: #0d8a91; /* Main teal color from site */
        --primary-dark: #00656b; /* Deep maroon/teal from site */
        --secondary-color: #69585f; /* Coolers black from site */
        --accent-color: #00656b; /* Using deep maroon as accent */
        --success-color: #38b000;
        --text-dark: #333333;
        --text-light: #f8f9fa;
        --bg-light: #f8f9fa;
        --bg-primary: #e9f0f2;
        --bg-secondary: #f0f5f6;
        --bg-accent: #e3f2f3;
        --gray-light: #f8f9fa;
        --gray-medium: #e9ecef;
        --gray-dark: #6c757d;
        --border-light: #e0e0e0;
        --border-radius-md: 10px;
        --border-radius-sm: 5px;
        --shadow-sm: 0 2px 5px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 8px rgba(0, 0, 0, 0.08);
        --shadow-lg: 0 8px 16px rgba(0, 0, 0, 0.1);
    }
            
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }
            
    body {
        font-family: 'Poppins', sans-serif;
        background: var(--bg-primary);
        position: relative;
        background-image: linear-gradient(
            to right,
            rgba(13, 138, 145, 0.03) 1px,
            transparent 1px
        ),
        linear-gradient(to bottom, rgba(13, 138, 145, 0.03) 1px, transparent 1px);
        background-size: 20px 20px;
    }
            
    body::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 280px;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        z-index: -1;
    }
            
    .profile-header {
        text-align: center;
        padding: 30px 20px 40px;
        color: var(--text-light);
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
    }
            
    .header-content {
        background-color: rgba(0, 0, 0, 0.2);
        padding: 25px;
        border-radius: var(--border-radius-md);
        max-width: 700px;
        margin: 0 auto;
        backdrop-filter: blur(5px);
    }
            
    .profile-header h1 {
        font-size: 2.4rem;
        font-weight: 700;
        margin-bottom: 10px;
        text-transform: none;
    }
            
    .profile-header p {
        font-size: 1.1rem;
        opacity: 0.9;
    }
            
    #role {
        color: white;
        background: rgba(0, 0, 0, 0.2);
        padding: 3px 12px;
        border-radius: 20px;
        font-size: 1rem;
        margin-left: 8px;
    }
            
    .profile-container {
        max-width: 1100px;
        margin: -50px auto 50px;
        padding: 0 20px;
        position: relative;
        z-index: 1;
    }
            
    .card-container {
        background: transparent;
        border: none;
        padding: 0;
    }
            
    .profile-card {
        background: white;
        border-radius: var(--border-radius-md);
        overflow: hidden;
        box-shadow: var(--shadow-md);
        transition: all 0.3s ease;
        height: 100%;
        border: none;
        margin-bottom: 25px;
    }
            
    .profile-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }
            
    .card-header {
        padding: 20px 25px;
        background: linear-gradient(to right, rgba(13, 138, 145, 0.05), rgba(0, 101, 107, 0.05));
        border-bottom: 1px solid var(--border-light);
    }
            
    .card-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--text-dark);
        margin: 0;
        display: flex;
        align-items: center;
    }
            
    .card-title i {
        margin-right: 10px;
        color: var(--primary-color);
    }
            
    .card-body {
        padding: 25px;
    }
            
    .info-group {
        margin-bottom: 15px;
        position: relative;
    }
            
    .info-label {
        font-size: 0.85rem;
        color: var(--gray-dark);
        margin-bottom: 5px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .info-label .edit-btn {
        font-size: 0.85rem;
        color: var(--primary-color);
        cursor: pointer;
        background-color: rgba(13, 138, 145, 0.1);
        padding: 3px 8px;
        border-radius: 4px;
        border: 1px solid rgba(13, 138, 145, 0.2);
        display: inline-flex;
        align-items: center;
        transition: all 0.2s ease;
        font-weight: 500;
    }

    .info-label .edit-btn:hover {
        background-color: rgba(13, 138, 145, 0.2);
        border-color: rgba(13, 138, 145, 0.4);
        transform: translateY(-2px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .info-label .edit-btn i {
        margin-right: 4px;
        font-size: 0.8rem;
    }
            
    .info-value {
        font-size: 1.1rem;
        color: var(--text-dark);
        word-break: break-word;
        padding: 5px 10px;
        background-color: var(--bg-accent);
        border-radius: var(--border-radius-sm);
        border-left: 3px solid var(--primary-color);
    }

    .info-edit {
        display: none;
        margin-top: 10px;
        padding: 15px;
        background-color: var(--bg-light);
        border: 1px solid var(--border-light);
        border-radius: var(--border-radius-sm);
        box-shadow: var(--shadow-sm);
    }

    .info-edit.show {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .inline-buttons {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }

    .btn-save, .btn-cancel {
        flex: 1;
        padding: 8px;
        border-radius: var(--border-radius-sm);
        font-weight: 600;
        text-align: center;
        transition: all 0.2s ease;
        font-size: 0.9rem;
        cursor: pointer;
    }

    .btn-save {
        background-color: var(--primary-color);
        color: white;
    }

    .btn-save:hover {
        background-color: var(--primary-dark);
        transform: translateY(-2px);
    }

    .btn-cancel {
        background-color: var(--gray-light);
        color: var(--gray-dark);
        border: 1px solid var(--border-light);
    }

    .btn-cancel:hover {
        background-color: var(--gray-medium);
    }
            
    .btn-profile {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: var(--border-radius-sm);
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        width: 100%;
        margin-top: 15px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
            
    .btn-profile i {
        margin-right: 8px;
    }
            
    .btn-profile:hover {
        background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
        transform: translateY(-2px);
        color: white;
        box-shadow: 0 5px 15px rgba(13, 138, 145, 0.3);
    }
            
    .btn-outline {
        background: transparent;
        border: 2px solid var(--primary-color);
        color: var(--primary-color);
      }
      
      .btn-outline:hover {
        background: var(--gray-light);
        color: var(--primary-color);
      }
      
      .btn-admin {
        background: linear-gradient(135deg, #f1c40f, #e67e22);
      }
      
      .btn-admin:hover {
        background: linear-gradient(135deg, #e67e22, #f1c40f);
      }
      
      .form-control {
        padding: 12px 15px;
        border: 1px solid var(--border-light);
        border-radius: var(--border-radius-sm);
        transition: all 0.3s ease;
        font-size: 1rem;
        margin-bottom: 15px;
        background-color: var(--bg-light);
        height: auto;
        font-family: inherit;
        line-height: 1.5;
        box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);
    }

    textarea.form-control, 
    input.form-control {
        display: block;
        width: 100%;
      }
      
      .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(13, 138, 145, 0.2);
        outline: none;
      }
      
      .alert {
        border-radius: var(--border-radius-sm);
        padding: 15px 20px;
        margin: 20px auto;
        font-weight: 500;
        text-align: center;
        max-width: 600px;
        position: fixed;
        top: 100px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1000;
        box-shadow: var(--shadow-md);
        animation: fadeInOut 5s ease-in-out;
        display: flex;
        align-items: center;
        justify-content: center;
      }
      
      .alert-success {
        background-color: rgba(56, 176, 0, 0.15);
        border-left: 4px solid var(--success-color);
        color: var(--success-color);
      }
    
    .alert-danger {
        background-color: rgba(220, 53, 69, 0.15);
        border-left: 4px solid #dc3545;
        color: #dc3545;
    }

    .alert:before {
        margin-right: 10px;
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
    }
    
    .alert-success:before {
        content: "\f00c"; /* Check mark icon */
    }
    
    .alert-danger:before {
        content: "\f071"; /* Warning icon */
      }
      
      .profile_edit, .address_edit, .contact_edit {
        display: none;
      }
      
      .show {
        display: block;
      }
 
    .order-history {
        margin-top: 20px;
    }
 
    .order-history h3 {
        color: var(--primary-color);
        margin-bottom: 15px;
    }
 
    .order-table {
        width: 100%;
        border-collapse: collapse;
    }
 
    .order-table th, .order-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid var(--border-light);
    }
      
    .order-table th {
        background-color: var(--bg-accent);
        color: var(--primary-dark);
    }
 
    .order-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
    }
 
    .badge-delivered {
        background-color: rgba(56, 176, 0, 0.15);
        color: #2a8600;
    }
      
    .badge-processing {
        background-color: rgba(241, 196, 15, 0.15);
        color: #b7950b;
    }
 
    .badge-shipped {
        background-color: rgba(52, 152, 219, 0.15);
        color: #2980b9;
    }

    @keyframes fadeInOut {
        0% { opacity: 0; transform: translate(-50%, -20px); }
        10% { opacity: 1; transform: translate(-50%, 0); }
        80% { opacity: 1; transform: translate(-50%, 0); }
        100% { opacity: 0; transform: translate(-50%, -20px); }
      }
      
      @media (max-width: 767px) {
        .profile-card {
          margin-bottom: 25px;
        }
        
        .profile-header h1 {
          font-size: 1.8rem;
        }
        
        body::before {
          height: 220px;
        }

        .profile-container {
            margin-top: -30px;
        }
      }
    </style>
</head>
<div class="overlay" data-overlay></div><script src="js/index.js"></script><!--    - HEADER  --><header>  <!-- top head action, search etc in php -->  <!-- inc/topheadactions.php -->  <?php require_once './includes/topheadactions.php'; ?>  <!-- desktop navigation -->  <!-- inc/desktopnav.php -->  <?php require_once './includes/desktopnav.php' ?>  <!-- mobile nav in php -->  <!-- inc/mobilenav.php -->  <?php require_once './includes/mobilenav.php'; ?></header><!-- Header End====================================================================== -->

<!--
    - MAIN
  -->
<main>
<div class="profile-header">
  <div class="header-content">
    <h1>
      Welcome, <?php echo $_SESSION['customer_name']; ?>
      <span id="role"><?php echo ($_SESSION['customer_role']=='admin') ? 'Admin' : 'Member'; ?></span>
    </h1>
    <p>Manage your account information and track your purchases</p>
  </div>
</div>

<div class="profile-container">
  <div class="container card-container">
    <div class="row g-4">
      <!-- Profile Card -->
      <div class="col-md-4">
        <div class="profile-card">
          <div class="card-header">
            <h5 class="card-title">
              <i class="fas fa-user-circle"></i> Personal Information
            </h5>
          </div>
          <div class="card-body">
            <!-- Name Field -->
            <div class="info-group">
              <div class="info-label">
                Full Name
                <span class="edit-btn" data-target="name-edit"><i class="fas fa-pencil-alt"></i> Edit</span>
            </div>
              <div class="info-value"><?php echo htmlspecialchars($_SESSION['customer_name']); ?></div>
              <div class="info-edit" id="name-edit">
              <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input
                  type="text" 
                  name="name" 
                  class="form-control"
                    value="<?php echo htmlspecialchars($_SESSION['customer_name']); ?>"
                    placeholder="Enter full name..."
                    minlength="3"
                    maxlength="50"
                    required
                  />
                  <input type="hidden" name="update_name" value="1" />
                  <div class="inline-buttons">
                    <button type="submit" name="save" class="btn-save"><i class="fas fa-save"></i> Save</button>
                    <div class="btn-cancel" data-target="name-edit"><i class="fas fa-times"></i> Cancel</div>
                  </div>
                </form>
              </div>
            </div>

            <!-- Email Field -->
            <div class="info-group">
              <div class="info-label">
                Email Address
                <span class="edit-btn" data-target="email-edit"><i class="fas fa-pencil-alt"></i> Edit</span>
              </div>
              <div class="info-value"><?php echo htmlspecialchars($_SESSION['customer_email']); ?></div>
              <div class="info-edit" id="email-edit">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input
                  type="email" 
                  name="email" 
                  class="form-control"
                    value="<?php echo htmlspecialchars($_SESSION['customer_email']); ?>"
                    placeholder="Enter email address..."
                    required
                />
                  <input type="hidden" name="update_email" value="1" />
                  <div class="inline-buttons">
                    <button type="submit" name="save" class="btn-save"><i class="fas fa-save"></i> Save</button>
                    <div class="btn-cancel" data-target="email-edit"><i class="fas fa-times"></i> Cancel</div>
                  </div>
              </form>
        </div>
      </div>

            <!-- Phone Field -->
            <div class="info-group">
              <div class="info-label">
                Phone Number
                <span class="edit-btn" data-target="phone-edit"><i class="fas fa-pencil-alt"></i> Edit</span>
              </div>
              <div class="info-value">
                <?php 
                  echo !empty($_SESSION['customer_phone']) ? htmlspecialchars($_SESSION['customer_phone']) : '<span style="color: var(--gray-dark); font-style: italic;">No phone number added</span>';
                ?>
              </div>
              <div class="info-edit" id="phone-edit">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                  <input
                    type="tel" 
                    name="number" 
                    class="form-control"
                    value="<?php echo !empty($_SESSION['customer_phone']) ? htmlspecialchars($_SESSION['customer_phone']) : ''; ?>"
                    placeholder="Enter phone number..."
                    pattern="[0-9\+\-\(\) ]{6,20}"
                    title="Use only digits, +, -, (, ) and spaces, between 6-20 characters"
                    required
                  />
                  <div class="inline-buttons">
                    <button type="submit" name="save" class="btn-save"><i class="fas fa-save"></i> Save</button>
                    <div class="btn-cancel" data-target="phone-edit"><i class="fas fa-times"></i> Cancel</div>
                  </div>
                </form>
              </div>
            </div>

            <!-- Address Field -->
            <div class="info-group">
              <div class="info-label">
                Shipping Address
                <span class="edit-btn" data-target="address-edit"><i class="fas fa-pencil-alt"></i> Edit</span>
              </div>
              <div class="info-value">
                <?php 
                  echo !empty($_SESSION['customer_address']) ? nl2br(htmlspecialchars($_SESSION['customer_address'])) : '<span style="color: var(--gray-dark); font-style: italic;">No address added yet</span>';
                ?>
              </div>
              <div class="info-edit" id="address-edit">
              <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <textarea
                  name="address" 
                  class="form-control"
                  placeholder="Enter your full address..."
                  rows="3"
                    style="resize: none; min-height: 38px; padding: 12px 15px; font-family: inherit; line-height: 1.5;"
                    maxlength="255"
                  ><?php echo !empty($_SESSION['customer_address']) ? htmlspecialchars($_SESSION['customer_address']) : ''; ?></textarea>
                  <div class="inline-buttons">
                    <button type="submit" name="save" class="btn-save"><i class="fas fa-save"></i> Save</button>
                    <div class="btn-cancel" data-target="address-edit"><i class="fas fa-times"></i> Cancel</div>
                  </div>
              </form>
        </div>
      </div>

            <!-- Account Type Field -->
            <div class="info-group">
              <div class="info-label">Account Type</div>
              <div class="info-value"><?php echo ucfirst($_SESSION['customer_role']); ?></div>
            </div>

            <?php if($_SESSION['customer_role'] == 'admin') { ?>
              <a href="admin/login.php" class="btn btn-profile btn-admin" style="margin-top: 10px;">
                <i class="fas fa-shield-alt"></i> Access Admin Panel
              </a>
            <?php } ?>
          </div>
        </div>
      </div>

      <!-- Orders Card -->
      <div class="col-md-8">
        <div class="profile-card">
          <div class="card-header">
            <h5 class="card-title">
              <i class="fas fa-shopping-bag"></i> My Recent Orders
            </h5>
          </div>
          <div class="card-body">
            <div class="order-history">
              <?php
              // Here we would normally fetch recent orders
              // For demonstration, showing placeholder content
              ?>
              <div class="table-responsive">
                <table class="order-table">
                  <thead>
                    <tr>
                      <th>Order #</th>
                      <th>Date</th>
                      <th>Items</th>
                      <th>Total</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // This would normally come from a database query
                    // Showing placeholder data
                    $demoOrders = [
                      ['id' => '10051', 'date' => '2023-06-05', 'items' => '3', 'total' => '$459.99', 'status' => 'delivered'],
                      ['id' => '10042', 'date' => '2023-05-22', 'items' => '1', 'total' => '$129.99', 'status' => 'shipped'],
                      ['id' => '10038', 'date' => '2023-05-14', 'items' => '2', 'total' => '$269.95', 'status' => 'processing']
                    ];

                    foreach($demoOrders as $order) {
                      echo "<tr>
                        <td>#" . $order['id'] . "</td>
                        <td>" . $order['date'] . "</td>
                        <td>" . $order['items'] . "</td>
                        <td>" . $order['total'] . "</td>
                        <td><span class='order-badge badge-" . $order['status'] . "'>" . ucfirst($order['status']) . "</span></td>
                      </tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
            <a href="orders.php" class="btn btn-profile" style="margin-top: 20px;">
              <i class="fas fa-history"></i> View Complete Order History
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
// Display success message if exists
if(isset($_SESSION['success_message'])) {
   echo '<div class="alert alert-success" role="alert">' . $_SESSION['success_message'] . '</div>';
   // Clear the message after displaying it
   unset($_SESSION['success_message']);
}

// Display error message if exists
if(isset($_SESSION['error_message'])) {
   echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error_message'] . '</div>';
   // Clear the message after displaying it
   unset($_SESSION['error_message']);
}
?>
</div>
</main>

<?php include_once('./includes/footer.php') ?>
<!-- Placed at the end of the document so the pages load faster ============================================= -->
<script src="./js/bootstrap.min.js" type="text/javascript"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Edit button click handlers
  document.querySelectorAll('.edit-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
      const targetId = this.getAttribute('data-target');
      
      // First close any other open edit forms
      document.querySelectorAll('.info-edit.show').forEach(function(form) {
        if(form.id !== targetId) {
          form.classList.remove('show');
        }
      });
      
      // Then toggle this form
      document.getElementById(targetId).classList.toggle('show');
    });
  });
  
  // Cancel button click handlers
  document.querySelectorAll('.btn-cancel').forEach(function(btn) {
    btn.addEventListener('click', function() {
      const targetId = this.getAttribute('data-target');
      document.getElementById(targetId).classList.remove('show');
    });
  });
});
</script>
