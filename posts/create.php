<?php
require_once "../config/db.php";
requireAuth();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = sanitize($_POST["title"]);
    $content = sanitize($_POST["content"]);
    
    if (empty($title) || empty($content)) {
        $error = "All fields are required.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO posts (title, content, user_id) VALUES (?, ?, ?)");
            
            if ($stmt->execute([$title, $content, $_SESSION["user_id"]])) {
                header("Location: ../dashboard.php");
                exit();
            } else {
                $error = "Failed to create post.";
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
    <title>Create Post - CRUD Blog</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <div class="post-form">
            <h2>Create New Post</h2>
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label>Title:</label>
                    <input type="text" name="title" required placeholder="Enter post title">
                </div>
                <div class="form-group">
                    <label>Content:</label>
                    <textarea name="content" rows="10" required placeholder="Write your post content here..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Create Post</button>
                <a href="../dashboard.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>
