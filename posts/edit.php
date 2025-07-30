<?php
require_once "../config/db.php";
requireAuth();

$post_id = $_GET["id"] ?? 0;
$error = "";

// Get post data
try {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ? AND user_id = ?");
    $stmt->execute([$post_id, $_SESSION["user_id"]]);
    $post = $stmt->fetch();
    
    if (!$post) {
        header("Location: ../dashboard.php");
        exit();
    }
} catch(PDOException $e) {
    header("Location: ../dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = sanitize($_POST["title"]);
    $content = sanitize($_POST["content"]);
    
    if (empty($title) || empty($content)) {
        $error = "All fields are required.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ? AND user_id = ?");
            
            if ($stmt->execute([$title, $content, $post_id, $_SESSION["user_id"]])) {
                header("Location: ../dashboard.php");
                exit();
            } else {
                $error = "Failed to update post.";
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
    <title>Edit Post - CRUD Blog</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <div class="container">
        <div class="post-form">
            <h2>Edit Post</h2>
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label>Title:</label>
                    <input type="text" name="title" required value="<?php echo htmlspecialchars($post["title"]); ?>">
                </div>
                <div class="form-group">
                    <label>Content:</label>
                    <textarea name="content" rows="10" required><?php echo htmlspecialchars($post["content"]); ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Update Post</button>
                <a href="../dashboard.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>
