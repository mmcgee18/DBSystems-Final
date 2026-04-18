<?php
session_start();
require_once '../includes/database_functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_GET['week_of'])) {
    header('Location: index.php');
    exit();
}

$user_id = (int)$_SESSION['user_id'];
$raw_week = $_GET['week_of'];

// Normalize WeekOf to Monday to ensure we clear the correct standardized data
try {
    $date_obj = new DateTime($raw_week);
    $date_obj->modify('monday this week');
    $normalized_week = $date_obj->format('Y-m-d');

    // Perform the deletion for the specific user and week
    $sql = "DELETE FROM MealPlans WHERE UserID = ? AND WeekOf = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $normalized_week);
    
    if ($stmt->execute()) {
        // Redirect back to the planner for that week with a success message
        header("Location: meal_plans.php?week_of=$normalized_week&status=cleared");
        exit();
    } else {
        throw new Exception($conn->error);
    }

} catch (Exception $e) {
    die("Fatal Error Clearing Week: " . $e->getMessage());
}
?>
