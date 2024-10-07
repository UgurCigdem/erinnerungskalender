<?php

$configArray = [
  // ------- API Zugangsdaten ------- 
  'API_BASE_URL' => 'http://localhost:5001/api/',
  'API_EMAIL' => 'testuser@example.com',
  'API_PASSWORD' => 'meinPasswort',
  // ------- SMTP Zugangsdaten -------
  'SMTP_HOST' => 'smtp.muster.at',
  'SMTP_USERNAME' => 'max@muster.at',
  'SMTP_PASSWORD' => 'maxMustermann',
  'SMTP_PORT' => 587,
  //------- Mail-Sender ------- 
  'ABSENDER_NAME' => 'Ihr Erinnerung-Service', // Name für die E-Mails
  'ABSENDER_EMAIL' => 'newapiservices@ugur.at',
  'EMPFAENGER_EMAIL' => 'newapiservices@ugur.at', // nur für Testmail
  'ERINNERUNG_BETREFF' => 'Erinnerung für ein Ereignis', 
  //'ERINNERUNG_DATUM' => '2024-11-05', // YYYY-MM-DD nur zum Testen
  //-------- Logg-Pfad -------
  'LOG_PATH' => 'logs', // Relativer Pfad für Log-Dateien | /private/logs/

];


return $configArray;