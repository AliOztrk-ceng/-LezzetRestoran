<?php
// Veritabanı bağlantımızı bu dosyaya dahil ediyoruz
require_once 'baglanti.php';

class MasaDAL {
    
    // 1. Masaları Listeleme (Arayüzde masaları yeşil/kırmızı göstermek için)
    public static function masalariGetir() {
        global $db;
        try {
            // Doğrudan SQL yazmıyoruz, hazırladığımız Procedure'ü CALL ile çağırıyoruz!
            $sorgu = $db->prepare("CALL sp_MasalarHepsi()");
            $sorgu->execute();
            return $sorgu->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    // 2. Belirli Bir Masanın Hesabını Getirme (Yazdığımız Fonksiyonu Kullanarak)
    public static function masaHesabiGetir($masa_id) {
        global $db;
        try {
            // Burada da fn_MasaHesap fonksiyonumuzu çağırıyoruz
            $sorgu = $db->prepare("SELECT fn_MasaHesap(:masa_id) AS guncel_hesap");
            $sorgu->bindParam(':masa_id', $masa_id, PDO::PARAM_INT);
            $sorgu->execute();
            $sonuc = $sorgu->fetch(PDO::FETCH_ASSOC);
            return $sonuc['guncel_hesap'];
        } catch (PDOException $e) {
            return 0;
        }
    }

    // 3. Sipariş Girildiğinde veya Kapatıldığında Masa Durumunu Kontrol Etme
    // Not: Masa durumlarını 'Dolu' veya 'Boş' yapma işini MySQL'deki TRIGGER'lara 
    // bıraktığımız için buraya ekstra bir güncelleme kodu yazmamıza gerek kalmadı!
}
?>