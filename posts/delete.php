<?php
require_once "../config/db.php";
requireAuth();

$post_id = $_GET["id"] ?? 0;

try {
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
    
    if ($stmt->execute([$post_id, $_SESSION["user_id"]])) {
        header("Location: ../dashboard.php?deleted=1");
    } else {
        header("Location: ../dashboard.php?error=1");
    }
} catch(PDOException $e) {
    header("Location: ../dashboard.php?error=1");
}
exit();
?>
