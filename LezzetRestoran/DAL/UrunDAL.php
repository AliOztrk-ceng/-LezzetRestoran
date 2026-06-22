<?php
require_once 'baglanti.php';

class UrunDAL {
    
    // Ürünleri Listeleme
    public static function urunleriGetir() {
        global $db;
        $sorgu = $db->prepare("CALL sp_UrunlerHepsi()");
        $sorgu->execute();
        return $sorgu->fetchAll(PDO::FETCH_ASSOC);
    }

    // Yeni Ürün Ekleme
    public static function urunEkle($urun_adi, $kategori, $birim_fiyat, $urun_detayi) {
        global $db;
        $sorgu = $db->prepare("CALL sp_UrunEkle(:adi, :kategori, :fiyat, :detay)");
        $sonuc = $sorgu->execute([
            ':adi' => $urun_adi, 
            ':kategori' => $kategori, 
            ':fiyat' => $birim_fiyat, 
            ':detay' => $urun_detayi
        ]);
        return $sonuc;
    }
    // Ürün Silme İşlemi
    public static function urunSil($urun_id) {
        global $db;
        try {
            $sorgu = $db->prepare("CALL sp_UrunSil(:id)");
            return $sorgu->execute([':id' => $urun_id]);
        } catch (PDOException $e) {
            // Eğer ürün daha önce bir masaya sipariş olarak girildiyse silinmesini engeller (Yabancı Anahtar Koruması)
            return false; 
        }
    }
}
?>