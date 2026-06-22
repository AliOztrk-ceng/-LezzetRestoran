<?php
session_start();
// Güvenlik: Giriş yapılmamışsa login sayfasına atar
if (!isset($_SESSION['kullanici_id'])) { header("Location: login.php"); exit(); }

require_once '../BLL/MasaBLL.php';
$masalar = MasaBLL::masalariGetirBLL();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Lezzet Restoran</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

   <div class="navbar">
        <h1>Lezzet Restoran</h1>
        <ul>
            <li><a href="index.php">Masalar</a></li>
            <li><a href="urunler.php">Ürünler</a></li>
            <li><a href="cikis.php">Çıkış Yap</a></li>
        </ul>
    </div>

    <div class="icerik">
        <h2>Masa Durumları</h2>
        <p>Adisyon girmek veya hesap almak için bir masaya tıklayın.</p>
        
        <div class="masa-kapsayici">
            <?php if($masalar): ?>
                <?php foreach($masalar as $masa): ?>
                    
                    <?php 
                        // Masa boşsa yeşil sınıfı, doluysa kırmızı sınıfı ata
                        $renkSinifi = ($masa['masa_durumu'] == 'Boş') ? 'masa-bos' : 'masa-dolu'; 
                    ?>
                    
                    <a href="masa_detay.php?id=<?php echo $masa['masa_id']; ?>" style="text-decoration: none; color: inherit;">
    <div class="masa-kutu <?php echo $renkSinifi; ?>">
        Masa <?php echo $masa['masa_no']; ?>
        <div class="durum-yazisi"><?php echo $masa['masa_durumu']; ?></div>
    </div>
</a>
                    
                <?php endforeach; ?>
            <?php else: ?>
                <p>Henüz sisteme masa eklenmemiş.</p>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
