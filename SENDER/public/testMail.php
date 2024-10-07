<?php

require_once __DIR__ . '/../private/services/LoggerService.php';

$config = require __DIR__ . '/../private/config/config.php';
require __DIR__ . '/../private/classes/EmailHelper.php';

$loggerService = new LoggerService("testMail.php", $config['LOG_PATH']);

try {

    $absenderEmail = $config['ABSENDER_EMAIL'];
    $absenderName = $config['ABSENDER_NAME'];
    $empfaengerEmail = $config['EMPFAENGER_EMAIL'];

    // in HTMLformat
    $body = "<h1>Willkommen</h1><p>Dies ist eine <b>Test-E-Mail</b>.</p><br>Danke!";
    $result = EmailHelper::emailSender($absenderEmail, $absenderName, $empfaengerEmail, 'Test-E-Mail', $body, true);

    // Loggen
    $loggerService->logEreignis("E-Mail erfolgreich gesendet: Von = $absenderEmail, An = $empfaengerEmail");
    
} catch (Exception $e) {
    // Fehlerbehandlung

    // Loggen
    $loggerService->logEreignis("Fehler beim Senden der E-Mail: " . $e->getMessage());

    echo "Fehler: " . $e->getMessage();
}
?>

<h1>Testmail Sender</h1>

Von: <?=$absenderEmail?> | <?=$absenderName?> <br />
An: <?=$empfaengerEmail ?> <br />

<hr>

<?php
// Ergebniss überprüfen
if (isset($result['message'])) {
    // Erfolgsnachricht ausgeben
    echo $result['message'];
} else {
    echo $result['error'];
}
?>