<?php
require_once "config/db.php";

// Get all posts with user information
try {
    $stmt = $pdo->prepare("SELECT p.*, u.username FROM posts p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC");
    $stmt->execute();
    $posts = $stmt->fetchAll();
} catch(PDOException $e) {
    $posts = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Blog Application</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header class="main-header">
            <h1>CRUD Blog Application</h1>
            <nav class="main-nav">
                <?php if (isLoggedIn()): ?>
                    <a href="dashboard.php" class="btn">Dashboard</a>
                    <a href="posts/create.php" class="btn btn-primary">New Post</a>
                    <a href="auth/logout.php" class="btn">Logout (<?php echo htmlspecialchars($_SESSION["username"]); ?>)</a>
                <?php else: ?>
                    <a href="auth/login.php" class="btn btn-primary">Login</a>
                    <a href="auth/register.php" class="btn">Register</a>
                <?php endif; ?>
            </nav>
        </header>
        
        <main>
            <section class="hero">
                <h2>Welcome to Our Blog Platform</h2>
                <p>Discover amazing content from our community of writers</p>
            </section>
            
            <section class="posts-section">
                <h3>Latest Posts</h3>
                
                <?php if (count($posts) > 0): ?>
                    <div class="posts-grid">
                        <?php foreach ($posts as $post): ?>
                            <article class="post-card">
                                <h4><?php echo htmlspecialchars($post["title"]); ?></h4>
                                <div class="post-meta">
                                    <span>By <?php echo htmlspecialchars($post["username"]); ?></span>
                                    <span><?php echo formatDate($post["created_at"]); ?></span>
                                </div>
                                <div class="post-content">
                                    <?php echo nl2br(htmlspecialchars(truncateText($post["content"], 200))); ?>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <h4>No posts yet!</h4>
                        <p>Be the first to share your thoughts.</p>
                        <?php if (!isLoggedIn()): ?>
                            <a href="auth/register.php" class="btn btn-primary">Get Started</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </section>
        </main>
        
        <footer>
            <p>&copy; 2024 CRUD Blog Application. Built with PHP & MySQL.</p>
        </footer>
    </div>
</body>
</html>
