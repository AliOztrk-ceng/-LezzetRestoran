<?php
require_once '../DAL/SiparisDAL.php';

class SiparisBLL {
    
    public static function siparisleriGetirBLL() {
        return SiparisDAL::siparisleriGetir();
    }

    public static function siparisEkleBLL($masa_id, $urun_id, $adet, $toplam_fiyat) {
        // Kural 1: Masa veya Ürün seçilmemiş olamaz!
        if (empty($masa_id) || empty($urun_id)) {
            return "Hata: Lütfen geçerli bir masa ve ürün seçiniz!";
        }

        // Kural 2: Sipariş adedi ve toplam fiyat mantıksız olamaz!
        if ($adet <= 0 || $toplam_fiyat <= 0) {
            return "Hata: Sipariş adedi ve fiyat 0'dan büyük olmalıdır!";
        }

        return SiparisDAL::siparisEkle($masa_id, $urun_id, $adet, $toplam_fiyat);
    }
}
?>