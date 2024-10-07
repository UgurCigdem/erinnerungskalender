<?php


$config = require_once __DIR__ . '/../private/config/config.php';
require_once __DIR__ . '/../private/classes/VorlageHelper.php';
require_once __DIR__ . '/../private/services/EmailService.php';
require_once __DIR__ . '/../private/services/ApiService.php';  

require_once __DIR__ . '/../private/services/LoggerService.php';  // LoggerService einbinden




$loggerService = new LoggerService("erinnerungenSenden.php", $config['LOG_PATH']);

$emailService = new EmailService($config);

// E-Mail-Parameter
$betreff = $config["ERINNERUNG_BETREFF"];

try {

    // Der Service meldet sich mit den Zugangsdaten aus der Config-Datei an
    // und ein Token wird aus der API ausgelesen, das
    // bei den folgenden Aufrufen automatisch eingesetzt wird.
    $apiService = new ApiService();

    // Erinnerungen abrufen
    $erinnerungen = $apiService->getErinnerungen($config['ERINNERUNG_DATUM']);

    $vorlagenDatei = realpath(__DIR__ . '/../private/templates/temp2/template.html');

    // Erinnerungen durchlaufen, Emails versenden
    foreach ($erinnerungen as $erinnerung) {

        try {

            $empfaengerEmail = $erinnerung['Email'];
            $erinnerungId = $erinnerung['ErinnerungId'];

            // Schlüsselwörter und Werte für die E-Mail
            $platzhalter = [
                'Name' => $erinnerung['Name'],
                'Beschreibung' => $erinnerung['Bezeichnung'],
                'Datum' => VorlageHelper::datumFormatierenInDDMMYYYY($erinnerung['Bis']),
            ];

            // Vorlage laden und Platzhalter ersetzen
            $fertigeVorlage = VorlageHelper::ladeVorlage($vorlagenDatei, $platzhalter);

            echo "<hr /><pre>";
            echo json_encode($erinnerung, JSON_PRETTY_PRINT);
            echo "</pre><hr />";
            echo $fertigeVorlage;
            echo "<hr />";

            // E-Mail senden
            $result = $emailService->emailSender1($empfaengerEmail, $betreff, $fertigeVorlage, true);
            //$result = $emailService->emailSender2($absenderEmail, $absenderName, $empfaengerEmail, $betreff, $fertigeVorlage, true);

            // Ergebnis überprüfen und im JSON-Format zurückgeben
            if (isset($result['message'])) {

                echo json_encode([
                    "message" => $result['message'],
                    "empfaengerEmail" => $empfaengerEmail,
                    "betreff" => $betreff
                ]);

                // Erinnerung um 1 Jahr inkrementieren
                $apiService->updateErinnerung($erinnerungId);

                // Loggen
                $loggerService->logEreignis("Erinnerung erfolgreich gesendet an: " . $empfaengerEmail);

            } else {

                echo json_encode(["error" => $result['error']]);
                
                // Loggen
                $loggerService->logEreignis("Fehler beim Senden der Erinnerung an: " . $empfaengerEmail . ". Fehler: " . $result['error']);

            }

        } catch (Exception $e) {

            // Fehlerbehandlung
            echo json_encode(["error" => "Fehler: " . $e->getMessage()]);

            // Loggen
            $loggerService->logEreignis("Fehler beim Verarbeiten der Erinnerung für: " . $erinnerung['Email'] . ". Fehler: " . $e->getMessage());

        }
    }

} catch (Exception $e) {
    // Allgemeine Fehlerbehandlung
    echo json_encode(["error" => "Fehler: " . $e->getMessage()]);

    // Loggen
    $loggerService->logEreignis("Fehler beim Abrufen der Erinnerungen. Fehler: " . $e->getMessage());
}

//unset($token);  // Lösche den Token nach der Verwendung

?>