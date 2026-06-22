<?php
session_start();
if (!isset($_SESSION['kullanici_id'])) { header("Location: login.php"); exit(); }

require_once '../BLL/UrunBLL.php';

$mesaj = "";

// 1. YENİ ÜRÜN EKLEME İŞLEMİ
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['urun_ekle'])) {
    $ad = $_POST['urun_adi'];
    $kategori = $_POST['kategori'];
    $fiyat = $_POST['birim_fiyat'];
    $detay = $_POST['urun_detayi'];

    $eklemeDurumu = UrunBLL::urunEkleBLL($ad, $kategori, $fiyat, $detay);
    if ($eklemeDurumu === true || $eklemeDurumu == 1) {
        $mesaj = "<div style='color: #27ae60; font-weight:bold; margin-bottom:15px;'>Ürün başarıyla menüye eklendi!</div>";
    } else {
        $mesaj = "<div style='color: #c0392b; font-weight:bold; margin-bottom:15px;'>$eklemeDurumu</div>";
    }
}

// 2. ÜRÜN SİLME İŞLEMİ (YENİ EKLENEN KISIM)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['urun_sil'])) {
    $sil_id = $_POST['silinecek_urun_id'];
    
    $silmeDurumu = UrunBLL::urunSilBLL($sil_id);
    if ($silmeDurumu === true) {
        $mesaj = "<div style='color: #27ae60; font-weight:bold; margin-bottom:15px;'>Ürün başarıyla menüden silindi!</div>";
    } else {
        $mesaj = "<div style='color: #c0392b; font-weight:bold; margin-bottom:15px;'>$silmeDurumu</div>";
    }
}

// Güncel listeyi çek
$urunler = UrunBLL::urunleriGetirBLL();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Lezzet Restoran - Ürünler</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .urun-yonetim { display: flex; gap: 30px; margin-top: 20px; }
        .urun-formu { background: white; padding: 20px; border-radius: 10px; border-top: 5px solid #d35400; width: 30%; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .urun-formu input, .urun-formu select { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        .urun-formu button { width: 100%; padding: 10px; background-color: #d35400; color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; }
        .urun-formu button:hover { background-color: #e67e22; }
        .urun-liste { width: 70%; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 12px; text-align: left; }
        th { background-color: #fdf5e6; color: #d35400; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .btn-sil { background-color: #c0392b; color: white; border: none; padding: 6px 12px; border-radius: 3px; cursor: pointer; font-weight: bold; }
        .btn-sil:hover { background-color: #e74c3c; }
    </style>
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
        <h2>Ürün Yönetimi</h2>
        <p>Restoran menüsünü buradan güncelleyebilir veya silebilirsiniz.</p>
        
        <?php echo $mesaj; ?>

        <div class="urun-yonetim">
            
            <div class="urun-formu">
                <h3>Yeni Ürün Ekle</h3>
                <form method="POST" action="">
                    <input type="text" name="urun_adi" placeholder="Örn: İskender Kebap" required>
                    <select name="kategori" required>
                        <option value="">Kategori Seçin</option>
                        <option value="Yemek">Yemek</option>
                        <option value="İçecek">İçecek</option>
                        <option value="Tatlı">Tatlı</option>
                        <option value="Aperatif">Aperatif</option>
                    </select>
                    <input type="number" step="0.01" name="birim_fiyat" placeholder="Fiyat (TL)" required>
                    <input type="text" name="urun_detayi" placeholder="Açıklama (İsteğe Bağlı)">
                    <button type="submit" name="urun_ekle">Menüye Ekle</button>
                </form>
            </div>

            <div class="urun-liste">
                <h3>Güncel Menü</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Ürün Kodu</th>
                            <th>Ürün Adı</th>
                            <th>Kategori</th>
                            <th>Fiyat</th>
                            <th>İşlem</th> </tr>
                    </thead>
                    <tbody>
                        <?php if($urunler): ?>
                            <?php foreach($urunler as $urun): ?>
                                <tr>
                                    <td>#<?php echo $urun['urun_id']; ?></td>
                                    <td><strong><?php echo $urun['urun_adi']; ?></strong></td>
                                    <td><?php echo $urun['kategori']; ?></td>
                                    <td><?php echo number_format($urun['birim_fiyat'], 2); ?> TL</td>
                                    <td>
                                        <form method="POST" action="" style="margin: 0;">
                                            <input type="hidden" name="silinecek_urun_id" value="<?php echo $urun['urun_id']; ?>">
                                            <button type="submit" name="urun_sil" class="btn-sil">Sil</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align:center;">Menüde henüz ürün bulunmamaktadır.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</body>
</html>