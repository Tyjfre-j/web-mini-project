<?php
// Script to check if stored procedures are installed

// Database connection
include('./includes/dbconnection.php');

// Clear screen
echo '<pre>';
echo "=============================================\n";
echo "Database Procedures Check\n";
echo "=============================================\n\n";

try {
    // Check for all procedures
    $procedures = [
        'GetOrderDetails',
        'FinalizeOrder',
        'GetCustomerOrderHistory'
    ];
    
    $triggers = [
        'before_order_items_insert',
        'after_order_items_insert',
        'after_order_status_update'
    ];
    
    echo "Checking procedures...\n";
    $stmt = $conn->prepare("SHOW PROCEDURE STATUS WHERE Db = ?");
    $db = 'site_database'; // Database name
    $stmt->execute([$db]);
    $installedProcedures = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $found = [];
    foreach ($installedProcedures as $proc) {
        $found[$proc['Name']] = true;
    }
    
    echo "\nProcedure Status:\n";
    echo "--------------------------------------------\n";
    foreach ($procedures as $procedure) {
        echo "{$procedure}: " . (isset($found[$procedure]) ? "INSTALLED" : "NOT INSTALLED") . "\n";
    }
    
    echo "\nChecking triggers...\n";
    $stmt = $conn->prepare("SHOW TRIGGERS FROM ?");
    $stmt->execute([$db]);
    $installedTriggers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $triggerFound = [];
    foreach ($installedTriggers as $trigger) {
        $triggerFound[$trigger['Trigger']] = true;
    }
    
    echo "\nTrigger Status:\n";
    echo "--------------------------------------------\n";
    foreach ($triggers as $trigger) {
        echo "{$trigger}: " . (isset($triggerFound[$trigger]) ? "INSTALLED" : "NOT INSTALLED") . "\n";
    }
    
    echo "\n\nIf any procedures or triggers are NOT INSTALLED, run install_triggers.php to install them.";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo '</pre>';

// Add link to install page
echo '<p><a href="install_triggers.php" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #3a86ff; color: white; text-decoration: none; border-radius: 8px; font-weight: 600;">Install Database Procedures</a></p>';

// Add link back to home
echo '<p><a href="index.php" style="display: inline-block; margin-top: 10px; padding: 10px 20px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 8px; font-weight: 600;">Return to Home</a></p>';
?> 