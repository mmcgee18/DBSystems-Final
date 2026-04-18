<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Ingredient | Culinary Compass</title>
    <link href="https://googleapis.com" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body { background: linear-gradient(135deg, #74b9ff, #55efc4); min-height: 100vh; padding: 40px 0; }
        .form-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); border-radius: 20px; padding: 40px; max-width: 700px; margin: auto; box-shadow: 0 15px 35px rgba(0,0,0,0.1); }
        .section-title { border-left: 5px solid #00b894; padding-left: 15px; margin: 25px 0 15px; font-weight: 600; color: #2d3436; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
        input { width: 100%; padding: 12px; border: 1px solid #dfe6e9; border-radius: 10px; box-sizing: border-box; }
        label { font-size: 0.8rem; color: #636e72; font-weight: 600; display: block; margin-bottom: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-card">
            <header style="display:flex; justify-content:space-between; align-items:center;">
                <h1 style="margin:0;">New Ingredient</h1>
                <a href="ingredient.php" class="btn" style="background:#dfe6e9; color:#2d3436; text-decoration:none; padding:10px 15px; border-radius:10px;">Cancel</a>
            </header>

            <!-- Functionality: Standard POST method -->
            <form action="process_add_ingredient.php" method="POST">
                <div class="section-title">General Info</div>
                <label>Food Item Name</label>
                <input type="text" name="FoodItem" placeholder="e.g. Avocado" required>
                
                <div class="grid-2" style="margin-top:15px;">
                    <div><label>Category</label><input type="text" name="Category" placeholder="Produce"></div>
                    <div><label>Meal Type</label><input type="text" name="MealType" placeholder="Breakfast/Lunch"></div>
                </div>

                <div class="section-title">Nutritional Facts (Per 100g)</div>
                <div class="grid-3">
                    <!-- Functionality: Database column names preserved -->
                    <div><label>Calories</label><input type="number" step="0.01" name="Calories"></div>
                    <div><label>Protein (g)</label><input type="number" step="0.01" name="Protein"></div>
                    <div><label>Carbs (g)</label><input type="number" step="0.01" name="Carbohydrates"></div>
                    <div><label>Fat (g)</label><input type="number" step="0.01" name="Fat"></div>
                    <div><label>Fiber (g)</label><input type="number" step="0.01" name="Fiber"></div>
                    <div><label>Sugars (g)</label><input type="number" step="0.01" name="Sugars"></div>
                    <div><label>Sodium (mg)</label><input type="number" step="0.01" name="Sodium"></div>
                    <div><label>Cholesterol (mg)</label><input type="number" step="0.01" name="Cholesterol"></div>
                    <div><label>Water (mL)</label><input type="number" name="WaterIntake"></div>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%; padding:15px; margin-top:30px; font-size:1.1rem; border-radius:15px; border:none; cursor:pointer; color:white;">
                    💾 Save to Pantry
                </button>
            </form>
        </div>
    </div>
</body>
</html>



<!-- <!DOCTYPE html>
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
</html> -->
