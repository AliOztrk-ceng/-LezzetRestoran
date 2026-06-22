<?php

$host = 'localhost';
$dbname = 'lezzet_restoran';
$username = 'root'; 
$password = '';     

try {
    
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
   
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    echo "Veri tabanı bağlantı hatası: " . $e->getMessage();
    exit();
}
?>
