<?php
require_once '../includes/database_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['RecipeID'];
    $conn->begin_transaction();
    try {
        $total = (int)$_POST['prep'] + (int)$_POST['cook'];
        $sql = "UPDATE Recipe SET RecipeName=?, PrepTime=?, CookTime=?, TotalTime=?, Servings=?, Ratings=?, total_fat=?, protein=?, total_carb=? WHERE RecipeID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siiiiidddi", $_POST['name'], $_POST['prep'], $_POST['cook'], $total, $_POST['servings'], $_POST['rating'], $_POST['fat'], $_POST['protein'], $_POST['carb'], $id);
        $stmt->execute();

        // Refresh Steps
        $conn->query("DELETE FROM RecipeDirections WHERE RecipeID = $id");
        $steps = explode("\n", str_replace("\r", "", $_POST['steps']));
        $stmtStep = $conn->prepare("INSERT INTO RecipeDirections (RecipeID, StepNumber, Instruction) VALUES (?, ?, ?)");
        $c = 1;
        foreach ($steps as $s) {
            if (trim($s)) {
                $trimmed = trim($s);
                $stmtStep->bind_param("iis", $id, $c, $trimmed);
                $stmtStep->execute();
                $c++;
            }
        }

        $conn->commit();
        header("Location: view_recipe.php?id=$id&updated=1");
    } catch (Exception $e) {
        $conn->rollback();
        die("Error: " . $e->getMessage());
    }
}
