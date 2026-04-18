<?php
session_start();
require_once '../includes/database_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    // Normalize WeekOf to Monday of its week for consistency
    $raw_week = $_POST['week_of'];
    $date_obj = new DateTime($raw_week);
    $date_obj->modify('monday this week');
    $normalized_week = $date_obj->format('Y-m-d');
    
    $plan_data = $_POST['plan']; // Structured as [day_num][meal_type]

    $conn->begin_transaction();
    try {
        // Prepare statement for bulk upsert
        $sql = "INSERT INTO MealPlans (UserID, RecipeID, WeekOf, DayOfWeek, MealType) 
                VALUES (?, ?, ?, ?, ?) 
                ON DUPLICATE KEY UPDATE RecipeID = VALUES(RecipeID)";
        $stmt = $conn->prepare($sql);

        foreach ($plan_data as $day => $meals) {
            foreach ($meals as $type => $recipe_id) {
                if (!empty($recipe_id)) {
                    $r_id = (int)$recipe_id;
                    $day_num = (int)$day;
                    $stmt->bind_param("iisis", $user_id, $r_id, $normalized_week, $day_num, $type);
                    $stmt->execute();
                } else {
                    // Optional: Clean up slot if user chose "-- Skip --"
                    $del_stmt = $conn->prepare("DELETE FROM MealPlans WHERE UserID = ? AND WeekOf = ? AND DayOfWeek = ? AND MealType = ?");
                    $day_num = (int)$day;
                    $del_stmt->bind_param("isis", $user_id, $normalized_week, $day_num, $type);
                    $del_stmt->execute();
                }
            }
        }
        $conn->commit();
        header("Location: meal_plans.php?week_of=$normalized_week&saved=1");
    } catch (Exception $e) {
        $conn->rollback();
        die("Save error: " . $e->getMessage());
    }
}
