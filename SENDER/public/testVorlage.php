<?php

require_once __DIR__ . '/../private/services/LoggerService.php'; // LoggerService einbinden

$config = require __DIR__ . '/../private/config/config.php';
require __DIR__ . '/../private/classes/VorlageHelper.php';
require __DIR__ . '/../private/classes/EmailHelper.php';

$loggerService = new LoggerService("testVorlage.php", $config['LOG_PATH']);

try {

    $absenderEmail = $config["ABSENDER_EMAIL"];
    $absenderName = $config["ABSENDER_NAME"];
    $empfaengerEmail = $config['EMPFAENGER_EMAIL'];
    $betreff = $config['ERINNERUNG_BETREFF'];
    
    // Vorlage
    $vorlagenDatei = realpath(__DIR__ . '/../private/templates/temp2/template.html');

    if ($vorlagenDatei === false) {
        throw new Exception("Vorlagendatei nicht gefunden: " . $vorlagenDatei);
    }

    // Schlüsselwörter und Werte
    $platzhalter = [
        'Name' => 'Max Mustermann',
        'Beschreibung' => 'Arzttermin',
        'Datum' => VorlageHelper::datumFormatierenInDDMMYYYY('2024-10-15') ,
    ];

    // Vorlage laden und Platzhalter ersetzen
    $fertigeVorlage = VorlageHelper::ladeVorlage($vorlagenDatei, $platzhalter);

    // E-Mail-Parameter
    //$absenderEmail = 'absender@example.com';
    //$absenderName = 'Ihr Service';
    //$empfaengerEmail = 'empfaenger@example.com';
    //$betreff = 'Erinnerung für Ihren Termin';

    // E-Mail senden
    $result = EmailHelper::emailSender($absenderEmail, $absenderName, $empfaengerEmail, $betreff, $fertigeVorlage, true);

    // Loggen
    $loggerService->logEreignis("E-Mail erfolgreich gesendet an: " . $empfaengerEmail);

} catch (Exception $e) {
    // Fehlerbehandlung

    // Loggen
    $loggerService->logEreignis("Fehler beim Senden der E-Mail: " . $e->getMessage());
    
    echo "Fehler: " . $e->getMessage();
}

?>

<h1>Testmail mit Vorlage</h1>

Von: <?=$absenderEmail?> | <?=$absenderName?> <br />
An: <?=$empfaengerEmail ?> <br />

<hr />

<?php

// Ergebniss überprüfen
if (isset($result['message'])) {
    // Erfolgsnachricht ausgeben
    echo $result['message'];
} else {
    echo $result['error'];
}

?>

<hr />

<?=$fertigeVorlage?>

<hr />