<?php 
session_start(); 
require_once '../includes/database_functions.php'; // Provides $conn from get_db_connection()

$search = isset($_GET['search']) ? $_GET['search'] : '';
$searchParam = "%$search%";

$sql = "SELECT * FROM Recipe WHERE RecipeName LIKE ? ORDER BY RecipeID DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $searchParam);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kitchen Library</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="container">
    <header class="hero">
        <h1>Kitchen Library</h1>
        <form method="GET" class="search-container">
            <input type="text" name="search" placeholder="Search recipes..." value="<?= htmlspecialchars($search) ?>">
        </form>
    </header>

    <div class="recipe-grid">
        <?php while ($r = $result->fetch_assoc()): ?>
            <div class="recipe-card">
                <div class="card-content">
                    <h2 class="card-title"><?= htmlspecialchars($r['RecipeName']) ?></h2>
                    <p class="card-meta">⏱ <?= $r['TotalTime'] ?> min | 🍽 <?= $r['Servings'] ?> portions</p>
                </div>
                <div class="card-actions">
                    <a href="view_recipe.php?id=<?= $r['RecipeID'] ?>" class="btn">View</a>
                    <div class="icon-actions">
                        <!-- Edit Link -->
                        <a href="edit_recipe.php?id=<?= $r['RecipeID'] ?>" title="Edit">✎</a>
                        <!-- Delete Link -->
                        <a href="delete_recipe.php?id=<?= $r['RecipeID'] ?>" title="Delete" style="color:#e74c3c;" onclick="return confirm('Delete this recipe?')">🗑</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <a href="add_recipe.php" class="fab">+</a>
</body>
</html>
