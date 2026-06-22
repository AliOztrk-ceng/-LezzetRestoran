<?php
session_start();

// Kullanıcı zaten giriş yapmışsa, tekrar bu sayfayı görmesin, direkt masalara gitsin
if (isset($_SESSION['kullanici_id'])) {
    header("Location: index.php");
    exit();
}


require_once '../BLL/KullaniciBLL.php';

$mesaj = "";

// Formdan giriş yap butonuna basıldığında çalışacak kodlar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kadi = $_POST['kullanici_adi'];
    $sifre = $_POST['sifre'];
    
    
    $girisDurumu = KullaniciBLL::girisKontrol($kadi, $sifre);
    
    if ($girisDurumu === true) {
        // Her şey doğruysa ana sayfaya fırlat
        header("Location: index.php");
        exit();
    } else {
        
        $mesaj = $girisDurumu;
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Lezzet Restoran - Giriş Yap</title>
    <style>
       
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #fdf5e6; 
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; 
        }
        .giris-kutusu {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
            width: 300px;
            border-top: 5px solid #d35400; 
        }
        .giris-kutusu h2 {
            color: #d35400;
            margin-bottom: 20px;
        }
        .giris-kutusu input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box; 
        }
        .giris-kutusu button {
            width: 100%;
            padding: 10px;
            background-color: #d35400;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
        }
        .giris-kutusu button:hover {
            background-color: #e67e22; 
        }
        .hata {
            color: #c0392b;
            font-size: 14px;
            margin-bottom: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="giris-kutusu">
        <h2>Sisteme Giriş</h2>
        
        <?php if($mesaj != ""): ?>
            <div class="hata"><?php echo $mesaj; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="kullanici_adi" placeholder="Kullanıcı Adı" required>
            <input type="password" name="sifre" placeholder="Şifre" required>
            <button type="submit">Giriş Yap</button>
        </form>
    </div>

</body>
</html>
