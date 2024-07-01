<?php
$dsn = 'mysql:host=localhost;dbname=isler';
$username = 'root'; // Veritabanı kullanıcı adınızı girin
$password = ''; // Veritabanı şifrenizi girin

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Veritabanı bağlantısı başarısız: ' . $e->getMessage();
}
?>