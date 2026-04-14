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
// Define a dynamic BASE_URL so links work whether app is in a subfolder
// (e.g. /sms) or served from root (e.g. localhost:8000)
if (!defined('BASE_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
    $base = $protocol . ($_SERVER['HTTP_HOST'] ?? 'localhost') . rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? '/'), '/\\') . '/';
    define('BASE_URL', $base);
}

// Example: echo BASE_URL; // http://localhost/sms/

if (!isset($conn) || !($conn instanceof mysqli)) {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Check connection.
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
}
// echo "Successfully connected...!";
