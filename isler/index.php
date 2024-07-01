<?php
require 'config.php';

// Sayfalama için gerekli değişkenler
$limit = 20; // Her sayfada gösterilecek satır sayısı
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Arama sorgusu
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Veritabanından verileri çekme
$sql = "SELECT * FROM isler_tb WHERE sube LIKE :search OR kisi LIKE :search OR iletisim LIKE :search OR konu LIKE :search ORDER BY tarih DESC LIMIT $start, $limit"; // Tarihe göre ters sıralama
$stmt = $pdo->prepare($sql);
$stmt->execute([':search' => "%$search%"]);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Toplam satır sayısını hesaplama
$sql_total = "SELECT COUNT(*) FROM isler_tb WHERE sube LIKE :search OR kisi LIKE :search OR iletisim LIKE :search OR konu LIKE :search";
$stmt_total = $pdo->prepare($sql_total);
$stmt_total->execute([':search' => "%$search%"]);
$total_results = $stmt_total->fetchColumn();
$total_pages = ceil($total_results / $limit);

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Günlük İşler</title>

    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .pagination {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
        }
        .pagination a {
            margin: 2px;
            padding: 8px 16px;
            text-decoration: none;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .pagination a:hover {
            background-color: #ddd;
        }
        .pagination a.active {
            background-color: #4CAF50;
            color: white;
            border: 1px solid #4CAF50;
        }
        form {
            margin-bottom: 20px;
        }
        input[type="text"], input[type="date"], select {
            padding: 8px;
            margin: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .status-completed {
            background-color: green;
            color: white;
            padding: 5px;
            border-radius: 3px;
        }
        .status-not-completed {
            background-color: red;
            color: white;
            padding: 5px;
            border-radius: 3px;
        }
        .status-info {
            background-color: yellow;
            color: black;
            padding: 5px;
            border-radius: 3px;
        }
        .delete-button {
            background-color: #45474B; /* Kırmızı renk */
            color: white;
            border: none;
            padding: 8px 12px;
            text-align: center;
            display: inline-block;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .delete-button:hover {
            background-color: #973131; /* Koyu kırmızı renk */
        }


        input[type="submit"] {
            background-color: #4CAF50; /* Yeşil renk */
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            display: inline-block;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #45a049; /* Koyu yeşil renk */
        }
    </style>
    <script>
        function confirmDelete(id) {
            if (confirm('Bu kaydı silmek istediğinizden emin misiniz?')) {
                // AJAX ile silme işlemi
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (xhr.status >= 200 && xhr.status < 400) {
                        window.location.reload();
                    }
                };
                xhr.send('id=' + id);
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Günlük İşler</h2>

        <!-- Ekleme Formu -->
        <form action="insert.php" method="post">
            <input type="date" name="tarih" required>
            <input type="text" name="sube" placeholder="Şube" required>
            <input type="text" name="kisi" placeholder="Kişi" required>
            <input type="text" name="iletisim" placeholder="İletişim" maxlength="11" required>
            <input type="text" name="konu" placeholder="Konu" required>
            <select name="durum" required>
                <option value="Tamamlandı">Tamamlandı</option>
                <option value="Tamamlanmadı">Tamamlanmadı</option>
                <option value="Bilgi">Bilgi</option>
            </select>
            <input type="submit" value="Ekle">
        </form>

        <!-- Arama Formu -->
        <form method="get" action="">
            <input type="text" name="search" placeholder="Ara" value="<?php echo htmlspecialchars($search); ?>">
        </form>

        <!-- Veri Tablosu -->  
        <table id="taskTable">
            <thead>
                <tr>
                    <th>Tarih</th>
                    <th>Şube</th>
                    <th>Kişi</th>
                    <th>İletişim</th>
                    <th>Konu</th>
                    <th>Durum</th>
                    <th>İşlemler</th> <!-- Yeni sütun -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row): ?>
                    <tr>
                        <td contenteditable="true" data-id="<?php echo $row['id']; ?>" data-column="tarih"><?php echo htmlspecialchars($row['tarih']); ?></td>
                        <td contenteditable="true" data-id="<?php echo $row['id']; ?>" data-column="sube"><?php echo htmlspecialchars($row['sube']); ?></td>
                        <td contenteditable="true" data-id="<?php echo $row['id']; ?>" data-column="kisi"><?php echo htmlspecialchars($row['kisi']); ?></td>
                        <td contenteditable="true" data-id="<?php echo $row['id']; ?>" data-column="iletisim"><?php echo htmlspecialchars($row['iletisim']); ?></td>
                        <td contenteditable="true" data-id="<?php echo $row['id']; ?>" data-column="konu"><?php echo htmlspecialchars($row['konu']); ?></td>
                        <td class="<?php echo getStatusClass($row['durum']); ?>">
                            <form method="post" action="update_status.php">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <select name="durum" onchange="this.form.submit()">
                                    <option value="Tamamlandı" <?php if ($row['durum'] == 'Tamamlandı') echo 'selected'; ?>>Tamamlandı</option>
                                    <option value="Tamamlanmadı" <?php if ($row['durum'] == 'Tamamlanmadı') echo 'selected'; ?>>Tamamlanmadı</option>
                                    <option value="Bilgi" <?php if ($row['durum'] == 'Bilgi') echo 'selected'; ?>>Bilgi</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <button class="delete-button" data-id="<?php echo $row['id']; ?>" onclick="confirmDelete(<?php echo $row['id']; ?>)">Sil</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var taskTable = document.getElementById('taskTable');
                
                taskTable.addEventListener('blur', function (event) {
                    var target = event.target;
                    if (target.hasAttribute('contenteditable')) {
                        var id = target.getAttribute('data-id');
                        var column = target.getAttribute('data-column');
                        var value = target.innerText.trim();
                        
                        // AJAX ile güncelleme işlemi
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', 'update.php', true);
                        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                        xhr.onload = function () {
                            if (xhr.status >= 200 && xhr.status < 400) {
                                // Başarılı güncelleme durumunda gerekiyorsa bir işlem yapabilirsiniz
                                console.log('Güncelleme başarılı');
                            } else {
                                console.error('Güncelleme başarısız: ' + xhr.statusText);
                            }
                        };
                        xhr.send('id=' + id + '&column=' + column + '&value=' + encodeURIComponent(value));
                        
                        // Sayfanın yenilenmesini önlemek için
                        event.preventDefault();
                    }
                });
            });
            function confirmDelete(id) {
                if (confirm('Bu kaydı silmek istediğinizden emin misiniz?')) {
                    // AJAX ile silme işlemi
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'delete.php', true);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xhr.onload = function () {
                        if (xhr.status >= 200 && xhr.status < 400) {
                            window.location.reload();
                        }
                    };
                    xhr.send('id=' + id);
                }
            }
        </script>

        <?php
        // Durumun CSS class'ını döndüren yardımcı fonksiyon
        function getStatusClass($durum) {
            switch ($durum) {
                case 'Tamamlandı':
                    return 'status-completed';
                case 'Tamamlanmadı':
                    return 'status-not-completed';
                case 'Bilgi':
                    return 'status-info';
                default:
                    return ''; // Varsayılan olarak boş döner
            }
        }
        ?>

        <!-- Sayfalama -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>" class="<?php if ($i == $page) echo 'active'; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>
    </div>
</body>
</html>