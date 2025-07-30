<?php
require_once "config/db.php";
requireAuth();

// Get user's posts
try {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$_SESSION["user_id"]]);
    $user_posts = $stmt->fetchAll();
} catch(PDOException $e) {
    $user_posts = [];
}

// Get total posts count
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM posts WHERE user_id = ?");
    $stmt->execute([$_SESSION["user_id"]]);
    $total_posts = $stmt->fetch()["total"];
} catch(PDOException $e) {
    $total_posts = 0;
}

$success = getFlash("success");
$error = getFlash("error");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CRUD Blog</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header class="main-header">
            <h1>Dashboard</h1>
            <nav class="main-nav">
                <a href="index.php" class="btn">Home</a>
                <a href="posts/create.php" class="btn btn-primary">New Post</a>
                <a href="auth/logout.php" class="btn">Logout</a>
            </nav>
        </header>
        
        <main>
            <div class="welcome-section">
                <h2>Welcome back, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h2>
                <p>Manage your blog posts and create new content.</p>
            </div>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if (isset($_GET["deleted"])): ?>
                <div class="alert alert-success">Post deleted successfully!</div>
            <?php endif; ?>
            
            <?php if (isset($_GET["error"])): ?>
                <div class="alert alert-error">Error occurred while processing your request.</div>
            <?php endif; ?>
            
            <div class="stats-section">
                <div class="stat-card">
                    <h3><?php echo $total_posts; ?></h3>
                    <p>Total Posts</p>
                </div>
            </div>
            
            <section class="posts-management">
                <h3>Your Posts</h3>
                
                <?php if (count($user_posts) > 0): ?>
                    <div class="posts-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Created</th>
                                    <th>Updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($user_posts as $post): ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo htmlspecialchars($post["title"]); ?></strong>
                                            <div class="post-preview">
                                                <?php echo htmlspecialchars(truncateText($post["content"], 100)); ?>
                                            </div>
                                        </td>
                                        <td><?php echo formatDate($post["created_at"]); ?></td>
                                        <td><?php echo formatDate($post["updated_at"]); ?></td>
                                        <td class="actions">
                                            <a href="posts/edit.php?id=<?php echo $post["id"]; ?>" class="btn btn-small">Edit</a>
                                            <a href="posts/delete.php?id=<?php echo $post["id"]; ?>" 
                                               class="btn btn-small btn-danger" 
                                               onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <h4>No posts yet!</h4>
                        <p>Start sharing your thoughts with the world.</p>
                        <a href="posts/create.php" class="btn btn-primary">Create Your First Post</a>
                    </div>
                <?php endif; ?>
            </section>
        </main>
    </div>
</body>
</html>
