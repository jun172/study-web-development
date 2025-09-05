<?php
require 'db.php';
$error = '';
$tags = [];

try {
    $tags = $pdo->query("SELECT * FROM tags ORDER BY name ASC")->fetchAll();
} catch (PDOException $e) {
    $tags = [];
}

// ---------------------------
// 新規投稿処理
// ---------------------------
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['title'], $_POST['content'])){
    $title   = trim($_POST['title']);
    $content = trim($_POST['content']);
    $postTags = $_POST['tags'] ?? [];

    if(!$title || !$content){
        $error = "タイトルと本文は必須です";
    } else {
        try {
            $pdo->beginTransaction();

            // postsテーブルに追加
            $stmt = $pdo->prepare("INSERT INTO posts(title, content, created_at) VALUES(?, ?, NOW())");
            $stmt->execute([$title, $content]);
            $postId = $pdo->lastInsertId();

            // post_tagsに紐付け
            if($postTags){
                $stmt = $pdo->prepare("INSERT INTO post_tags(post_id, tag_id) VALUES(?, ?)");
                foreach($postTags as $tagId){
                    $stmt->execute([$postId, intval($tagId)]);
                }
            }

            $pdo->commit();
            header('Location: index.php'); exit;
        } catch(PDOException $e){
            $pdo->rollBack();
            $error = "投稿失敗: ".$e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>新規投稿 - MyX Blog</title>
<link rel="stylesheet" href="jun.css">
</head>
<body>
<div class="container">
    <h1>新規投稿</h1>

    <?php if($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <label>タイトル</label>
        <input type="text" name="title" required>

        <label>本文</label>
        <textarea name="content" rows="8" required></textarea>

        <div class="tags">
            <label>タグ</label>
            <?php foreach($tags as $tag): ?>
                <label>
                    <input type="checkbox" name="tags[]" value="<?= $tag['id'] ?>">
                    <?= htmlspecialchars($tag['name']) ?>
                </label>
            <?php endforeach; ?>
        </div>

        <button type="submit">投稿する</button>
    </form>

    <button onclick="location.href='index.php'">一覧に戻る</button>
</div>
</body>
</html>
