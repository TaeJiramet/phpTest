<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "marathon_registration";

// First connect without specifying database
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    // Database created or already exists
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Select the database
$conn->select_db($dbname);
$conn->set_charset("utf8");

// Create runners table if it doesn't exist
$createTable = "CREATE TABLE IF NOT EXISTS runners (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    gender ENUM('ชาย', 'หญิง') NOT NULL,
    birth_date DATE NOT NULL,
    emergency_contact VARCHAR(100) NOT NULL,
    emergency_phone VARCHAR(20) NOT NULL,
    tshirt_size ENUM('S', 'M', 'L', 'XL', 'XXL') NOT NULL,
    distance ENUM('Full Marathon (42km)', 'Half Marathon (21km)', 'Fun Run (5km)') NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($createTable) === TRUE) {
    // Table created or already exists
} else {
    echo "Error creating members table: " . $conn->error . "<br>";
}
?>