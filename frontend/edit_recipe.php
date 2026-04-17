<?php 
require_once '../includes/database_functions.php';
$id = (int)$_GET['id'];

// 1. Fetch Recipe Metadata
$stmt = $conn->prepare("SELECT * FROM Recipe WHERE RecipeID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$recipe = $stmt->get_result()->fetch_assoc();

// 2. Fetch Directions and join for textarea
$stmt = $conn->prepare("SELECT Instruction FROM RecipeDirections WHERE RecipeID = ? ORDER BY StepNumber ASC");
$stmt->bind_param("i", $id);
$stmt->execute();
$stepsRes = $stmt->get_result();
$stepsArray = [];
while($row = $stepsRes->fetch_assoc()) $stepsArray[] = $row['Instruction'];
$stepsText = implode("\n", $stepsArray);

// 3. Fetch Selected Ingredients
$stmt = $conn->prepare("SELECT IngredientID FROM Contains WHERE RecipeID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$currentIngsRes = $stmt->get_result();
$currentIngs = [];
while($row = $currentIngsRes->fetch_assoc()) $currentIngs[] = $row['IngredientID'];

// 4. All Ingredients for dropdown
$allIngs = $conn->query("SELECT * FROM Ingredient ORDER BY FoodItem ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit: <?= htmlspecialchars($recipe['RecipeName']) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="container">
    <a href="recipes.php">← Cancel</a>
    <h1>Edit Recipe</h1>
    <form action="process_edit.php" method="POST" class="card">
        <input type="hidden" name="RecipeID" value="<?= $id ?>">
        
        <label>Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($recipe['RecipeName']) ?>" required>
        
        <div style="display:flex; gap:10px;">
            <input type="number" name="prep" placeholder="Prep" value="<?= $recipe['PrepTime'] ?>">
            <input type="number" name="cook" placeholder="Cook" value="<?= $recipe['CookTime'] ?>">
            <input type="number" name="servings" placeholder="Servings" value="<?= $recipe['Servings'] ?>">
        </div>

        <label>Instructions</label>
        <textarea name="steps" rows="6"><?= htmlspecialchars($stepsText) ?></textarea>

        <h3>Ingredients</h3>
        <select name="ingredients[]" multiple style="height: 150px;">
            <?php while($ing = $allIngs->fetch_assoc()): ?>
                <option value="<?= $ing['IngredientID'] ?>" <?= in_array($ing['IngredientID'], $currentIngs) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($ing['FoodItem']) ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit" class="btn btn-primary" style="width:100%; margin-top:20px;">Update Recipe</button>
    </form>
</body>
</html>
