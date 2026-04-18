<?php 
session_start(); 
require_once '../includes/database_functions.php'; 

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$show_favs = isset($_GET['favorites']) && $_GET['favorites'] == 1;
$search = $_GET['search'] ?? '';
$searchParam = "%$search%";

// Fetch recipes logic (Matches your current schema)
if ($show_favs) {
    $sql = "SELECT r.* FROM Recipe r JOIN Likes l ON r.RecipeID = l.RecipeID WHERE l.UserID = ? AND r.RecipeName LIKE ? ORDER BY r.RecipeID DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $searchParam);
} else {
    $sql = "SELECT * FROM Recipe WHERE RecipeName LIKE ? ORDER BY RecipeID DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $searchParam);
}
$stmt->execute();
$result = $stmt->get_result();

$likes_res = $conn->query("SELECT RecipeID FROM Likes WHERE UserID = $user_id");
$liked_ids = [];
while($row = $likes_res->fetch_assoc()) $liked_ids[] = $row['RecipeID'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kitchen Library | Culinary Compass</title>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Fix for Image Display Bug */
        .recipe-card img { 
            width: 100%; 
            height: 200px; 
            object-fit: cover; /* Ensures images fill space without stretching */
            display: block;
        }
        
        /* Modern Header Layout */
        .nav-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
            margin-bottom: 20px;
        }

        .search-row {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
        }

        .recipe-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }
    </style>
</head>
<body class="container">
    <!-- Clean Navigation Bar -->
    <nav class="nav-bar">
        <h1 style="margin:0;">Kitchen Library</h1>
        <a href="homepage.php" class="btn" style="background:#dfe6e9; color:#2d3436; text-decoration:none; padding:10px 20px; border-radius:8px;">
            <i class="fa fa-home"></i> Home
        </a>
    </nav>

    <!-- Search & Favorites Filter -->
    <div class="search-row">
        <form method="GET" style="flex:1; display:flex; gap:10px;">
            <input type="text" name="search" placeholder="Search recipes..." value="<?= htmlspecialchars($search) ?>" style="flex:1; padding:12px; border-radius:8px; border:1px solid #ddd;">
            <?php if($show_favs): ?><input type="hidden" name="favorites" value="1"><?php endif; ?>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        
        <a href="recipes.php?favorites=<?= $show_favs ? 0 : 1 ?>" class="btn" 
           style="background:<?= $show_favs ? '#e74c3c' : '#fff' ?>; color:<?= $show_favs ? '#fff' : '#e74c3c' ?>; border: 1px solid #e74c3c; display:flex; align-items:center; padding:0 20px; border-radius:8px;">
            <i class="fa <?= $show_favs ? 'fa-heart' : 'fa-heart-o' ?>"></i>
        </a>
    </div>

    <div class="recipe-grid">
        <?php while ($r = $result->fetch_assoc()): ?>
            <div class="recipe-card" style="background:#fff; border-radius:15px; overflow:hidden; box-shadow:0 4px 15px rgba(0,0,0,0.05);">
                <!-- Fixed Image Container -->
                <img src="<?= htmlspecialchars($r['img_src'] ?: 'placeholder.jpg') ?>" alt="Recipe">
                
                <div style="padding:20px;">
                    <h3 style="margin:0 0 10px 0;"><?= htmlspecialchars($r['RecipeName']) ?></h3>
                    <p style="color:#636e72; font-size:0.9rem; margin-bottom:15px;">⏱ <?= $r['TotalTime'] ?> min | 🍽 <?= $r['Servings'] ?> portions</p>
                    
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <a href="view_recipe.php?id=<?= $r['RecipeID'] ?>" class="btn" style="background:#f4f4f4; color:#2d3436; text-decoration:none; padding:8px 15px; border-radius:5px;">View</a>
                        
                        <div style="display:flex; gap:12px; align-items:center;">
                            <a href="toggle_like.php?id=<?= $r['RecipeID'] ?>" style="color:<?= in_array($r['RecipeID'], $liked_ids) ? '#e74c3c' : '#bdc3c7' ?>; font-size:1.3rem;">
                                <i class="fa <?= in_array($r['RecipeID'], $liked_ids) ? 'fa-heart' : 'fa-heart-o' ?>"></i>
                            </a>
                            <a href="edit_recipe.php?id=<?= $r['RecipeID'] ?>" style="color:#636e72; text-decoration:none;">✎</a>
                            <a href="delete_recipe.php?id=<?= $r['RecipeID'] ?>" style="color:#e74c3c; text-decoration:none;" onclick="return confirm('Delete?')">🗑</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <a href="add_recipe.php" class="fab" style="position:fixed; bottom:30px; right:30px; background:#00b894; color:#fff; width:60px; height:60px; border-radius:50%; display:flex; align-items:center; justify-content:center; text-decoration:none; font-size:24px; box-shadow:0 10px 20px rgba(0,184,148,0.3);">+</a>
</body>
</html>
