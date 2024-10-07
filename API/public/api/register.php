<?php

/*
# Beschreibung der API-Endpunkte

Methode URL                  Beschreibung                            Authentifizierung
----------------------------------------------------------------------------------------------
POST   /api/register.php     Benutzer-Registrierung                  Keine (offener Endpunkt)
*/

require_once __DIR__ . '/../../private/classes/corsMiddleware.php';
require_once __DIR__ . '/../../private/classes/authMiddleware.php';

// CORS-Middleware aufrufen
CorsMiddleware::handle(); // Alle Methoden erlaubt

$config = require_once __DIR__ . '/../../private/config/config.php';  
require_once __DIR__ . '/../../private/classes/vendor/autoload.php';  
require_once __DIR__ . '/../../private/models/Benutzer.php';  
require_once __DIR__ . '/../../private/services/BenutzerService.php';
require_once __DIR__ . '/../../private/services/LoggerService.php';  

use \Firebase\JWT\JWT;

$loggerService = new LoggerService("register.php", $config['LOG_PATH']);

// Header für JSON-Response setzen
header('Content-Type: application/json');

// Nur POST erlauben
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    // JSON-Daten dekodieren
    $data = json_decode(file_get_contents('php://input'));

    // Daten vorhanden?
    if (isset($data->Name) && isset($data->Email) && isset($data->Passwort)) {
        
        // E-Mail Validierung mit Regex
        if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $data->Email)) {

            // Loggen
            $loggerService->logEreignis("Fehlgeschlagene Registrierung: Ungültige E-Mail-Adresse: " . $data->Email);

            http_response_code(400); // Fehlercode 400 (Bad Request)

            echo json_encode(["message" => "Ungültige E-Mail-Adresse."]);
            exit();
        }

        // Passwort-Längenprüfung
        if (strlen($data->Passwort) < 4) {

            // Loggen
            $loggerService->logEreignis("Fehlgeschlagene Registrierung: Passwort zu kurz.");

            http_response_code(400); // Fehlercode 400 (Bad Request)

            echo json_encode(["message" => "Passwort muss mindestens 4 Zeichen lang sein."]);
            exit();
        }

        // Benutzer-Objekt erstellen
        $neuerBenutzer = new Benutzer($data->Name, $data->Email, $data->Passwort); // Rolle nicht angegeben

        try {
            // BenutzerService instanziieren
            $benutzerService = new BenutzerService($conn);

            // Benutzer mit dem Namen "." sind außerordentlicher Benutzer
            if ($benutzerService->existsByEmailAndDotInName($neuerBenutzer->Email)) {

                // außerordentlicher Benutzer aktualisieren (registrieren)

                $aktuellerBenutzer = $benutzerService->getByEmail($neuerBenutzer->Email);
                
                // ID Übernehmen
                $neuerBenutzer->BenutzerId = $aktuellerBenutzer->BenutzerId;

                $benutzerService->update($neuerBenutzer);

                // Loggen
                $loggerService->logEreignis("Außerordentlicher Benutzer aktualisiert: Name = " . $neuerBenutzer->Name . ", E-Mail = " . $neuerBenutzer->Email);

                // Erfolgsnachricht
                echo json_encode(["message" => "Registrierung erfolgreich!", "id" => $neuerBenutzer->BenutzerId]);

                exit;
            }
            else
            // Checken, ob Email bereits existiert
            if ($benutzerService->existsByEmail($neuerBenutzer->Email)) {
                echo json_encode(["message" => "Ein Benutzer mit dieser E-Mail existiert bereits."]);
                exit;
            }
            else{
                // Benutzer erstellen
                $benutzerId = $benutzerService->create($neuerBenutzer);

                // Loggen
                $loggerService->logEreignis("Neuer Benutzer registriert: Name = " . $neuerBenutzer->Name . ", E-Mail = " . $neuerBenutzer->Email);

                // Erfolgsnachricht
                echo json_encode(["message" => "Registrierung erfolgreich!", "id" => $benutzerId]);
            }

        } catch (Exception $e) {

            // Loggen
            $loggerService->logEreignis("Fehler bei der Registrierung: " . $e->getMessage());

            // Fehler bei Registrierung
            http_response_code(400); // Fehlercode 400 (Bad Request)

            echo json_encode(["message" => "Fehler bei der Registrierung: " . $e->getMessage()]);

        }

    } else {
        
        // Loggen
        $loggerService->logEreignis("Fehlgeschlagene Registrierung: Unvollständige Daten.");

        // Fehlermeldung, Notwendige Daten
        http_response_code(400); // Fehlercode 400 (Bad Request)

        echo json_encode(["message" => "Name, Email und Passwort müssen angegeben werden."]);
    }

} else {
    // Fehlermeldung, Nur POST-Anfrage
    echo json_encode(["message" => "Nur POST-Anfragen sind erlaubt."]);
}