<?php
// XAMPP'ın varsayılan veri tabanı giriş bilgileri
$host = 'localhost';
$dbname = 'lezzet_restoran';
$username = 'root'; 
$password = '';     // XAMPP'ta şifre varsayılan olarak boştur

try {
    // PDO ile güvenli veri tabanı bağlantısı kuruyoruz
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Hata yakalama modunu aktif ediyoruz ki sorun çıkarsa nerede olduğunu görelim
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    echo "Veri tabanı bağlantı hatası: " . $e->getMessage();
    exit();
}
?>