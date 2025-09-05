<?php
require 'db.php'; // PDO接続

$postId = $_GET['id'] ?? null;
if (!$postId || !is_numeric($postId)) die('無効なIDです');

// 投稿取得
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$postId]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$post) die('投稿が存在しません');

// タグ一覧
$tags = $pdo->query("SELECT * FROM tags ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

// 投稿のタグ
$stmt = $pdo->prepare("SELECT tag_id FROM post_tags WHERE post_id = ?");
$stmt->execute([$postId]);
$postTags = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>投稿編集</title>
<link rel="stylesheet" href="jun.css">
</head>
<body>
<h1>投稿編集</h1>
<form action="edit_post.php" method="post">
    <input type="hidden" name="id" value="<?= $post['id'] ?>">
    <p>タイトル<br>
        <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>
    </p>
    <p>本文<br>
        <textarea name="content" rows="10" cols="50" required><?= htmlspecialchars($post['content']) ?></textarea>
    </p>
    <p>タグ<br>
    <?php foreach($tags as $tag): ?>
        <label>
            <input type="checkbox" name="tags[]" value="<?= $tag['id'] ?>"
            <?= in_array($tag['id'], $postTags) ? 'checked' : '' ?>>
            <?= htmlspecialchars($tag['name']) ?>
        </label>
    <?php endforeach; ?>
    </p>
    <button type="submit">更新</button>
</form>
<p><a href="index.php">一覧に戻る</a></p>
</body>
</html>
