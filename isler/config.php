
<?php
$dsn = 'mysql:host=localhost;dbname=isler;charset=utf8';
$username = 'root'; // MySQL kullanıcı adınızı buraya yazın
$password = ''; // MySQL şifrenizi buraya yazın

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Veritabanı bağlantı hatası: ' . $e->getMessage();
    exit();
}

?>