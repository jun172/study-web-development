<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta chares="UTF-8">
        <title>100から1までの偶数リスト</title>
        <style>
            li{
                display: inline-block;
                margin-right: 10px; /*　感覚を開ける　*/
            }
            </style>
            </head>
            <body>
                <h1>100から1までの偶数リスト</h1>
                <ul>
<?php
for ($i = 100; $i >-1; $i--) {
    if ($i % 2 == 0) {
        echo "$i" . "<li>";
    }
}
?>
</ul>
</body>
</html>