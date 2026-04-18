<?php 
session_start(); 
require_once '../includes/database_functions.php'; 

// Functionality: Keep search logic identical
$search = $_GET['search'] ?? '';
$searchParam = "%$search%";

$sql = "SELECT * FROM Ingredient WHERE FoodItem LIKE ? OR Category LIKE ? ORDER BY FoodItem ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $searchParam, $searchParam);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pantry | Culinary Compass</title>
    <link href="https://googleapis.com" rel="stylesheet">
    <link rel="stylesheet" href="https://cloudflare.com">
    <link rel="stylesheet" href="style.css">
    <style>
        body { background: linear-gradient(135deg, #74b9ff, #55efc4); min-height: 100vh; }
        .nav-bar { display: flex; justify-content: space-between; align-items: center; padding: 20px; background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border-radius: 15px; margin-bottom: 30px; }
        .recipe-card { background: white; border-radius: 20px; overflow: hidden; transition: 0.3s; border: 1px solid #f0f0f0; display: flex; flex-direction: column; }
        .recipe-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
        .card-content { padding: 25px; flex-grow: 1; }
        .card-tag { font-size: 0.7rem; text-transform: uppercase; color: #00b894; font-weight: 700; margin-bottom: 10px; display: block; }
        .card-actions { padding: 20px 25px; background: #fafafa; border-top: 1px solid #f0f0f0; display: flex; justify-content: space-between; }
    </style>
</head>
<body class="container">
    <nav class="nav-bar">
        <h1 style="margin:0; color:white;">Ingredient Pantry</h1>
        <a href="homepage.php" class="btn" style="background:#2d3436; color:white; text-decoration:none; padding:10px 20px; border-radius:10px;">🏠 Home</a>
    </nav>

    <!-- Functionality: GET Method for Search -->
    <form method="GET" style="margin-bottom: 40px; display: flex; gap: 10px;">
        <input type="text" name="search" placeholder="Search pantry items..." value="<?= htmlspecialchars($search) ?>" style="flex:1; padding:15px; border-radius:12px; border:none;">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <div class="recipe-grid">
        <?php while ($i = $result->fetch_assoc()): ?>
            <div class="recipe-card">
                <div class="card-content">
                    <span class="card-tag"><?= htmlspecialchars($i['Category'] ?: 'General') ?></span>
                    <h2 style="margin:0 0 10px 0;"><?= htmlspecialchars($i['FoodItem']) ?></h2>
                    <p style="color:#636e72; font-size:0.9rem;">🔥 <?= $i['Calories'] ?> kcal | 💪 <?= $i['Protein'] ?>g P</p>
                </div>
                <div class="card-actions">
                    <!-- Functionality: Direct ID links -->
                    <a href="view_ingredient.php?id=<?= $i['IngredientID'] ?>" class="btn" style="background:#dfe6e9; color:#2d3436; text-decoration:none; padding:8px 15px; border-radius:8px;">Details</a>
                    <div style="display:flex; gap:12px; align-items:center;">
                        <a href="edit_ingredient.php?id=<?= $i['IngredientID'] ?>" style="color:#636e72; text-decoration:none;">✎</a>
                        <a href="delete_ingredient.php?id=<?= $i['IngredientID'] ?>" style="color:#e74c3c; text-decoration:none;" onclick="return confirm('Delete item?')">🗑</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <a href="add_ingredient.php" class="fab" style="position:fixed; bottom:30px; right:30px; background:#00b894; color:white; width:60px; height:60px; border-radius:50%; display:flex; align-items:center; justify-content:center; text-decoration:none; font-size:24px; box-shadow:0 10px 20px rgba(0,0,0,0.1);">+</a>
</body>
</html>




<!-- <?php 
session_start(); 
require_once '../includes/database_functions.php'; 

$search = isset($_GET['search']) ? $_GET['search'] : '';
$searchParam = "%$search%";

$sql = "SELECT * FROM Ingredient WHERE FoodItem LIKE ? OR Category LIKE ? ORDER BY FoodItem ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $searchParam, $searchParam);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pantry | Ingredients</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Layout Improvements */
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .btn-home {
            text-decoration: none;
            color: #666;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: color 0.2s;
        }

        .btn-home:hover {
            color: #333;
        }

        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .search-container {
            flex-grow: 1;
            margin: 0 !important; /* Override hero style */
        }

        .search-container input {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        /* Add Button Style */
        .btn-add {
            background-color: #2ecc71;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            white-space: nowrap;
            transition: background 0.3s, transform 0.2s;
            display: inline-block;
        }

        .btn-add:hover {
            background-color: #27ae60;
            transform: translateY(-1px);
        }

        .btn-add:active {
            transform: translateY(0);
        }

        /* Responsive stack for mobile */
        @media (max-width: 600px) {
            .header-actions {
                flex-direction: column;
                align-items: stretch;
            }
            .btn-add {
                text-align: center;
            }
        }
    </style>
</head>
<body class="container">
    <header class="hero">
        <div class="header-top">
            <a href="index.php" class="btn-home">← Back to Home</a>
            <h1>Ingredient Pantry</h1>
            <div style="width: 110px;"></div> <!-- Visual balancer -->
        </div>

        <div class="header-actions">
            <form method="GET" class="search-container">
                <input type="text" name="search" placeholder="Search food items or categories..." value="<?= htmlspecialchars($search) ?>">
            </form>
            
            <a href="add_ingredient.php" class="btn-add">+ Add Ingredient</a>
        </div>
    </header>

    <div class="recipe-grid">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($i = $result->fetch_assoc()): ?>
                <div class="recipe-card">
                    <div class="card-content">
                        <span class="card-tag"><?= htmlspecialchars($i['Category']) ?></span>
                        <h2 class="card-title"><?= htmlspecialchars($i['FoodItem']) ?></h2>
                        <p class="card-meta">
                            🔥 <?= $i['Calories'] ?> kcal | 💪 <?= $i['Protein'] ?>g P
                        </p>
                    </div>
                    <div class="card-actions">
                        <a href="view_ingredient.php?id=<?= $i['IngredientID'] ?>" class="btn">Nutritional Info</a>
                        <div class="icon-actions">
                            <a href="edit_ingredient.php?id=<?= $i['IngredientID'] ?>">✎</a>
                            <a href="delete_ingredient.php?id=<?= $i['IngredientID'] ?>" style="color:#e74c3c;" onclick="return confirm('Delete this ingredient?')">🗑</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="text-align: center; grid-column: 1 / -1; padding: 50px;">
                <p>No ingredients found matching "<strong><?= htmlspecialchars($search) ?></strong>".</p>
                <a href="ingredients.php" style="color: #3498db;">Clear Search</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html> -->
