<?php
require 'db.php';

$search = $_GET['search'] ?? '';

$sql = "SELECT * FROM isler_tb WHERE sube LIKE :search OR kisi LIKE :search OR iletisim LIKE :search OR konu LIKE :search OR durum LIKE :search ORDER BY tarih DESC LIMIT 20";
$stmt = $pdo->prepare($sql);
$stmt->execute(['search' => '%' . $search . '%']);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($results);
?>