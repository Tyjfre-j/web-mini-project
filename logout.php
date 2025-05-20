<?php
// Check if session is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
   
$logged_out = false;
   
// Check if user is logged in
if(isset($_SESSION['id'])) {
   session_unset();
   session_destroy();
   $logged_out = true;
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
    <title>Logout - PeakGear</title>
    <style>
        .countdown-container {
            margin: 25px auto;
            position: relative;
            width: 80px;
            height: 80px;
        }
        
        .countdown-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: conic-gradient(var(--primary-color) 0deg, #f0f0f0 0deg);
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: background 1s linear;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        .countdown-inner {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 24px;
            font-weight: bold;
            color: var(--primary-color);
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .auth-btn-container {
            margin-top: 30px;
        }
        
        .auth-success-message, .auth-error-message {
            padding: 18px;
            border-radius: 10px;
            margin-top: 20px;
        }
        
        .logout-message {
            text-align: center;
            margin: 15px 0;
            color: #555;
            line-height: 1.5;
        }
    </style>
</head>
<body class="auth-body">
    <div class="auth-container">
        <div class="auth-logo-box">
            <h1 class="auth-site-title">PeakGear</h1>
            <?php if($logged_out): ?>
                <h3 class="auth-form-title">Successfully Logged Out</h3>
                <div class="auth-divider"></div>
                <div class="auth-success-message fade-in">
                    <i class="fas fa-check-circle" style="margin-right: 10px;"></i>
                    You have been successfully logged out
                </div>
            <?php else: ?>
                <h3 class="auth-form-title">Already Logged Out</h3>
                <div class="auth-divider"></div>
                <div class="auth-error-message fade-in">
                    <i class="fas fa-info-circle" style="margin-right: 10px;"></i>
                    You are not currently logged in
                </div>
            <?php endif; ?>
            
            <div class="countdown-container">
                <div class="countdown-circle" id="countdown-circle">
                    <div class="countdown-inner">
                        <span id="countdown">5</span>
                    </div>
                </div>
            </div>
            
            <p class="logout-message fade-in">
                You will be redirected to the homepage in <span id="countdown-text">5</span> seconds
            </p>
        </div>
        
        <div class="auth-btn-container fade-in">
            <a href="index.php" class="auth-btn-primary">
                <i class="fas fa-home" style="margin-right: 10px;"></i> Go to Homepage
            </a>
            <a href="login.php" class="auth-btn-secondary">
                <i class="fas fa-sign-in-alt" style="margin-right: 10px;"></i> Sign In
            </a>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let count = 5;
            const countdownElement = document.getElementById('countdown');
            const countdownTextElement = document.getElementById('countdown-text');
            const countdownCircle = document.getElementById('countdown-circle');
            
            const interval = setInterval(function() {
                count--;
                
                if (count < 0) {
                    clearInterval(interval);
                    window.location.href = 'index.php';
                    return;
                }
                
                // Update the countdown text
                countdownElement.textContent = count;
                countdownTextElement.textContent = count;
                
                // Update the circle progress
                const degrees = (5 - count) / 5 * 360;
                countdownCircle.style.background = `conic-gradient(var(--primary-color) ${degrees}deg, #f0f0f0 0deg)`;
                
            }, 1000);
        });
    </script>
</body>
</html>