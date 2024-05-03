<?php
    $dsn = "mysql:host=localhost;dbname=lagdatabase";
    if (isset($_SESSION['Admin']) && $_SESSION['Admin'] == 1){
        $dbusername = "adminUser";
        $dbpassword = "admin123";
    } else {
        // $dbusername = "GamerUser";
        // $dbpassword = "gamer123";
        $dbusername = "adminUser";
        $dbpassword = "admin123";
    }
    try {
        $pdo = new PDO($dsn, $dbusername, $dbpassword); 
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    } catch (PDOExecption $e){
        echo "Connection Error: " . $e->getMessage(); 
    }