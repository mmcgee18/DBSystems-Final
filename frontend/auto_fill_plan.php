<?php
session_start();
require_once '../includes/database_functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$raw_week = $_GET['week_of'];

// Normalize WeekOf to Monday for consistency
$date_obj = new DateTime($raw_week);
$date_obj->modify('monday this week');
$normalized_week = $date_obj->format('Y-m-d');

$days = [1, 2, 3, 4, 5, 6, 7];
$meals = ['Breakfast', 'Lunch', 'Dinner', 'Snack'];

$conn->begin_transaction();

try {
    // 1. Get all available recipe IDs
    $recipe_res = $conn->query("SELECT RecipeID FROM Recipe");
    $recipe_ids = [];
    while($row = $recipe_res->fetch_assoc()) {
        $recipe_ids[] = $row['RecipeID'];
    }

    if (empty($recipe_ids)) {
        throw new Exception("No recipes found in your library to auto-fill.");
    }

    // 2. Prepare the upsert statement
    // We use INSERT IGNORE if you only want to fill EMPTY slots
    // Use ON DUPLICATE KEY UPDATE if you want to replace everything
    $sql = "INSERT INTO MealPlans (UserID, RecipeID, WeekOf, DayOfWeek, MealType) 
            VALUES (?, ?, ?, ?, ?) 
            ON DUPLICATE KEY UPDATE RecipeID = RecipeID"; // This keeps current choices

    $stmt = $conn->prepare($sql);

    foreach ($days as $day) {
        foreach ($meals as $type) {
            // Pick a random recipe ID from your list
            $random_recipe = $recipe_ids[array_rand($recipe_ids)];
            
            $stmt->bind_param("iisis", $user_id, $random_recipe, $normalized_week, $day, $type);
            $stmt->execute();
        }
    }

    $conn->commit();
    header("Location: meal_plans.php?week_of=$normalized_week&autofilled=1");

} catch (Exception $e) {
    $conn->rollback();
    die("Auto-fill Error: " . $e->getMessage());
}
