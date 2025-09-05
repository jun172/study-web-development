<?php
require 'db.php';
header('Content-Type: application/json');

$tag = $_GET['tag'] ?? null;

if ($tag) {
    $stmt = $pdo->prepare("
        SELECT p.*, GROUP_CONCAT(t.name) AS tags
        FROM posts p
        LEFT JOIN post_tags pt ON p.id = pt.post_id
        LEFT JOIN tags t ON t.id = pt.tag_id
        WHERE t.name = ?
        GROUP BY p.id
        ORDER BY p.created_at DESC
    ");
    $stmt->execute([$tag]);
} else {
    $stmt = $pdo->query("
        SELECT p.*, GROUP_CONCAT(t.name) AS tags
        FROM posts p
        LEFT JOIN post_tags pt ON p.id = pt.post_id
        LEFT JOIN tags t ON t.id = pt.tag_id
        GROUP BY p.id
        ORDER BY p.created_at DESC
    ");
}
echo json_encode($stmt->fetchAll());
?>