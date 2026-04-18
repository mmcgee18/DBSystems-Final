<?php
session_start();
require_once '../includes/database_functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// --- 1. HANDLE WEEK SELECTION (MONDAY CONSISTENCY) ---
if (isset($_GET['week_of'])) {
    $date_obj = new DateTime($_GET['week_of']);
    $date_obj->modify('monday this week');
    $week_of = $date_obj->format('Y-m-d');
} else {
    $week_of = date('Y-m-d', strtotime('monday this week'));
}

// Generate dropdown for 4 weeks back and 8 weeks forward
$monday_options = [];
$current_monday = new DateTime(date('Y-m-d', strtotime('monday this week')));
for ($i = -4; $i <= 8; $i++) {
    $temp_date = clone $current_monday;
    $temp_date->modify("$i week");
    $monday_options[] = $temp_date->format('Y-m-d');
}

// --- 2. FETCH RECIPES AND EXISTING PLANS ---
// Get all recipes for dropdowns
$recipe_options = "<option value=''>-- Skip / Empty --</option>";
$all_recipes = $conn->query("SELECT RecipeID, RecipeName FROM Recipe ORDER BY RecipeName ASC");
while($row = $all_recipes->fetch_assoc()) {
    $recipe_options .= "<option value='{$row['RecipeID']}'>".htmlspecialchars($row['RecipeName'])."</option>";
}

// Get existing plan for the user and selected Monday
$plans = [];
$sql = "SELECT DayOfWeek, MealType, RecipeID FROM MealPlans WHERE UserID = ? AND WeekOf = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_id, $week_of);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $plans[$row['DayOfWeek']][$row['MealType']] = $row['RecipeID'];
}

$days = [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday'];
$meals = ['Breakfast', 'Lunch', 'Dinner', 'Snack'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Meal Planner | Culinary Compass</title>
    <link rel="stylesheet" href="https://cloudflare.com">
    <link rel="stylesheet" href="style.css">
    <style>
        body { background: linear-gradient(135deg, #74b9ff, #55efc4); min-height: 100vh; font-family: 'Poppins', sans-serif; }
        .planner-container { 
            background: rgba(255, 255, 255, 0.9); 
            backdrop-filter: blur(10px); 
            border-radius: 20px; 
            padding: 30px; 
            margin: 20px auto; 
            max-width: 1200px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); 
        }
        .week-nav { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 20px; 
            background: #fff; 
            padding: 15px; 
            border-radius: 12px; 
            border: 1px solid #dfe6e9;
        }
        .grid-7 { display: grid; grid-template-columns: repeat(7, 1fr); gap: 10px; margin-bottom: 25px; }
        .day-card { background: #fff; border-radius: 12px; padding: 10px; border: 1px solid #dfe6e9; }
        .day-title { text-align: center; font-weight: 600; color: #00b894; border-bottom: 2px solid #55efc4; margin-bottom: 10px; padding-bottom: 5px; }
        .meal-slot { margin-bottom: 12px; }
        .meal-label { font-size: 0.7rem; font-weight: 600; color: #636e72; text-transform: uppercase; margin-bottom: 2px; display: block; }
        select { width: 100%; padding: 5px; border-radius: 5px; border: 1px solid #dfe6e9; font-size: 0.85rem; }
        .btn-group { display: flex; gap: 10px; }
        .btn-nav { background: #2d3436; color: white; text-decoration: none; padding: 10px 15px; border-radius: 8px; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="container planner-container">
        <header style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h1 style="margin:0; color:#2d3436;"><i class="fa fa-calendar"></i> Weekly Planner</h1>
            <a href="homepage.php" class="btn-nav"><i class="fa fa-home"></i> Home</a>
        </header>

        <!-- Week Selector & Magic Actions -->
        <div class="week-nav">
            <form method="GET" id="weekSelector" style="margin:0;">
                <label style="font-weight:600; color:#636e72;">Viewing Week: </label>
                <select name="week_of" onchange="this.form.submit()" style="width: auto; display:inline-block;">
                    <?php foreach ($monday_options as $monday): ?>
                        <option value="<?= $monday ?>" <?= ($monday == $week_of) ? 'selected' : '' ?>>
                            <?= date('M d, Y', strtotime($monday)) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
            
            <div class="btn-group">
                <a href="auto_fill_plan.php?week_of=<?= $week_of ?>" class="btn" 
                   style="background:#6c5ce7; color:white; text-decoration:none; padding:10px 15px; border-radius:8px;"
                   onclick="return confirm('Auto-fill empty slots with random recipes?')">
                    <i class="fa fa-magic"></i> Auto-Fill
                </a>
            </div>
        </div>

        <!-- Main Schedule Form -->
        <form action="process_meal_plan.php" method="POST">
            <input type="hidden" name="week_of" value="<?= $week_of ?>">
            
            <div class="grid-7">
                <?php foreach ($days as $num => $name): ?>
                    <div class="day-column day-card">
                        <div class="day-title"><?= $name ?></div>
                        <?php foreach ($meals as $type): ?>
                            <div class="meal-slot">
                                <label class="meal-label"><?= $type ?></label>
                                <select name="plan[<?= $num ?>][<?= $type ?>]">
                                    <?php 
                                        $currentID = $plans[$num][$type] ?? '';
                                        // Highlight the current selected recipe in the dropdown
                                        echo str_replace("value='$currentID'", "value='$currentID' selected", $recipe_options); 
                                    ?>
                                </select>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Footer Actions -->
            <div style="display:flex; gap:15px; align-items: stretch;">
                <button type="submit" class="btn btn-primary" style="flex:2; font-size:1.1rem; padding:15px;">
                    <i class="fa fa-save"></i> Save All Changes
                </button>
                
                <a href="reset_meal_plan.php?week_of=<?= $week_of ?>" class="btn btn-danger" 
                   style="flex:1; display:flex; align-items:center; justify-content:center; text-decoration:none;"
                   onclick="return confirm('Clear ALL recipes for this week? This cannot be undone.')">
                    <i class="fa fa-trash"></i> Reset Week
                </a>
                
                <button type="reset" class="btn" style="background:#dfe6e9; flex:0.5;">Reset Form</button>
            </div>
        </form>
    </div>
</body>
</html>
