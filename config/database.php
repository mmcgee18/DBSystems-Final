<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'culinarycompass'); // ENSURE THIS MATCHES YOUR ACTUAL DATABASE NAME

// Create connection
if (!function_exists('get_db_connection')) {
    function get_db_connection() {
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        // Check connection
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }
        return $mysqli;
    }
}
?>
