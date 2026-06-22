<?php
require_once 'baglanti.php';

class KullaniciDAL {
    public static function girisYap($kullanici_adi, $sifre) {
        global $db;
        // Kullanıcı adı ve şifre eşleşiyorsa o kullanıcının tüm bilgilerini getir
        $sorgu = $db->prepare("SELECT * FROM kullanicilar WHERE kullanici_adi = :kadi AND sifre = :sifre");
        $sorgu->execute([':kadi' => $kullanici_adi, ':sifre' => $sifre]);
        return $sorgu->fetch(PDO::FETCH_ASSOC);
    }
}
?>