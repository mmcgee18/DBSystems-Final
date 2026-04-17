<?php 
require_once '../includes/database_functions.php';
$id = (int)$_GET['id'];

// Fetch Recipe
$stmt = $conn->prepare("SELECT * FROM Recipe WHERE RecipeID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$recipe = $stmt->get_result()->fetch_assoc();

// Fetch Ingredients using JOIN
$sqlIng = "SELECT i.FoodItem, c.Quantity FROM Contains c JOIN Ingredient i ON c.IngredientID = i.IngredientID WHERE c.RecipeID = ?";
$stmtIng = $conn->prepare($sqlIng);
$stmtIng->bind_param("i", $id);
$stmtIng->execute();
$ingredients = $stmtIng->get_result();

// Fetch Directions
$stmtStep = $conn->prepare("SELECT * FROM RecipeDirections WHERE RecipeID = ? ORDER BY StepNumber ASC");
$stmtStep->bind_param("i", $id);
$stmtStep->execute();
$steps = $stmtStep->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($recipe['RecipeName']) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="container">
    <a href="recipes.php">← Back</a>
    <div class="card" style="margin-top:20px;">
        <h1><?= htmlspecialchars($recipe['RecipeName']) ?></h1>
        <p>Prep: <?= $recipe['PrepTime'] ?>m | Cook: <?= $recipe['CookTime'] ?>m</p>
        
        <h3>Ingredients</h3>
        <ul>
            <?php while($i = $ingredients->fetch_assoc()): ?>
                <li><?= htmlspecialchars($i['Quantity'] ?: 'To taste') ?> <?= htmlspecialchars($i['FoodItem']) ?></li>
            <?php endwhile; ?>
        </ul>

        <h3>Directions</h3>
        <?php while($s = $steps->fetch_assoc()): ?>
            <p><strong>Step <?= $s['StepNumber'] ?>:</strong> <?= htmlspecialchars($s['Instruction']) ?></p>
        <?php endwhile; ?>
    </div>
</body>
</html>
