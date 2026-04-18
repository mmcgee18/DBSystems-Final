<?php 
require_once '../includes/database_functions.php';
$id = (int)$_GET['id'];

// Because of ON DELETE CASCADE in your SQL, this single command 
// deletes the directions and ingredient links automatically.
$stmt = $conn->prepare("DELETE FROM Recipe WHERE RecipeID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: recipes.php?deleted=1");
exit();
?>
