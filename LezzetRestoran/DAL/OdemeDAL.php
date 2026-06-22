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
        $sorgu = $db->prepare("CALL sp_OdemeEkle(:masa_id, :odeme_tutari, :odeme_turu)");
        $sonuc = $sorgu->execute([
            ':masa_id' => $masa_id, 
            ':odeme_tutari' => $odeme_tutari, 
            ':odeme_turu' => $odeme_turu
        ]);
        return $sonuc;
    }
}
?>