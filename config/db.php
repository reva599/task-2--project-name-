<?php
// SQLite Database Configuration with Fixed Password Hashing
define("DB_FILE", "blog.sqlite");

// Global PDO connection
$pdo = null;

try {
    // Create SQLite database connection
    $pdo = new PDO("sqlite:" . DB_FILE);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Create tables if they don't exist
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS posts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title VARCHAR(200) NOT NULL,
            content TEXT NOT NULL,
            user_id INTEGER NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )
    ");
    
    // Check if we need to insert/update sample data
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $user_count = $stmt->fetch()['count'];
    
    if ($user_count == 0) {
        // Create proper password hashes
        $admin_hash = password_hash('password', PASSWORD_DEFAULT);
        $testuser_hash = password_hash('password', PASSWORD_DEFAULT);
        $blogger_hash = password_hash('password', PASSWORD_DEFAULT);
        
        // Insert users with properly hashed passwords
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute(['admin', $admin_hash]);
        $stmt->execute(['testuser', $testuser_hash]);
        $stmt->execute(['blogger', $blogger_hash]);
        
        // Sample posts
        $pdo->exec("
            INSERT INTO posts (title, content, user_id) VALUES
            ('Welcome to Our Blog', 'This is the first post on our amazing blog platform. Here you can create, read, update and delete posts with full authentication!', 1),
            ('Getting Started with CRUD', 'Learn how to perform Create, Read, Update, and Delete operations in this comprehensive blog application.', 1),
            ('PHP Best Practices', 'Discover the best practices for PHP development including security, performance, and maintainability.', 2),
            ('User Authentication Guide', 'A complete guide to implementing secure user authentication in PHP applications.', 3)
        ");
    } else {
        // Update existing users with proper password hashes if they're using old hashes
        $stmt = $pdo->query("SELECT id, username, password FROM users WHERE username IN ('admin', 'testuser', 'blogger')");
        $users = $stmt->fetchAll();
        
        foreach ($users as $user) {
            // Check if password is the old format (starts with $2y$10$92IXUNpkjO...)
            if (strpos($user['password'], '$2y$10$92IXUNpkjO') === 0) {
                // Update with new hash
                $new_hash = password_hash('password', PASSWORD_DEFAULT);
                $update_stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                $update_stmt->execute([$new_hash, $user['id']]);
            }
        }
    }
    
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Helper functions
function isLoggedIn() {
    return isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"]);
}

function requireAuth() {
    if (!isLoggedIn()) {
        header("Location: ../auth/login.php");
        exit();
    }
}

function redirectIfLoggedIn() {
    if (isLoggedIn()) {
        header("Location: ../dashboard.php");
        exit();
    }
}

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

function setFlash($type, $message) {
    $_SESSION["flash"][$type] = $message;
}

function getFlash($type) {
    if (isset($_SESSION["flash"][$type])) {
        $message = $_SESSION["flash"][$type];
        unset($_SESSION["flash"][$type]);
        return $message;
    }
    return null;
}

function formatDate($date) {
    return date("F j, Y \\a\\t g:i A", strtotime($date));
}

function truncateText($text, $length = 150) {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . "...";
}
?>
