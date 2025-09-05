<?php
require 'db.php';
header('Content-Type: application/json');
$tags = $pdo->query("SELECT * FROM tags ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($tags);
?>
