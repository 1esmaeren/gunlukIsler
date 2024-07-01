<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && !empty($_POST['id'])) {
    $id = $_POST['id'];

    try {
        // Veritabanından silme işlemi
        $sql = "DELETE FROM isler_tb WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        // Silme işleminden sonra ana sayfaya yönlendirme
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        echo 'Silme işlemi sırasında hata oluştu: ' . $e->getMessage();
        exit();
    }
} else {
    // Hatalı istek durumunda ana sayfaya yönlendirme
    header("Location: index.php");
    exit();
}
?>