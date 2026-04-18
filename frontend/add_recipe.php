<?php 
session_start(); 
if (!isset($_SESSION['user_id'])) { 
    header('Location: index.php'); 
    exit(); 
} 
require_once '../includes/database_functions.php'; 

// Fetch ingredients for the multi-select dropdown
$ingredients = $conn->query("SELECT IngredientID, FoodItem FROM Ingredient ORDER BY FoodItem ASC");
?> 
<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <title>Add Recipe | Culinary Compass</title> 
    <link href="https://googleapis.com" rel="stylesheet"> 
    <link rel="stylesheet" href="https://cloudflare.com">
    <link rel="stylesheet" href="style.css">
    <style>
        body { background: linear-gradient(135deg, #74b9ff, #55efc4); min-height: 100vh; overflow-y: auto; }
        .form-card { 
            background: rgba(255, 255, 255, 0.9); 
            backdrop-filter: blur(10px); 
            border-radius: 20px; 
            padding: 40px; 
            margin: 40px auto; 
            max-width: 800px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.1); 
        }
        .section-title { 
            border-left: 5px solid #00b894; 
            padding-left: 15px; 
            margin: 25px 0 15px; 
            color: #2d3436; 
            font-weight: 600;
        }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; }
        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
        input, textarea, select { 
            border: 1px solid #dfe6e9; 
            border-radius: 10px; 
            padding: 12px; 
            font-family: 'Poppins', sans-serif;
            width: 100%;
            box-sizing: border-box;
        }
        label { font-size: 0.8rem; color: #636e72; font-weight: 600; margin-bottom: 5px; display: block; }
        .btn-submit { 
            background: #00b894; color: white; border: none; padding: 15px; 
            border-radius: 15px; width: 100%; font-size: 1.1rem; 
            cursor: pointer; transition: 0.3s; margin-top: 30px;
        }
        .btn-submit:hover { background: #009476; transform: translateY(-3px); }
    </style>
</head> 
<body> 
    <div class="container">
        <div class="form-card">
            <header style="display:flex; justify-content:space-between; align-items:center;">
                <h1 style="margin:0; color:#2d3436;">Add New Recipe</h1>
                <a href="recipes.php" class="btn" style="background:#dfe6e9; color:#2d3436; text-decoration:none; padding:10px 15px; border-radius:10px;">
                    <i class="fa fa-arrow-left"></i> Library
                </a>
            </header>

            <form action="process_add.php" method="POST">
                
                <!-- Section 1: Basics -->
                <div class="section-title">General Information</div>
                <label>Recipe Name</label>
                <input type="text" name="name" placeholder="e.g. Garlic Butter Salmon" required>
                
                <div class="grid-3" style="margin-top:15px;">
                    <div><label>Prep Time (min)</label><input type="number" name="prep" required></div>
                    <div><label>Cook Time (min)</label><input type="number" name="cook" required></div>
                    <div><label>Servings</label><input type="number" name="servings" required></div>
                </div>

                <div class="grid-3" style="margin-top:15px;">
                    <div><label>Yield</label><input type="number" name="yield" placeholder="e.g. 4"></div>
                    <div><label>Rating (0-5)</label><input type="number" step="0.1" name="rating" placeholder="4.5"></div>
                    <div><label>Image Source URL</label><input type="text" name="img_src" placeholder="https://..."></div>
                </div>
                
                <label style="margin-top:15px;">Original Recipe URL</label>
                <input type="text" name="url" placeholder="https://link-to-original-source.com">

                <!-- Section 2: Nutrition -->
                <div class="section-title">Nutritional Facts (Per Serving)</div>
                <div class="grid-4">
                    <div><label>Total Fat (g)</label><input type="number" step="0.01" name="total_fat"></div>
                    <div><label>Sat. Fat (g)</label><input type="number" step="0.01" name="sat_fat"></div>
                    <div><label>Cholesterol (mg)</label><input type="number" step="0.01" name="cholesterol"></div>
                    <div><label>Sodium (mg)</label><input type="number" step="0.01" name="sodium"></div>
                    <div><label>Total Carbs (g)</label><input type="number" step="0.01" name="total_carb"></div>
                    <div><label>Fiber (g)</label><input type="number" step="0.01" name="dietary_fiber"></div>
                    <div><label>Sugars (g)</label><input type="number" step="0.01" name="total_sugars"></div>
                    <div><label>Protein (g)</label><input type="number" step="0.01" name="protein"></div>
                </div>

                <div class="section-title">Vitamins & Minerals (% Daily Value)</div>
                <div class="grid-4">
                    <div><label>Vit C (%)</label><input type="number" step="0.01" name="vitamin_c"></div>
                    <div><label>Calcium (%)</label><input type="number" step="0.01" name="calcium"></div>
                    <div><label>Iron (%)</label><input type="number" step="0.01" name="iron"></div>
                    <div><label>Potassium (mg)</label><input type="number" step="0.01" name="potassium"></div>
                </div>

                <!-- Section 3: Detailed Content -->
                <div class="section-title">Ingredients & Steps</div>
                <label>Select Ingredients (Hold Ctrl to select multiple)</label>
                <select name="ingredients[]" multiple style="height: 120px;">
                    <?php while($ing = $ingredients->fetch_assoc()): ?>
                        <option value="<?= $ing['IngredientID'] ?>"><?= htmlspecialchars($ing['FoodItem']) ?></option>
                    <?php endwhile; ?>
                </select>

                <label style="margin-top:15px;">Instructions (Enter each step on a new line)</label>
                <textarea name="steps" rows="6" placeholder="1. Preheat oven to 400°F...&#10;2. Season the salmon..."></textarea>

                <button type="submit" class="btn-submit">
                    <i class="fa fa-save"></i> Save Recipe to Library
                </button>
            </form>
        </div>
    </div>
</body> 
</html>
