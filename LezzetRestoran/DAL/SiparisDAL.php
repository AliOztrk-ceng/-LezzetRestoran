<?php
require_once 'baglanti.php';

class SiparisDAL {
    
    // Siparişleri Listeleme
    public static function siparisleriGetir() {
        global $db;
        $sorgu = $db->prepare("CALL sp_SiparisDetay()");
        $sorgu->execute();
        return $sorgu->fetchAll(PDO::FETCH_ASSOC);
    }

    // Yeni Sipariş Ekleme (Masa Dolu Yapma Trigger'ını Tetikler)
    public static function siparisEkle($masa_id, $urun_id, $adet, $toplam_fiyat) {
        global $db;
        $sorgu = $db->prepare("CALL sp_SiparisEkle(:masa_id, :urun_id, :adet, :toplam_fiyat)");
        $sonuc = $sorgu->execute([
            ':masa_id' => $masa_id, 
            ':urun_id' => $urun_id, 
            ':adet' => $adet, 
            ':toplam_fiyat' => $toplam_fiyat
        ]);
        return $sonuc;
    }
}
?>