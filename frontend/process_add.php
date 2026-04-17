<?php
require_once '../includes/database_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn->begin_transaction(); // Ensure data integrity

    try {
        $total = (int)$_POST['prep'] + (int)$_POST['cook'];
        $sql = "INSERT INTO Recipe (RecipeName, PrepTime, CookTime, TotalTime, Servings) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siiii", $_POST['name'], $_POST['prep'], $_POST['cook'], $total, $_POST['servings']);
        $stmt->execute();
        $recipeID = $conn->insert_id;

        // Steps Insertion
        if (!empty($_POST['steps'])) {
            $steps = explode("\n", str_replace("\r", "", $_POST['steps']));
            $stmtStep = $conn->prepare("INSERT INTO RecipeDirections (RecipeID, StepNumber, Instruction) VALUES (?, ?, ?)");
            foreach ($steps as $idx => $txt) {
                if (trim($txt)) {
                    $num = $idx + 1;
                    $trimmed = trim($txt);
                    $stmtStep->bind_param("iis", $recipeID, $num, $trimmed);
                    $stmtStep->execute();
                }
            }
        }

        // Ingredients Link
        if (!empty($_POST['ingredients'])) {
            $stmtIng = $conn->prepare("INSERT INTO Contains (RecipeID, IngredientID, Quantity) VALUES (?, ?, 'To taste')");
            foreach ($_POST['ingredients'] as $ingID) {
                $stmtIng->bind_param("ii", $recipeID, $ingID);
                $stmtIng->execute();
            }
        }

        $conn->commit();
        header("Location: recipes.php");
    } catch (Exception $e) {
        $conn->rollback();
        die("Save failed: " . $e->getMessage());
    }
}
