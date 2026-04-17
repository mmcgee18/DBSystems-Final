<?php 
require_once '../includes/auth_functions.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $success = register_user(
        $_POST['firstname'], $_POST['lastname'], $_POST['username'], 
        $_POST['password'], $_POST['email'], $_POST['height'], 
        $_POST['weight'], $_POST['age'], $_POST['budget']
    );

    if ($success) {
        header('Location: index.php?registered=1');
        exit();
    } else {
        header('Location: register.php?error=exists');
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Register | Culinary Compass</title>
    <link href="style.css" rel="stylesheet">
</head>
<body class="container">
    <div class="card" style="max-width: 500px; margin: auto;">
        <h1>Create Account</h1>
        <form method="post" action="register.php">
            <div style="display:flex; gap:10px;">
                <input type="text" name="firstname" placeholder="First Name" required>
                <input type="text" name="lastname" placeholder="Last Name" required>
            </div>
            
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" id="password" placeholder="Password" required>
            <input type="password" id="confirm_password" placeholder="Confirm Password" required>

            <h3 style="margin-top:20px;">Health & Budget Profile</h3>
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px;">
                <input type="number" step="0.01" name="height" placeholder="Height (cm)" required>
                <input type="number" step="0.01" name="weight" placeholder="Weight (kg)" required>
                <input type="number" name="age" placeholder="Age" required>
                <input type="number" step="0.01" name="budget" placeholder="Weekly Budget ($)" required>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <p style="color:red;">Username or Email already exists.</p>
            <?php endif; ?>

            <div style="margin-top:20px;">
                <button type="submit" class="btn btn-primary" style="width:100%;">Register</button>
                <p style="text-align:center;"><a href="index.php">Already have an account? Login</a></p>
            </div>
        </form>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirm_password').value;
            
            if (password !== confirm) {
                e.preventDefault();
                alert('Passwords do not match!');
            } else if (password.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters!');
            }
        });
    </script>
</body>
</html>
