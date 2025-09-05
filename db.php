<?php
// シンプルに直接DSNに書く方法（MAMP用）
$dsn = 'mysql:host=localhost;port=8889;dbname=sample_db;charset=utf8mb4';
$user = 'root';
$pass = 'root';

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,      // エラーを例外で通知
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // fetch時は連想配列
    ]);
    // 接続確認用
    // echo "DB接続成功";
} catch (PDOException $e) {
    die('DB接続失敗: ' . $e->getMessage());
}
?>
