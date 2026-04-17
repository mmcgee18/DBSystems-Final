<?php
require_once '../config/database.php';

function register_user($firstname, $lastname, $username, $password, $email, $height, $weight, $age, $budget) {
    $mysqli = get_db_connection();

    // Check if username OR email exists
    $check_stmt = $mysqli->prepare("SELECT UserID FROM Users WHERE Username = ? OR Email = ?");
    $check_stmt->bind_param("ss", $username, $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $check_stmt->close();
        $mysqli->close();
        return false; 
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Updated INSERT to match new table schema
    $sql = "INSERT INTO Users (FirstName, LastName, Username, Password, Email, Height, Weight, Age, WeeklyBudget) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    
    // "sssssddid" -> string x5, double x2, int x1, double x1
    $stmt->bind_param("sssssddid", $firstname, $lastname, $username, $hashed_password, $email, $height, $weight, $age, $budget);
    
    $success = $stmt->execute();
    $stmt->close();
    $mysqli->close();
    return $success;
}

function login_user($username, $password) {
    $mysqli = get_db_connection();
    // Table name changed to "Users" (capitalized)
    $stmt = $mysqli->prepare("SELECT UserID, Password FROM Users WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password);

    if ($stmt->fetch() && password_verify($password, $hashed_password)) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;
        $stmt->close();
        $mysqli->close();
        return true;
    }
    $stmt->close();
    $mysqli->close();
    return false;
}

function is_logged_in() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    return isset($_SESSION['user_id']);
}

function logout() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    session_destroy();
}
?>
