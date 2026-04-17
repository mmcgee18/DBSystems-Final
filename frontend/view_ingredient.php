<?php 
require_once '../includes/database_functions.php';

// Get ID and validate it
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch Ingredient Data
$stmt = $conn->prepare("SELECT * FROM Ingredient WHERE IngredientID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$ing = $result->fetch_assoc();

// Redirect if not found
if (!$ing) {
    header("Location: ingredient.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($ing['FoodItem']) ?> | Nutrition Details</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .nutrition-label {
            background: white;
            border: 2px solid black;
            padding: 20px;
            max-width: 350px;
            margin: 20px auto;
            font-family: 'Inter', sans-serif;
        }
        .label-header { border-bottom: 10px solid black; padding-bottom: 5px; margin-bottom: 5px; }
        .label-header h1 { margin: 0; font-size: 2.2rem; font-weight: 800; }
        .line-bold { border-bottom: 5px solid black; margin: 5px 0; }
        .line-thin { border-bottom: 1px solid #ccc; margin: 5px 0; }
        .nutrient-row { display: flex; justify-content: space-between; padding: 3px 0; }
        .indent { padding-left: 20px; }
        .calories-box { display: flex; justify-content: space-between; align-items: baseline; font-size: 1.5rem; font-weight: 800; }
    </style>
</head>
<body class="container">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <a href="ingredient.php" class="btn" style="background:#dfe6e9;">← Back to Pantry</a>
        <div>
            <a href="edit_ingredient.php?id=<?= $id ?>" class="btn btn-primary">Edit Item</a>
        </div>
    </div>

    <div class="nutrition-label">
        <div class="label-header">
            <h1>Nutrition Facts</h1>
            <p><strong>Item:</strong> <?= htmlspecialchars($ing['FoodItem']) ?></p>
            <p><strong>Category:</strong> <?= htmlspecialchars($ing['Category']) ?></p>
        </div>
        
        <div class="calories-box">
            <span>Calories</span>
            <span><?= number_format($ing['Calories'], 0) ?></span>
        </div>
        <div class="line-bold"></div>
        
        <p style="text-align: right; font-size: 0.8rem;"><strong>% Daily Value*</strong></p>
        
        <div class="nutrient-row">
            <span><strong>Total Fat</strong> <?= $ing['Fat'] ?>g</span>
            <strong><?= number_format(($ing['Fat']/70)*100, 0) ?>%</strong>
        </div>
        <div class="line-thin"></div>
        
        <div class="nutrient-row indent">
            <span>Cholesterol <?= $ing['Cholesterol'] ?>mg</span>
            <strong><?= number_format(($ing['Cholesterol']/300)*100, 0) ?>%</strong>
        </div>
        <div class="line-thin"></div>
        
        <div class="nutrient-row">
            <span><strong>Sodium</strong> <?= $ing['Sodium'] ?>mg</span>
            <strong><?= number_format(($ing['Sodium']/2300)*100, 0) ?>%</strong>
        </div>
        <div class="line-thin"></div>

        <div class="nutrient-row">
            <span><strong>Total Carbohydrates</strong> <?= $ing['Carbohydrates'] ?>g</span>
            <strong><?= number_format(($ing['Carbohydrates']/275)*100, 0) ?>%</strong>
        </div>
        <div class="line-thin"></div>

        <div class="nutrient-row indent">
            <span>Dietary Fiber <?= $ing['Fiber'] ?>g</span>
            <strong><?= number_format(($ing['Fiber']/28)*100, 0) ?>%</strong>
        </div>
        <div class="line-thin"></div>

        <div class="nutrient-row indent">
            <span>Total Sugars <?= $ing['Sugars'] ?>g</span>
            <span></span>
        </div>
        <div class="line-bold"></div>

        <div class="nutrient-row">
            <span><strong>Protein</strong> <?= $ing['Protein'] ?>g</span>
            <span></span>
        </div>
        <div class="line-bold"></div>
        
        <p style="font-size: 0.7rem; color: #666;">
            * The % Daily Value (DV) tells you how much a nutrient in a serving of food contributes to a daily diet of 2,000 calories.
        </p>
    </div>
</body>
</html>
