<?php
session_start();
require_once '../includes/database_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $conn->begin_transaction();

    try {
        // Calculate TotalTime automatically
        $totalTime = (int)$_POST['prep'] + (int)$_POST['cook'];
        
        // 1. Prepare the SQL with all 21 specific columns (RecipeID is AI)
        $sql = "INSERT INTO Recipe (
                    RecipeName, PrepTime, CookTime, TotalTime, Servings, 
                    Yield, Ratings, url, img_src, total_fat, 
                    sat_fat, cholesterol, sodium, total_carb, dietary_fiber, 
                    total_sugars, protein, vitamin_c, calcium, iron, potassium
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        /* 
           Type Definition String:
           s = string, i = integer, d = double/float
           "siiiiidssdddddddddddd"
        */
        $stmt->bind_param("siiiiidssdddddddddddd", 
            $_POST['name'],           // RecipeName (s)
            $_POST['prep'],           // PrepTime (i)
            $_POST['cook'],           // CookTime (i)
            $totalTime,               // TotalTime (i)
            $_POST['servings'],       // Servings (i)
            $_POST['yield'],          // Yield (i)
            $_POST['rating'],         // Ratings (d)
            $_POST['url'],            // url (s)
            $_POST['img_src'],        // img_src (s)
            $_POST['total_fat'],      // total_fat (d)
            $_POST['sat_fat'],        // sat_fat (d)
            $_POST['cholesterol'],    // cholesterol (d)
            $_POST['sodium'],         // sodium (d)
            $_POST['total_carb'],     // total_carb (d)
            $_POST['dietary_fiber'],  // dietary_fiber (d)
            $_POST['total_sugars'],   // total_sugars (d)
            $_POST['protein'],        // protein (d)
            $_POST['vitamin_c'],      // vitamin_c (d)
            $_POST['calcium'],        // calcium (d)
            $_POST['iron'],           // iron (d)
            $_POST['potassium']       // potassium (d)
        );

        $stmt->execute();
        $recipeID = $conn->insert_id;

        // 2. Process Instructions (Textarea split)
        if (!empty($_POST['steps'])) {
            $instructions = explode("\n", str_replace("\r", "", $_POST['steps']));
            $stmtStep = $conn->prepare("INSERT INTO RecipeDirections (RecipeID, StepNumber, Instruction) VALUES (?, ?, ?)");
            $count = 1;
            foreach ($instructions as $text) {
                $trimmed = trim($text);
                if ($trimmed !== '') {
                    $stmtStep->bind_param("iis", $recipeID, $count, $trimmed);
                    $stmtStep->execute();
                    $count++;
                }
            }
        }

        // 3. Process Ingredients (Multi-select)
        if (isset($_POST['ingredients']) && is_array($_POST['ingredients'])) {
            $stmtContains = $conn->prepare("INSERT INTO Contains (RecipeID, IngredientID, Quantity) VALUES (?, ?, ?)");
            $defaultQty = "As specified";
            foreach ($_POST['ingredients'] as $ingID) {
                $stmtContains->bind_param("iis", $recipeID, $ingID, $defaultQty);
                $stmtContains->execute();
            }
        }

        $conn->commit();
        header("Location: recipes.php?success=1");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        die("Fatal Error Saving Recipe: " . $e->getMessage());
    }
}
