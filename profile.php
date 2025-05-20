<?php
   include_once('./includes/headerNav.php');
   //this restriction will secure the pages path injection
   if(!(isset($_SESSION['id']))){
      header("location:index.php?UnathorizedUser");
     }
    $sql8 ="SELECT * FROM  customer WHERE customer_id='{$_SESSION['id']}';";
    $result8 = $conn->query($sql8);
    $row8 = $result8->fetch_assoc();
    $_SESSION['customer_name'] = $row8['customer_fname'];
    $_SESSION['customer_email'] = $row8['customer_email'];
    $_SESSION['customer_phone'] = $row8['customer_phone'];
    $_SESSION['customer_address'] = $row8['customer_address'];
    $conn->close();
?>
<head>
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
      integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>My Profile - PeakGear</title>
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
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, var(--gray-light), var(--gray-medium));
        position: relative;
      }
      
      body::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 280px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        z-index: -1;
      }
      
      .profile-header {
        text-align: center;
        padding: 30px 20px 40px;
        color: var(--text-light);
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
      }
      
      .header-content {
        background-color: rgba(0, 0, 0, 0.4);
        padding: 25px;
        border-radius: 12px;
        max-width: 700px;
        margin: 0 auto;
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
        margin: 0 auto 50px;
        padding: 0 20px;
      }
      
      .card-container {
        background: transparent;
        border: none;
        padding: 0;
      }
      
      .profile-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        height: 100%;
        border: none;
      }
      
      .profile-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
      }
      
      .card-header {
        padding: 20px 25px;
        background: linear-gradient(to right, rgba(58, 134, 255, 0.05), rgba(131, 56, 236, 0.05));
        border-bottom: 1px solid var(--gray-medium);
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
      }
      
      .info-label {
        font-size: 0.85rem;
        color: var(--gray-dark);
        margin-bottom: 5px;
      }
      
      .info-value {
        font-size: 1.1rem;
        color: var(--text-dark);
        word-break: break-word;
      }
      
      .btn-profile {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
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
        background: linear-gradient(135deg, var(--primary-dark), var(--secondary-color));
        transform: translateY(-2px);
        color: white;
        box-shadow: 0 5px 15px rgba(58, 134, 255, 0.3);
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
        background: linear-gradient(135deg, #ff9e00, #ff4e00);
      }
      
      .btn-admin:hover {
        background: linear-gradient(135deg, #ff9e00, #ff4e00);
      }
      
      .form-control {
        padding: 12px 15px;
        border: 1px solid var(--gray-medium);
        border-radius: 8px;
        transition: all 0.3s ease;
        font-size: 1rem;
        margin-bottom: 15px;
      }
      
      .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(58, 134, 255, 0.2);
      }
      
      .alert {
        border-radius: 8px;
        padding: 15px;
        margin: 20px 0;
        font-weight: 500;
        text-align: center;
      }
      
      .alert-success {
        background-color: rgba(56, 176, 0, 0.15);
        border: 1px solid rgba(56, 176, 0, 0.3);
        color: var(--success-color);
      }
      
      .profile_edit, .address_edit, .contact_edit {
        display: none;
        margin-top: 20px;
      }
      
      .show {
        display: block;
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
      }
    </style>
</head>
<header>
  <!-- top head action, search etc in php -->
  <!-- inc/topheadactions.php -->
  <?php require_once './includes/topheadactions.php'; ?>
  <!-- desktop navigation -->
  <!-- inc/desktopnav.php -->
  <?php require_once './includes/desktopnav.php' ?>
  <!-- mobile nav in php -->
  <!-- inc/mobilenav.php -->
  <?php require_once './includes/mobilenav.php'; ?>
</header>
<hr>
<!-- Header End====================================================================== -->

<div class="profile-header">
  <div class="header-content">
    <h1>
      Welcome, <?php echo $_SESSION['customer_name']; ?>
      <span id="role"><?php echo ($_SESSION['customer_role']=='admin') ? 'Admin' : 'Member'; ?></span>
    </h1>
    <p>Manage your personal information and track your orders</p>
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
            <div class="info-group">
              <div class="info-label">Full Name</div>
              <div class="info-value"><?php echo $_SESSION['customer_name']; ?></div>
            </div>
            <div class="info-group">
              <div class="info-label">Email Address</div>
              <div class="info-value"><?php echo $_SESSION['customer_email']; ?></div>
            </div>
            <div class="info-group">
              <div class="info-label">Account Type</div>
              <div class="info-value"><?php echo ucfirst($_SESSION['customer_role']); ?></div>
            </div>

            <a href="#/profile/edit" id="edit_link1" class="btn btn-profile">
              <i class="fas fa-pencil-alt"></i> Edit Profile
            </a>
            
            <?php if($_SESSION['customer_role'] == 'admin') { ?>
              <a href="admin/login.php" class="btn btn-profile btn-admin" style="margin-top: 10px;">
                <i class="fas fa-shield-alt"></i> Access Admin Panel
              </a>
            <?php } ?>

            <!-- Profile Edit Form -->
            <div class="profile_edit">
              <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input
                  type="text" 
                  name="name" 
                  class="form-control"
                  placeholder="New Name..."
                />
                <input
                  type="email" 
                  name="email" 
                  class="form-control"
                  placeholder="New Email..."
                />
                <button 
                  type="submit" 
                  name="save" 
                  class="btn btn-profile">
                  <i class="fas fa-save"></i> Save Changes
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Address Card -->
      <div class="col-md-4">
        <div class="profile-card">
          <div class="card-header">
            <h5 class="card-title">
              <i class="fas fa-map-marker-alt"></i> Address Information
            </h5>
          </div>
          <div class="card-body">
            <div class="info-group">
              <div class="info-label">Shipping Address</div>
              <div class="info-value">
                <?php 
                  echo !empty($_SESSION['customer_address']) ? $_SESSION['customer_address'] : '<span style="color: var(--gray-dark); font-style: italic;">No address added yet</span>';
                ?>
              </div>
            </div>

            <a href="#/address/edit" id="edit_link2" class="btn btn-profile">
              <i class="fas fa-pencil-alt"></i> Update Address
            </a>

            <!-- Address Edit Form -->
            <div class="address_edit">
              <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <textarea
                  name="address" 
                  class="form-control"
                  placeholder="Enter your full address..."
                  rows="3"
                ></textarea>
                <button 
                  type="submit" 
                  name="save" 
                  class="btn btn-profile">
                  <i class="fas fa-save"></i> Save Address
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Contact Card -->
      <div class="col-md-4">
        <div class="profile-card">
          <div class="card-header">
            <h5 class="card-title">
              <i class="fas fa-phone-alt"></i> Contact Information
            </h5>
          </div>
          <div class="card-body">
            <div class="info-group">
              <div class="info-label">Phone Number</div>
              <div class="info-value">
                <?php 
                  echo !empty($_SESSION['customer_phone']) ? $_SESSION['customer_phone'] : '<span style="color: var(--gray-dark); font-style: italic;">No phone number added</span>';
                ?>
              </div>
            </div>

            <a href="#/contact/edit" id="edit_link3" class="btn btn-profile">
              <i class="fas fa-pencil-alt"></i> Update Phone Number
            </a>

            <!-- Contact Edit Form -->
            <div class="contact_edit">
              <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input
                  type="tel" 
                  name="number" 
                  class="form-control"
                  placeholder="New Phone Number..."
                />
                <button 
                  type="submit" 
                  name="save" 
                  class="btn btn-profile">
                  <i class="fas fa-save"></i> Save Phone Number
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Orders Card -->
      <div class="col-md-12">
        <div class="profile-card">
          <div class="card-header">
            <h5 class="card-title">
              <i class="fas fa-shopping-bag"></i> My Orders
            </h5>
          </div>
          <div class="card-body">
            <p>View your order history, track shipments, and manage your purchases.</p>
            <a href="orders.php" class="btn btn-profile">
              <i class="fas fa-box-open"></i> View My Orders
            </a>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<?php
//for edit backend users data php and mysql
if(isset($_POST['save'])){
   $success_message = "";
   
   if(!empty($_POST['name']) AND !empty($_POST['email'])){
      include "includes/config.php";
      $sql6 = "UPDATE customer 
               SET  customer_fname= '{$_POST['name']}' ,
                    customer_email= '{$_POST['email']}' 
               WHERE customer_id= '{$_SESSION['id']}' ";
      if($conn->query($sql6)){
         $success_message = '<div class="alert alert-success" role="alert">Profile updated successfully!</div>';
         $_SESSION['customer_name'] = $_POST['name'];
         $_SESSION['customer_email'] = $_POST['email'];
      }   
      $conn->close();
   }
   
   if(!empty($_POST['address'])){
      include "includes/config.php";
      $sql6 = "UPDATE customer 
               SET  customer_address= '{$_POST['address']}'
               WHERE customer_id= '{$_SESSION['id']}' ";
      if($conn->query($sql6)){
         $success_message = '<div class="alert alert-success" role="alert">Address updated successfully!</div>';
         $_SESSION['customer_address'] = $_POST['address'];
      }   
      $conn->close();
   }
   
   if(!empty($_POST['number'])){
      include "includes/config.php";
      $sql6 = "UPDATE customer 
               SET  customer_phone= '{$_POST['number']}'
               WHERE customer_id= '{$_SESSION['id']}' ";
      if($conn->query($sql6)){
         $success_message = '<div class="alert alert-success" role="alert">Contact number updated successfully!</div>';
         $_SESSION['customer_phone'] = $_POST['number'];
      }   
      $conn->close();
   }
   
   // Display the success message
   if(!empty($success_message)){
      echo $success_message;
      // Refresh the page after 2 seconds to show updated information
      echo "<script>
            setTimeout(function(){
               window.location.href = '" . $_SERVER['PHP_SELF'] . "';
            }, 2000);
          </script>";
   }
}
?>

<?php include_once('./includes/footer.php') ?>
<!-- Placed at the end of the document so the pages load faster ============================================= -->
<script src="./js/bootstrap.min.js" type="text/javascript"></script>
<script src="./js/edit.js"></script>
