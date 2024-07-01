
<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tarih = $_POST['tarih'];
    $sube = $_POST['sube'];
    $kisi = $_POST['kisi'];
    $iletisim = $_POST['iletisim'];
    $konu = $_POST['konu'];
    $durum = $_POST['durum'];

    $sql = "INSERT INTO isler_tb (tarih, sube, kisi, iletisim, konu, durum) VALUES (:tarih, :sube, :kisi, :iletisim, :konu, :durum)";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':tarih' => $tarih,
        ':sube' => $sube,
        ':kisi' => $kisi,
        ':iletisim' => $iletisim,
        ':konu' => $konu,
        ':durum' => $durum
    ]);

    // İşlem tamamlandığında geri dön
    header("Location: index.php");
}
?>