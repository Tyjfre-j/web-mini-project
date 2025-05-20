<?php
// Script to install database triggers and stored procedures

// Database connection
include('./includes/dbconnection.php');

// Clear screen
echo '<pre>';
echo "=============================================\n";
echo "Database Triggers and Procedures Installation\n";
echo "=============================================\n\n";

// Function to execute SQL statement
function executeSql($conn, $sql, $description) {
    echo "Installing: $description... ";
    try {
        $conn->exec($sql);
        echo "SUCCESS\n";
        return true;
    } catch (PDOException $e) {
        echo "ERROR\n";
        echo "Error message: " . $e->getMessage() . "\n\n";
        return false;
    }
}

// Read the SQL from the file
try {
    $sqlFile = file_get_contents('./database/triggers_procedures.sql');
    
    // Split the SQL file by DELIMITER statements
    $sqlParts = preg_split('/DELIMITER \/\/|DELIMITER ;/', $sqlFile);
    
    // Process each part
    foreach($sqlParts as $part) {
        if(empty(trim($part))) continue;
        
        // For procedures/triggers (with //)
        if(strpos($part, 'END //') !== false) {
            // Replace the ending
            $sql = str_replace('END //', 'END', trim($part));
            
            // Try to identify what we're installing
            $description = "Unknown procedure or trigger";
            
            if(preg_match('/CREATE PROCEDURE ([^ (]+)/', $sql, $matches)) {
                $description = "Stored procedure " . $matches[1];
            } elseif(preg_match('/CREATE TRIGGER ([^ (]+)/', $sql, $matches)) {
                $description = "Trigger " . $matches[1];
            }
            
            executeSql($conn, $sql, $description);
        } 
        // For table creation or other statements
        else {
            // Split by semicolons
            $statements = explode(';', $part);
            
            foreach($statements as $statement) {
                $statement = trim($statement);
                if(empty($statement)) continue;
                
                $description = "Standard SQL statement";
                
                if(preg_match('/CREATE TABLE ([^ (]+)/', $statement, $matches)) {
                    $description = "Table " . $matches[1];
                }
                
                executeSql($conn, $statement, $description);
            }
        }
    }
    
    echo "\nInstallation completed!\n";
    echo "You can now access the schema information page to see the database schema and procedures.\n";
    
} catch (Exception $e) {
    echo "Error reading SQL file: " . $e->getMessage() . "\n";
}

echo '</pre>';

// Add link back to home
echo '<p><a href="index.php" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #CE5959; color: white; text-decoration: none; border-radius: 5px;">Return to Home</a></p>';
?> 