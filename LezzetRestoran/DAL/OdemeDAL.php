<?php
require_once 'baglanti.php';

class OdemeDAL {
    
    // Ödemeleri Listeleme
    public static function odemeleriGetir() {
        global $db;
        $sorgu = $db->prepare("CALL sp_OdemeDetay()");
        $sorgu->execute();
        return $sorgu->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ödeme Ekleme (Masa Boş Yapma Trigger'ını Tetikler)
  
public static function odemeEkle($masa_id, $odeme_tutari, $odeme_turu) {
    global $db;
    try {
        $db->beginTransaction(); // İşlemi bir paket yapıyoruz

        // 1. Ödemeyi Kaydet
        $sorgu1 = $db->prepare("CALL sp_OdemeEkle(:masa_id, :odeme_tutari, :odeme_turu)");
        $sorgu1->execute([':masa_id' => $masa_id, ':odeme_tutari' => $odeme_tutari, ':odeme_turu' => $odeme_turu]);

        // 2. O Masanın Tüm Siparişlerini Sil (Hesabı Sıfırlamak İçin)
        $sorgu2 = $db->prepare("DELETE FROM siparisler WHERE masa_id = :masa_id");
        $sorgu2->execute([':masa_id' => $masa_id]);

        $db->commit(); // Paket başarıyla bitti
        return true;
    } catch (PDOException $e) {
        $db->rollBack(); // Hata olursa başa dön
        return false;
    }
}
}
?>
