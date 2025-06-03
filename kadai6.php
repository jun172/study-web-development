<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>1年間の日付一覧</title>
    <style>
        .date {
            display: inline-block;
            margin: 4px;
            font-family: sans-serif;
            color: rgba(0, 0, 0, .5);
        }
        li{
            display: inline-block;
        }
    </style>
</head>
<body>
    <h1>今日から1年間の日付</h1>
    <ul>
    <li>
    <?php
    $start = strtotime(date('Y-m-d'));
    $end = strtotime('+1 year', $start);

    for ($date = $start; $date < $end; $date = strtotime('+1 day', $date)) {
        echo "<li>" . date('n/j(D)', $date) . "</li>";
    }
    ?>
    </li>
    </ul>
</body>
</html>
