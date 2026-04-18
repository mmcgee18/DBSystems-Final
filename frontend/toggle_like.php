<?php
session_start();
require_once '../includes/database_functions.php';

if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $recipe_id = (int)$_GET['id'];
    $user_id = (int)$_SESSION['user_id'];

    // Check if already liked
    $check = $conn->prepare("SELECT * FROM Likes WHERE UserID = ? AND RecipeID = ?");
    $check->bind_param("ii", $user_id, $recipe_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        // Already liked: Remove it
        $stmt = $conn->prepare("DELETE FROM Likes WHERE UserID = ? AND RecipeID = ?");
    } else {
        // Not liked yet: Add it
        $stmt = $conn->prepare("INSERT INTO Likes (UserID, RecipeID) VALUES (?, ?)");
    }
    $stmt->bind_param("ii", $user_id, $recipe_id);
    $stmt->execute();
}

// Redirect back to the library or current view
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
