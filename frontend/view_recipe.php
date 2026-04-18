<?php 
require_once '../includes/database_functions.php';
$id = (int)$_GET['id'];
$recipe = $conn->query("SELECT * FROM Recipe WHERE RecipeID = $id")->fetch_assoc();
$steps = $conn->query("SELECT * FROM RecipeDirections WHERE RecipeID = $id ORDER BY StepNumber ASC");
$ings = $conn->query("SELECT i.FoodItem, c.Quantity FROM Contains c JOIN Ingredient i ON c.IngredientID = i.IngredientID WHERE c.RecipeID = $id");
?>
<!DOCTYPE html>
<html>
<head><link rel="stylesheet" href="style.css"></head>
<body class="container">
    <a href="recipes.php">← Back</a>
    <div class="card" style="margin-top:20px;">
        <img src="<?= $recipe['img_src'] ?>" style="width:100%; height:300px; object-fit:cover; border-radius:15px;">
        <h1><?= htmlspecialchars($recipe['RecipeName']) ?></h1>
        <p><a href="<?= $recipe['url'] ?>" target="_blank">View Original Source</a></p>
        
        <div style="display:grid; grid-template-columns: 2fr 1fr; gap:20px;">
            <div>
                <h3>Method</h3>
                <?php while($s = $steps->fetch_assoc()) echo "<p><b>{$s['StepNumber']}.</b> {$s['Instruction']}</p>"; ?>
            </div>
            <div style="background:#f4f4f4; padding:15px; border-radius:10px;">
                <h4>Nutrition (Per Serving)</h4>
                <p>Fat: <?= $recipe['total_fat'] ?>g | Carbs: <?= $recipe['total_carb'] ?>g</p>
                <p>Protein: <?= $recipe['protein'] ?>g | Sugar: <?= $recipe['total_sugars'] ?>g</p>
                <hr>
                <small>Vit C: <?= $recipe['vitamin_c'] ?>% | Iron: <?= $recipe['iron'] ?>%</small>
            </div>
        </div>
    </div>
</body>
</html>
