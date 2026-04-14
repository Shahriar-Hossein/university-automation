<?php
// echo "Welcome to the Student database (smsdb)";
// DB credentials.
// for windows with laragon
// define('DB_HOST', 'localhost');

// for ubuntu serving with : php -S 127.0.0.1:8000
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'smsdb');

// Establish database connection if not already created.
if (!isset($conn) || !($conn instanceof mysqli)) {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Check connection.
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
}
// echo "Successfully connected...!";
