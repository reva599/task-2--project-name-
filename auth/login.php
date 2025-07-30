<?php
require_once "../config/db.php";
redirectIfLoggedIn();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = sanitize($_POST["username"]);
    $password = $_POST["password"];
    
    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            
            if ($user && verifyPassword($password, $user["password"])) {
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["username"] = $user["username"];
                header("Location: ../dashboard.php");
                exit();
            } else {
                $error = "Invalid username or password.";
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
    <title>Login - CRUD Blog</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <div class="auth-form">
            <h2>Login</h2>
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
            <div class="demo-accounts">
                <p><strong>Demo:</strong> admin / password</p>
                <p><strong>Demo:</strong> testuser / password</p>
            </div>
            <p><a href="register.php">Register</a> | <a href="../index.php">Home</a></p>
        </div>
    </div>
</body>
</html>
