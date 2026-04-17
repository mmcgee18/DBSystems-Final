<?php 
require_once '../includes/database_functions.php';
$ingredients = $conn->query("SELECT * FROM Ingredient ORDER BY FoodItem ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Create Recipe</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="container">
    <h1>New Recipe</h1>
    <form action="process_add.php" method="POST" class="card">
        <input type="text" name="name" placeholder="Recipe Name" required>
        <div style="display:flex; gap:10px;">
            <input type="number" name="prep" placeholder="Prep Time">
            <input type="number" name="cook" placeholder="Cook Time">
            <input type="number" name="servings" placeholder="Servings">
        </div>
        <textarea name="steps" placeholder="Instructions (one per line)" rows="6"></textarea>
        <h3>Select Ingredients</h3>
        <select name="ingredients[]" multiple style="height: 150px;">
            <?php while($ing = $ingredients->fetch_assoc()): ?>
                <option value="<?= $ing['IngredientID'] ?>"><?= htmlspecialchars($ing['FoodItem']) ?></option>
            <?php endwhile; ?>
        </select>
        <button type="submit" class="btn btn-primary" style="width:100%; margin-top:20px;">Save Recipe</button>
    </form>
</body>
</html>
