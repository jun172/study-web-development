<?php
require 'db.php';

$tagFilter = $_GET['tag'] ?? '';

// 記事取得
if ($tagFilter) {
    $stmt = $pdo->prepare("
        SELECT p.*, GROUP_CONCAT(t.name) AS tags
        FROM posts p
        LEFT JOIN post_tags pt ON p.id=pt.post_id
        LEFT JOIN tags t ON t.id=pt.tag_id
        WHERE t.name = ?
        GROUP BY p.id
        ORDER BY p.created_at DESC
    ");
    $stmt->execute([$tagFilter]);
} else {
    $stmt = $pdo->query("
        SELECT p.*, GROUP_CONCAT(t.name) AS tags
        FROM posts p
        LEFT JOIN post_tags pt ON p.id=pt.post_id
        LEFT JOIN tags t ON t.id=pt.tag_id
        GROUP BY p.id
        ORDER BY p.created_at DESC
    ");
}
$posts = $stmt->fetchAll();

// タグ一覧
$tags = $pdo->query("SELECT * FROM tags ORDER BY name ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>MyX Blog</title>
<link rel="stylesheet" href="jun.css">
</head>
<body>
<header>
    <h1>MyX Blog</h1>
</header>
<div class="container">
    <aside class="sidebar">
        <h3>タグ検索</h3>
        <?php foreach($tags as $tag): ?>
            <a href="?tag=<?= htmlspecialchars($tag['name']) ?>" class="tag-btn"><?= htmlspecialchars($tag['name']) ?></a>
        <?php endforeach; ?>
        <a href="index.php" class="tag-btn">クリア</a>
        <hr>
        <a href="create.php" class="button">新規投稿</a>
        <a href="tag_manage.php" class="button">タグ管理</a>
    </aside>
    <main class="main">
        <?php foreach($posts as $post): ?>
        <article class="post">
            <h2><?= htmlspecialchars($post['title']) ?></h2>
            <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
            <p class="tags">タグ: <?= htmlspecialchars($post['tags'] ?? '') ?></p>
            <p class="time">作成日時: <?= $post['created_at'] ?></p>
            <a href="edit.php?id=<?= $post['id'] ?>" class="button edit-btn">編集</a>
            <a href="http://localhost:8888/delete.php=<?= $post['id'] ?>" class="button delete-btn" onclick="return confirm('本当に削除しますか？')">削除</a>
        </article>
        <?php endforeach; ?>
    </main>
</div>
</body>
</html>
