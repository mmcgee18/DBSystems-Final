<?php 
require_once '../includes/database_functions.php';
$id = (int)$_GET['id'];
$stmt = $conn->prepare("SELECT * FROM Ingredient WHERE IngredientID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$ing = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit: <?= htmlspecialchars($ing['FoodItem']) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="container">
    <h1>Edit Ingredient</h1>
    <form action="process_edit_ingredient.php" method="POST" class="card">
        <input type="hidden" name="IngredientID" value="<?= $id ?>">
        <label>Food Name</label>
        <input type="text" name="FoodItem" value="<?= htmlspecialchars($ing['FoodItem']) ?>" required>
        
        <!-- Add similar input fields as add_ingredient.php but with value="<?= $ing['ColumnName'] ?>" -->
        <label>Calories</label>
        <input type="number" step="0.01" name="Calories" value="<?= $ing['Calories'] ?>">
        
        <button type="submit" class="btn btn-primary" style="width:100%; margin-top:20px;">Update Ingredient</button>
    </form>
</body>
</html>