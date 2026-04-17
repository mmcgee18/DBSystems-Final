<?php
require_once '../includes/database_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Capture the ID and the new data
    $id = (int)$_POST['IngredientID'];
    
    $sql = "UPDATE Ingredient SET 
            FoodItem = ?, 
            Category = ?, 
            Calories = ?, 
            Protein = ?, 
            Carbohydrates = ?, 
            Fat = ?, 
            Fiber = ?, 
            Sugars = ?, 
            MealType = ? 
            WHERE IngredientID = ?";

    $stmt = $conn->prepare($sql);

    // 2. Bind parameters: 
    // "ssdddddds i" -> 2 strings, 6 decimals, 1 string, then the integer ID
    $stmt->bind_param("ssddddddsi", 
        $_POST['FoodItem'], 
        $_POST['Category'], 
        $_POST['Calories'], 
        $_POST['Protein'], 
        $_POST['Carbohydrates'], 
        $_POST['Fat'], 
        $_POST['Fiber'], 
        $_POST['Sugars'], 
        $_POST['MealType'],
        $id
    );

    // 3. Execute and redirect
    if ($stmt->execute()) {
        header("Location: ingredient.php?status=updated");
        exit();
    } else {
        die("Update failed: " . $conn->error);
    }
} else {
    header("Location: ingredient.php");
    exit();
}
