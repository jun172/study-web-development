<?php
require 'db.php';

$postId = $_GET['id'] ?? null;

if (!$postId || !is_numeric($postId)) {
    die("無効なIDです");
}

try {
    $pdo->beginTransaction();

    // 投稿に紐づくタグ削除
    $stmt = $pdo->prepare("DELETE FROM post_tags WHERE post_id = ?");
    $stmt->execute([$postId]);

    // 投稿本体削除
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$postId]);

    $pdo->commit();

    // 削除完了 → 一覧ページに戻す
    header("Location: index.php");
    exit;

} catch (PDOException $e) {
    $pdo->rollBack();
    die("削除失敗: " . $e->getMessage());
}
?>