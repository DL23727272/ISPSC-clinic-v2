<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'isps_clinica';


// $host = 'localhost';
// $username = 'u227484579_clinic23';
// $password = 'dlGamoso23';
// $database = 'u227484579_clinicTest123';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4
$conn->set_charset("utf8mb4");

// Function to sanitize input data
function sanitize_input($data) {
    global $conn;
    return htmlspecialchars(strip_tags(trim($conn->real_escape_string($data))));
}

// Password hashing verification function
function verify_password($input_password, $hashed_password) {
    return password_verify($input_password, $hashed_password);
}
?>