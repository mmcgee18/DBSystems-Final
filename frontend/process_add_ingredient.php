<?php
require_once '../includes/database_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "INSERT INTO Ingredient (FoodItem, Category, Calories, Protein, Carbohydrates, Fat, Fiber, Sugars, MealType) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdddddds", 
        $_POST['FoodItem'], $_POST['Category'], $_POST['Calories'], 
        $_POST['Protein'], $_POST['Carbohydrates'], $_POST['Fat'], 
        $_POST['Fiber'], $_POST['Sugars'], $_POST['MealType']
    );

    if ($stmt->execute()) {
        header("Location: ingredient.php?status=success");
    } else {
        die("Error: " . $conn->error);
    }
}
