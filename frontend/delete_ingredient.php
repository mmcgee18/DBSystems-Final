<?php 
require_once '../includes/database_functions.php';
$id = (int)$_GET['id'];

$stmt = $conn->prepare("DELETE FROM Ingredient WHERE IngredientID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: ingredient.php");
