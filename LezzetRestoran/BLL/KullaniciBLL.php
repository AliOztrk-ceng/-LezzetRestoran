<?php
require_once '../DAL/KullaniciDAL.php';

class KullaniciBLL {
    public static function girisKontrol($kullanici_adi, $sifre) {
        if (empty(trim($kullanici_adi)) || empty(trim($sifre))) {
            return "Hata: Kullanıcı adı ve şifre boş bırakılamaz!";
        }
        
        $kullanici = KullaniciDAL::girisYap($kullanici_adi, $sifre);
        
        if ($kullanici) {
            // Başarılı giriş: PHP Session (Oturum) başlat ve bilgileri kaydet
            session_start();
            $_SESSION['kullanici_id'] = $kullanici['kullanici_id'];
            $_SESSION['kullanici_adi'] = $kullanici['kullanici_adi'];
            $_SESSION['rol'] = $kullanici['rol'];
            return true;
        } else {
            return "Hata: Hatalı kullanıcı adı veya şifre!";
        }
    }
}
?>