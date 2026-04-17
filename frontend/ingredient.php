<?php 
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
</html>
