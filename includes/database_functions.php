<?php
require_once '../config/database.php';

// FIX 1: Call the function from database.php to create the connection object
// This creates the $conn variable used in your ingredient.php and recipes.php
$conn = get_db_connection();

// FIX 2: Removed the redundant/broken get_db_connection wrapper that caused recursion
// The function already exists in database.php, we don't need to redeclare it here.

if (!function_exists('sanitize_input')) {
    function sanitize_input($data) {
        global $conn; // Allow access to the connection for mysqli_real_escape_string
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        // It's better to use mysqli_real_escape_string if you're using mysqli
        return mysqli_real_escape_string($conn, $data);
    }
}

function validate_year($year) {
    return filter_var($year, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1900, 'max_range' => date('Y'))));
}

function validate_age($age) {
    return filter_var($age, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1, 'max_range' => 120)));
}
?>
