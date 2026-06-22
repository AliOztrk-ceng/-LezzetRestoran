<?php
// BLL, DAL ile iletişim kuracağı için MasaDAL dosyamızı çağırıyoruz
// '../' ifadesi bir üst klasöre çıkıp DAL klasörüne girmemizi sağlar
require_once '../DAL/MasaDAL.php';

class MasaBLL {
    
    // 1. Masaları Listeleme Kontrolü
    public static function masalariGetirBLL() {
        // Burada ileride "Sadece yetkili kullanıcılar masaları görebilir" gibi 
        // güvenlik kuralları eklenebilir. Şimdilik doğrudan DAL'a yönlendiriyoruz.
        return MasaDAL::masalariGetir();
    }

    // 2. Masa Hesabını Getirme Kontrolü
    public static function masaHesabiGetirBLL($masa_id) {
        // Kural: Masa ID boş olamaz veya 0'dan küçük olamaz!
        if (empty($masa_id) || $masa_id <= 0) {
            return "Geçersiz Masa Numarası!";
        }
        
        // Şartlar sağlandıysa veri tabanından hesabı çekmek üzere DAL'a gönder
        return MasaDAL::masaHesabiGetir($masa_id);
    }
}
?>