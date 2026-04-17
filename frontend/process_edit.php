<?php
require_once '../includes/database_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['RecipeID'];
    $conn->begin_transaction();

    try {
        // 1. Update Metadata
        $total = (int)$_POST['prep'] + (int)$_POST['cook'];
        $sql = "UPDATE Recipe SET RecipeName = ?, PrepTime = ?, CookTime = ?, TotalTime = ?, Servings = ? WHERE RecipeID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siiiii", $_POST['name'], $_POST['prep'], $_POST['cook'], $total, $_POST['servings'], $id);
        $stmt->execute();

        // 2. Refresh Directions (Delete then Insert)
        $conn->prepare("DELETE FROM RecipeDirections WHERE RecipeID = ?")->execute([$id]);
        $steps = explode("\n", str_replace("\r", "", $_POST['steps']));
        $stmtStep = $conn->prepare("INSERT INTO RecipeDirections (RecipeID, StepNumber, Instruction) VALUES (?, ?, ?)");
        $count = 1;
        foreach ($steps as $s) {
            if (trim($s)) {
                $trimmed = trim($s);
                $stmtStep->bind_param("iis", $id, $count++, $trimmed);
                $stmtStep->execute();
            }
        }

        // 3. Refresh Ingredients (Delete then Insert)
        $conn->prepare("DELETE FROM Contains WHERE RecipeID = ?")->execute([$id]);
        if (!empty($_POST['ingredients'])) {
            $stmtIng = $conn->prepare("INSERT INTO Contains (RecipeID, IngredientID, Quantity) VALUES (?, ?, 'To taste')");
            foreach ($_POST['ingredients'] as $ingID) {
                $stmtIng->bind_param("ii", $id, $ingID);
                $stmtIng->execute();
            }
        }

        $conn->commit();
        header("Location: view_recipe.php?id=$id&status=updated");
    } catch (Exception $e) {
        $conn->rollback();
        die("Update failed: " . $e->getMessage());
    }
}
