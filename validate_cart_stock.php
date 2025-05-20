<?php
// Check if session is not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include necessary files
require_once('./includes/db_connection.php');
require_once('./includes/db_procedures.php');

// Set content type to JSON
header('Content-Type: application/json');

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => false, 'error_info' => 'Invalid request method']);
    exit();
}

// Decode JSON request body
$input = json_decode(file_get_contents('php://input'), true);

// Check if cart is empty
if (!isset($_SESSION['mycart']) || empty($_SESSION['mycart'])) {
    echo json_encode(['status' => false, 'error_info' => 'Your cart is empty']);
    exit();
}

// Validate stock availability using the function we created
$stockCheck = checkCartStockAvailability($_SESSION['mycart']);

// Return the result as JSON
echo json_encode($stockCheck);
exit(); 