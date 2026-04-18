<?php
session_start();
require_once '../includes/database_functions.php';

$plan_id = (int)$_GET['id'];
$week_of = $_GET['week_of']; // Passed from view to maintain focus

$stmt = $conn->prepare("DELETE FROM MealPlans WHERE PlanID = ? AND UserID = ?");
$stmt->bind_param("ii", $plan_id, $_SESSION['user_id']);
$stmt->execute();

// Redirect back to the specific week
header("Location: meal_plans.php?week_of=" . urlencode($week_of));
exit();
