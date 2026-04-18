<?php 
require_once '../includes/database_functions.php';
$id = (int)$_GET['id'];

$recipe = $conn->query("SELECT * FROM Recipe WHERE RecipeID = $id")->fetch_assoc();
$stepsRes = $conn->query("SELECT Instruction FROM RecipeDirections WHERE RecipeID = $id ORDER BY StepNumber ASC");
$stepsArray = [];
while($s = $stepsRes->fetch_assoc()) $stepsArray[] = $s['Instruction'];
$stepsText = implode("\n", $stepsArray);

$currentIngsRes = $conn->query("SELECT IngredientID FROM Contains WHERE RecipeID = $id");
$currentIngs = [];
while($ing = $currentIngsRes->fetch_assoc()) $currentIngs[] = $ing['IngredientID'];

$allIngredients = $conn->query("SELECT * FROM Ingredient ORDER BY FoodItem ASC");
?>
<!DOCTYPE html>
<html>
<head><link rel="stylesheet" href="style.css"></head>
<body class="container">
    <h1>Edit: <?= htmlspecialchars($recipe['RecipeName']) ?></h1>
    <form action="process_edit.php" method="POST" class="card">
        <input type="hidden" name="RecipeID" value="<?= $id ?>">
        
        <label>Recipe Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($recipe['RecipeName']) ?>" required>
        
        <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:10px;">
            <input type="number" name="prep" value="<?= $recipe['PrepTime'] ?>" placeholder="Prep">
            <input type="number" name="cook" value="<?= $recipe['CookTime'] ?>" placeholder="Cook">
            <input type="number" name="servings" value="<?= $recipe['Servings'] ?>" placeholder="Servings">
        </div>

        <h4>Nutritional Metadata</h4>
        <div style="display:grid; grid-template-columns: repeat(4, 1fr); gap:10px;">
            <input type="number" step="0.01" name="fat" value="<?= $recipe['total_fat'] ?>" placeholder="Fat">
            <input type="number" step="0.01" name="protein" value="<?= $recipe['protein'] ?>" placeholder="Protein">
            <input type="number" step="0.01" name="carb" value="<?= $recipe['total_carb'] ?>" placeholder="Carbs">
            <input type="number" step="0.1" name="rating" value="<?= $recipe['Ratings'] ?>" placeholder="Rating">
        </div>

        <label>Instructions</label>
        <textarea name="steps" rows="6"><?= htmlspecialchars($stepsText) ?></textarea>

        <label>Ingredients</label>
        <select name="ingredients[]" multiple style="height:150px;">
            <?php while($ing = $allIngredients->fetch_assoc()): ?>
                <option value="<?= $ing['IngredientID'] ?>" <?= in_array($ing['IngredientID'], $currentIngs) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($ing['FoodItem']) ?>
                </option>
            <?php endwhile; ?>
        </select>
        
        <button type="submit" class="btn btn-primary" style="width:100%; margin-top:20px;">Update Recipe</button>
    </form>
</body>
</html>
