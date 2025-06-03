<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>1から365までの数字を横に表示</title>
    <style>
        li {
            display: inline-block;
            margin-right: 10px; /* 間隔を開ける */
        }
    </style>
</head>
<body>
    <ul>
        <?php
        for ($i = 1; $i <= 365; $i++) {
            echo "<li>{$i}</li>";
        }
        ?>
    </ul>
    <p>今日知ったこと1.phpでhtmlとcssが使えるの知った。2.cssでわかんない単語がでてきたけど参考書を読んでよかった。 </p>
</body>
</html>
