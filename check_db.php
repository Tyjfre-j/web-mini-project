<?php
include_once 'includes/config.php';

// Check laptop table structure
echo "<h2>Laptop Table Structure</h2>";
$query = "DESCRIBE `Laptops`";
$result = mysqli_query($conn, $query);
echo "<table border='1'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>{$row['Field']}</td>";
    echo "<td>{$row['Type']}</td>";
    echo "<td>{$row['Null']}</td>";
    echo "<td>{$row['Key']}</td>";
    echo "<td>{$row['Default']}</td>";
    echo "<td>{$row['Extra']}</td>";
    echo "</tr>";
}
echo "</table>";

// Check sample data from laptop table
echo "<h2>Sample Laptop Data</h2>";
$query = "SELECT * FROM `Laptops` LIMIT 2";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    echo "<pre>";
    print_r($row);
    echo "</pre>";
} else {
    echo "No laptop data found.";
}

// Check if small description exists
echo "<h2>Count of Null/Empty Small Descriptions</h2>";
$query = "SELECT COUNT(*) as count FROM `Laptops` WHERE `Laptops_small_description` IS NULL OR `Laptops_small_description` = ''";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
echo "Laptops with missing descriptions: {$row['count']}";

// Check if name exists
echo "<h2>Count of Null/Empty Names</h2>";
$query = "SELECT COUNT(*) as count FROM `Laptops` WHERE `Laptops_name` IS NULL OR `Laptops_name` = ''";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
echo "Laptops with missing names: {$row['count']}"; 