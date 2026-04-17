<!DOCTYPE html>
<html>
<head>
    <title>Add Ingredient</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="container">
    <h1>New Food Item</h1>
    <form action="process_add_ingredient.php" method="POST" class="card">
        <label>Food Name</label>
        <input type="text" name="FoodItem" required placeholder="e.g. Greek Yogurt">
        
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
            <div><label>Category</label><input type="text" name="Category" placeholder="Dairy"></div>
            <div><label>Meal Type</label><input type="text" name="MealType" placeholder="Breakfast"></div>
        </div>

        <h3>Nutritional Values (per 100g)</h3>
        <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:10px;">
            <div><label>Calories</label><input type="number" step="0.01" name="Calories"></div>
            <div><label>Protein</label><input type="number" step="0.01" name="Protein"></div>
            <div><label>Carbs</label><input type="number" step="0.01" name="Carbohydrates"></div>
            <div><label>Fat</label><input type="number" step="0.01" name="Fat"></div>
            <div><label>Fiber</label><input type="number" step="0.01" name="Fiber"></div>
            <div><label>Sugars</label><input type="number" step="0.01" name="Sugars"></div>
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%; margin-top:20px;">Add to Pantry</button>
    </form>
</body>
</html>
