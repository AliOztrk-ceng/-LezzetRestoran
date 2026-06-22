<?php
session_start(); // Güvenlik görevlisini çağır

// Kullanıcının cebindeki tüm biletleri (oturum verilerini) yırtıp at
session_destroy(); 

// Onu anında giriş gişesine (login.php) geri fırlat
header("Location: login.php");
exit();
?>