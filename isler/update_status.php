<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $durum = $_POST['durum'];

    $sql = "UPDATE isler_tb SET durum = :durum WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':durum' => $durum,
        ':id' => $id
    ]);

    // İşlem tamamlandığında geri dön
    header("Location: index.php");
}
?>