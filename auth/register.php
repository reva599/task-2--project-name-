<?php
require_once "../config/db.php";
redirectIfLoggedIn();

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = sanitize($_POST["username"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            
            if ($stmt->fetch()) {
                $error = "Username already exists.";
            } else {
                $hashedPassword = hashPassword($password);
                $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                
                if ($stmt->execute([$username, $hashedPassword])) {
                    $success = "Registration successful! You can now login.";
                } else {
                    $error = "Registration failed.";
                }
            }
        } catch(PDOException $e) {
            $error = "Database error.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - CRUD Blog</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <div class="auth-form">
            <h2>Register</h2>
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            <form method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <button type="submit" class="btn btn-primary">Register</button>
            </form>
            <p><a href="login.php">Login</a> | <a href="../index.php">Home</a></p>
        </div>
    </div>
</body>
</html>
