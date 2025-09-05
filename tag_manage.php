<?php
require 'db.php';
$error = '';

// タグ追加
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['new_tag'])){
    $newTag = trim($_POST['new_tag']);
    if($newTag !== ''){
        try {
            $pdo->prepare("INSERT INTO tags(name) VALUES(?)")->execute([$newTag]);
        } catch(PDOException $e){
            $error = "タグ追加失敗: ".$e->getMessage();
        }
    }
    header('Location: tag_manage.php'); exit;
}

// タグ削除
if(isset($_GET['delete_id'])){
    $pdo->prepare("DELETE FROM tags WHERE id=?")->execute([intval($_GET['delete_id'])]);
    header('Location: tag_manage.php'); exit;
}

// タグ編集
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['edit_tag'], $_POST['edit_id'])){
    $editTag = trim($_POST['edit_tag']);
    $editId  = intval($_POST['edit_id']);
    if($editTag !== ''){
        $pdo->prepare("UPDATE tags SET name=? WHERE id=?")->execute([$editTag, $editId]);
    }
    header('Location: tag_manage.php'); exit;
}

// タグ一覧取得
$tags = $pdo->query("SELECT * FROM tags ORDER BY name ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>タグ管理 - MyX Blog</title>
<link rel="stylesheet" href="jun.css">
</head>
<body>
<header>
    <h1>タグ管理</h1>
    <button onclick="location.href='index.php'">投稿一覧へ戻る</button>
</header>

<main>
    <?php if($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <h2>タグ追加</h2>
    <form method="POST">
        <input type="text" name="new_tag" placeholder="新しいタグ" required>
        <button type="submit">追加</button>
    </form>

    <h2>タグ一覧</h2>
    <ul>
        <?php foreach($tags as $tag): ?>
            <li>
                <?= htmlspecialchars($tag['name']) ?>
                <a href="?delete_id=<?= $tag['id'] ?>" onclick="return confirm('削除しますか？')">削除</a>
            </li>
        <?php endforeach; ?>
    </ul>
</main>
</body>
</html>
