<?php
session_start();
if (!isset($_SESSION['kullanici_id'])) { header("Location: login.php"); exit(); }

require_once '../BLL/MasaBLL.php';
require_once '../BLL/UrunBLL.php';
require_once '../BLL/SiparisBLL.php';
require_once '../BLL/OdemeBLL.php';

// Linkten gelen masa ID'sini al (Yoksa ana sayfaya at)
$masa_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($masa_id == 0) { header("Location: index.php"); exit(); }

// Sayfa yüklendiğinde menüdeki ürünleri çekiyoruz
$urunler = UrunBLL::urunleriGetirBLL();


$guncelHesap = MasaBLL::masaHesabiGetirBLL($masa_id); 
if (!$guncelHesap) $guncelHesap = 0; // Eğer sipariş yoksa hesap 0'dır

$mesaj = "";

// Butonlara tıklandığında çalışacak form işlemleri
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. SİPARİŞ EKLE BUTONUNA BASILDIYSA
    if (isset($_POST['siparis_ekle'])) {
        $urun_id = $_POST['urun_id'];
        $adet = $_POST['adet'];
        
        // Seçilen ürünün fiyatını bul ve toplam fiyatı hesapla
        $birim_fiyat = 0;
        foreach ($urunler as $u) {
            if ($u['urun_id'] == $urun_id) { $birim_fiyat = $u['birim_fiyat']; break; }
        }
        $toplam_fiyat = $birim_fiyat * $adet;
        
        // Siparişi gir! (Arka planda MySQL Trigger'ı çalışıp masayı 'Dolu' yapacak)
        $sonuc = SiparisBLL::siparisEkleBLL($masa_id, $urun_id, $adet, $toplam_fiyat);
        if ($sonuc === true || $sonuc == 1) {
            header("Location: masa_detay.php?id=$masa_id"); // Sayfayı yenile ki hesap güncellensin
            exit();
        } else {
            $mesaj = "<div style='color: red;'>$sonuc</div>";
        }
    }
    
    // 2. HESABI KAPAT BUTONUNA BASILDIYSA
    if (isset($_POST['hesap_kapat'])) {
        $odeme_turu = $_POST['odeme_turu'];
        
        if ($guncelHesap > 0) {
            // Ödemeyi al! (Arka planda MySQL Trigger'ı çalışıp masayı 'Boş' yapacak)
            $sonuc = OdemeBLL::odemeEkleBLL($masa_id, $guncelHesap, $odeme_turu);
            if ($sonuc === true || $sonuc == 1) {
                header("Location: index.php"); // İşlem bitti, ana sayfaya dön
                exit();
            }
        } else {
            $mesaj = "<div style='color: red;'>Ödenecek hesap bulunmamaktadır!</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Lezzet Restoran - Masa Detayı</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .detay-kapsayici { display: flex; gap: 30px; margin-top: 20px; }
        .panel { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 50%; }
        .panel-sol { border-top: 5px solid #27ae60; } /* Yeşil vurgu */
        .panel-sag { border-top: 5px solid #d35400; text-align: center; } /* Kiremit vurgu */
        .panel input, .panel select { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        .btn { width: 100%; padding: 12px; color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 16px; margin-top: 10px; }
        .btn-yesil { background-color: #27ae60; }
        .btn-yesil:hover { background-color: #2ecc71; }
        .btn-kirmizi { background-color: #c0392b; }
        .btn-kirmizi:hover { background-color: #e74c3c; }
        .hesap-tutari { font-size: 48px; font-weight: bold; color: #d35400; margin: 20px 0; }
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
        <h2>Masa İşlemleri</h2>
        <p><a href="index.php" style="color: #d35400; text-decoration: none;">&larr; Masalara Dön</a></p>
        
        <?php if($mesaj != "") echo $mesaj; ?>

        <div class="detay-kapsayici">
            
            <div class="panel panel-sol">
                <h3>Adisyona Ürün Ekle</h3>
                <form method="POST" action="">
                    <select name="urun_id" required>
                        <option value="">Lütfen Ürün Seçin...</option>
                        <?php foreach($urunler as $urun): ?>
                            <option value="<?php echo $urun['urun_id']; ?>">
                                <?php echo $urun['urun_adi']; ?> - <?php echo number_format($urun['birim_fiyat'], 2); ?> TL
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <input type="number" name="adet" value="1" min="1" required placeholder="Adet">
                    
                    <button type="submit" name="siparis_ekle" class="btn btn-yesil">Siparişi Gir</button>
                </form>
            </div>

            <div class="panel panel-sag">
                <h3>Güncel Hesap</h3>
                <div class="hesap-tutari"><?php echo number_format($guncelHesap, 2); ?> TL</div>
                
                <form method="POST" action="">
                    <select name="odeme_turu" required <?php echo ($guncelHesap <= 0) ? 'disabled' : ''; ?>>
                        <option value="Nakit">Nakit Ödeme</option>
                        <option value="Kredi Kartı">Kredi Kartı</option>
                    </select>
                    
                    <button type="submit" name="hesap_kapat" class="btn btn-kirmizi" <?php echo ($guncelHesap <= 0) ? 'disabled' : ''; ?>>
                        Hesabı Kapat ve Masayı Boşalt
                    </button>
                </form>
            </div>

        </div>
    </div>

</body>
</html>
