<?php
require_once '../DAL/UrunDAL.php';

class UrunBLL {
    
    public static function urunleriGetirBLL() {
        return UrunDAL::urunleriGetir();
    }

    public static function urunEkleBLL($urun_adi, $kategori, $birim_fiyat, $urun_detayi) {
        // Kural 1: Ürün adı veya kategori boş bırakılamaz!
        if (empty(trim($urun_adi)) || empty(trim($kategori))) {
            return "Hata: Ürün adı ve kategori alanları boş geçilemez!";
        }
        
        // Kural 2: Ürünün fiyatı 0 veya eksi olamaz!
        if ($birim_fiyat <= 0) {
            return "Hata: Ürün fiyatı 0'dan büyük olmalıdır!";
        }

        // Bütün kurallar sağlandıysa veriyi kaydetmek için DAL'a gönder
        return UrunDAL::urunEkle($urun_adi, $kategori, $birim_fiyat, $urun_detayi);
    }
    // Ürün Silme Kontrolü
    public static function urunSilBLL($urun_id) {
        if (empty($urun_id) || $urun_id <= 0) {
            return "Hata: Geçersiz ürün seçimi!";
        }
        
        $sonuc = UrunDAL::urunSil($urun_id);
        
        if ($sonuc) {
            return true;
        } else {
            return "Hata: Bu ürün aktif bir siparişte kullanıldığı için silinemez!";
        }
    }
}
?>