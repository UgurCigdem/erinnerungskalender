<?php

$configArray = [
    // ------- DB Zugangsdaten ------- 
    'DB_HOST' => 'localhost',
    'DB_NAME' => 'dbErinnerung', 
    'DB_USER' => 'root',
    'DB_PASSWORD' => '',
    //-------- Token -------
    'TOKEN_BASE_URL' => 'http://localhost', // URL für JWT
    'TOKEN_KEY' => 'geheimesSchluessel',
    //-------- Logg-Pfad -------
    'LOG_PATH' => 'logs', // Relativer Pfad für Log-Dateien | /private/logs/
];






use Firebase\JWT\JWT;

// DB Verbindung
try {
    //$conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn = new PDO("mysql:host=" . $configArray['DB_HOST'] . ";dbname=" . $configArray['DB_NAME'], $configArray['DB_USER'], $configArray['DB_PASSWORD']);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Verbindung fehlgeschlagen: " . $e->getMessage());
}

// Session starten, wenn keine Session aktiv ist
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}

// CSRF-Token generieren, später über meta-Eintrag zu Ajax übergeben!
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); 
}


return $configArray;
