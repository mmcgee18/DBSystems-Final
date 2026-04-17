<?php 
session_start(); 
if (!isset($_SESSION['user_id'])) { 
    header('Location: index.php'); 
    exit(); 
} 
require_once '../includes/database_functions.php'; 
?> 
<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Culinary Compass | Homepage</title> 
    <link href="https://googleapis.com" rel="stylesheet"> 
    <style> 
        body, html { margin: 0; padding: 0; overflow: hidden; font-family: 'Poppins', sans-serif; } 
        
        .window { 
            width: 100vw; height: 100vh; 
            background: linear-gradient(135deg, #74b9ff, #55efc4); 
            display: flex; align-items: center; justify-content: center; flex-direction: column; 
        } 

        .title { display: flex; flex-direction: column; align-items: center; margin-top: 20px; } 
        .title > h1 { 
            font-size: 4rem; color: white; 
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2); margin: 0; 
        } 
        
        .title > img { height: 180px; width: auto; margin-top: 10px; filter: drop-shadow(0 10px 15px rgba(0,0,0,0.2)); }

        /* Animation for the food icon */
        .move { animation: float 4s infinite ease-in-out; } 
        @keyframes float { 
            0%, 100% { transform: translateY(0) rotate(0deg); } 
            50% { transform: translateY(-20px) rotate(5deg); } 
        } 

        .content { display: flex; flex-direction: column; align-items: center; margin-top: 40px; gap: 15px; } 
        
        button { 
            width: 350px; padding: 15px; font-size: 1.2rem; 
            color: white; background: rgba(255, 255, 255, 0.2); 
            border: 2px solid white; border-radius: 15px; cursor: pointer; 
            backdrop-filter: blur(5px); transition: 0.3s; 
        } 
        button:hover { background: white; color: #00b894; transform: translateY(-3px); } 

        /* Info Panel Logic */
        .info-btn:active ~ .info { opacity: 1; pointer-events: auto; } 
        
        .info { 
            position: absolute; width: 320px; background: white; 
            border-radius: 20px; padding: 25px; opacity: 0; 
            transition: 0.3s; pointer-events: none;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1); color: #2d3436; 
        } 
        .left { left: 40px; top: 100px; } 
        .right { right: 40px; top: 100px; } 

        /* Floating Veggies (formerly snow) */
        .floating-container { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; pointer-events: none; }
        .item { position: absolute; font-size: 25px; top: -50px; animation: fall linear infinite; }
        @keyframes fall {
            0% { transform: translateY(-10vh) rotate(0deg); }
            100% { transform: translateY(110vh) rotate(360deg); }
        }
    </style> 
</head> 
<body> 
    <div class="window"> 
        <div class="title"> 
            <h1>Culinary Compass</h1> 
            <img class="move" src="https://flaticon.com" alt="Meal Icon"> 
        </div> 

        <div class="content"> 
            <button onclick="location.href='ingredient.php'">INGREDIENTS</button> 
            <button onclick="location.href='recipes.php'">RECIPE BOOK</button> 
            <button class="info-btn">DASHBOARD INFO</button> 
            <button onclick="location.href='logout.php'">LOGOUT</button> 

            <!-- Info Panels -->
            <div class="info left"> 
                <h3 style="color: #00b894;">Webpage Summary</h3> 
                <p>Track your health metrics, manage grocery lists, and organize your weekly meals in one place.</p> 
                <strong>Current Goal:</strong> <span>Maintain Weight</span>
            </div> 

            <div class="info right"> 
                <h3>User: <?php echo htmlspecialchars($_SESSION['username']); ?></h3> 
                <hr>
                <p><strong>Tips:</strong><br>
                1. Add recipes to save time.<br>
                2. Use the "Generate List" tool to automatically make a weeks worth of recipes meeting your specified needs.<br>
                3. Stay hydrated!</p>
            </div> 
        </div> 
    </div> 

    <!-- Decorative Floating Food Items -->
    <div class="floating-container"> 
        <div class="item" style="left:10%; animation-duration:8s;">🥗</div> 
        <div class="item" style="left:25%; animation-duration:6s;">🍎</div> 
        <div class="item" style="left:45%; animation-duration:9s;">🥑</div> 
        <div class="item" style="left:65%; animation-duration:7s;">🥦</div> 
        <div class="item" style="left:85%; animation-duration:10s;">🍗</div> 
    </div> 
</body> 
</html>