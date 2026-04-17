<?php 
require_once '../includes/database_functions.php';
$id = (int)$_GET['id'];

$stmt = $conn->prepare("DELETE FROM Recipe WHERE RecipeID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: recipes.php");
exit();
?>
