<?php
// Require the Composer autoload file
require __DIR__ . '/../vendor/autoload.php'; // Go up one level to the root directory

// Load the .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..'); // Go up one level to the root directory
$dotenv->load();

// Access environment variables
$host = $_ENV['DB_HOST'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];
$database = $_ENV['DB_NAME'];

// Create a new MySQLi connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>