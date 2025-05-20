<?php include_once('./includes/headerNav.php'); ?>

<!-- Add custom styling for the contact page -->
<style>
  main {
    margin-top: 10px !important;
    padding-top: 10px !important;
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
  }
  
  .product-container {
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
  }
</style>

<div class="overlay" data-overlay></div>
<!--
    - HEADER
  -->

<!-- get tables data from db -->

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

<!--
    - MAIN
  -->

<main>

  <div class="product-container">
    <div class="container">
      <!--
          - SIDEBAR
        -->
      <!-- CATEGORY SIDE BAR MOBILE MENU -->
      <?php require_once './includes/categorysidebar.php' ?>
      <!-- ############################# -->

      <div class="product-box">
        <!-- get id and url for each category and display its dat from table her in this secton -->
        <div class="product-main">

          <!-- contact cards -->
          <!-- MAIL -->
          <div class="contact-card-container mail">
            <div class="contact-icon">
              <a href="#">
                <ion-icon class="contact-icons mail-icon" name="mail-outline"></ion-icon>
              </a>
            </div>
            <div class="contact-details">
              <contact-title>
                <h2>Mail</h2>
              </contact-title>
              <p>
                <a href="mailto:<?php echo($site_info_email) ?>"><?php echo($site_info_email) ?></a>
              </p>
            </div>
          </div>
          <!--  -->

          <!-- Whatsapp -->
          <div class="contact-card-container whatsapp">
            <div class="contact-icon">
              <a href="#">
                <ion-icon class="contact-icons whatsapp-icon" name="logo-whatsapp"></ion-icon>
              </a>
            </div>
            <div class="contact-details">
              <contact-title>
                <h2>Whatsapp</h2>
              </contact-title>
              <p>
                <a href="#"><?php echo($site_contact_num) ?></a>
              </p>
            </div>
          </div>
          <!--  -->

          <!-- Location -->
          <div class="contact-card-container location">
            <div class="contact-icon">
              <a href="#">
                <ion-icon class="contact-icons location-icon" name="location-outline"></ion-icon>
              </a>
            </div>
            <div class="contact-details">
              <contact-title>
                <h2>Location</h2>
              </contact-title>
              <p>
              <?php echo($site_address) ?>
              </p>
            </div>
          </div>
          <!--  -->


        </div>

            <!-- Map -->
    	<div class="row">
	<div class="span12">
  <iframe 
  style="width:100%; height:300px; border: 0;" 
  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2606.4043633769624!2d3.0297084!3d36.7691496!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x128fb23c60a4d5a7%3A0x2a7cdb377ea6b048!2sRue%20Smail%20BENMOUNA%2C%20El%20Biar!5e0!3m2!1sen!2sdz!4v1694283135453!5m2!1sen!2sdz"
  allowfullscreen
  loading="lazy">
</iframe>

    <br />
	<small>
    <a href="https://www.google.co.uk/maps/place/Rue+Smail+BENMOUNA,+El+Biar/@36.7691496,3.0297084,19z/data=!3m1!4b1!4m6!3m5!1s0x128fb23c60a4d5a7:0x2a7cdb377ea6b048!8m2!3d36.7691496!4d3.0303535!16s%2Fg%2F11g6498lvy?entry=ttu&g_ep=EgoyMDI1MDEwMi4wIKXMDSoJLDEwMjExMjM0SAFQAw%3D%3D">
    View Larger Map
    </a>
  </small>
	</div>
	</div>


      </div>
    </div>





  </div>

  <!--
      - TESTIMONIALS, CTA & SERVICE
    -->

  <!--
      - BLOG
    -->

<script src="./js/jquery.js" type="text/javascript"></script>
<script src="./js/bootstrap.min.js" type="text/javascript"></script>

</main>

<?php require_once './includes/footer.php'; ?>