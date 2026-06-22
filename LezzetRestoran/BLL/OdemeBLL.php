<?php
require_once '../DAL/OdemeDAL.php';

class OdemeBLL {
    
    public static function odemeleriGetirBLL() {
        return OdemeDAL::odemeleriGetir();
    }

    public static function odemeEkleBLL($masa_id, $odeme_tutari, $odeme_turu) {
        // Kural 1: Geçersiz bir masa ID veya negatif bir tutar girilemez!
        if (empty($masa_id) || $odeme_tutari <= 0) {
            return "Hata: Geçersiz masa veya ödeme tutarı!";
        }

        // Kural 2: Ödeme türü sadece veri tabanındaki CHECK kısıtlamasına uygun olabilir!
        if ($odeme_turu !== 'Nakit' && $odeme_turu !== 'Kredi Kartı') {
            return "Hata: Geçersiz ödeme türü seçildi!";
        }

        return OdemeDAL::odemeEkle($masa_id, $odeme_tutari, $odeme_turu);
    }
}
?>