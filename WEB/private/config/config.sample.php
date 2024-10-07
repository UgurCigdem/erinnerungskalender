<?php


$configArray = [
    // ------- API Zugangsdaten ------- 
    'API_BASE_URL' => 'http://localhost:5001/api/',
    'API_EMAIL' => 'testuser@example.com', // Benutzer mit admin Rechte
    'API_PASSWORD' => 'meinPasswort',
    // ------- SMTP Zugangsdaten ------- 
    'SMTP_HOST' => 'smtp.services.com',
    'SMTP_USERNAME' => 'office@services.com',
    'SMTP_PASSWORD' => '***',
    'SMTP_PORT' => 587,
    //------- Mail-Sender ------- 
    'ABSENDER_NAME' => 'Ihr Erinnerung-Service', // Name für die E-Mails
    'ABSENDER_EMAIL' => 'newapiservices@services.com',
    'EMPFAENGER_EMAIL' => 'newapiservices@services.com', // nur für Testmail
    'ERINNERUNG_BETREFF' => 'Erinnerung für ein Ereignis', 
    //'ERINNERUNG_DATUM' => '2024-11-05', // YYYY-MM-DD  nur zum Testen
    //-------- Logg-Pfad -------
    'LOG_PATH' => 'logs', // Relativer Pfad für Log-Dateien | /private/logs/
    //-------- Token -------
    'TOKEN_BASE_URL' => 'http://localhost', // URL für JWT
    'TOKEN_KEY' => 'geheimesSchluessel',
  
  ];

  





use Firebase\JWT\JWT;

if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Session starten, wenn keine Session aktiv ist
}

// CSRF-Token generieren, später über meta-Eintrag zu Ajax übergeben!
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); 
}

  return $configArray;
?>