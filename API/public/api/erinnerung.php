<?php

/*

# Beschreibung der API-Endpunkte 

Methode	URL                                 Beschreibung                    Authentifizierung
--------------------------------------------------------------------------------------------
GET    /api/erinnerung.php?id={id}         Eine Erinnerung per ID abrufen           JWT
GET    /api/erinnerung.php?BenutzerId={BenutzerId}  Alle Erinnerungen abrufen       JWT
GET    /api/erinnerung.php                 Alle Erinnerungen abrufen                JWT
POST   /api/erinnerung.php                 Eine neue Erinnerung erstellen           JWT
PUT    /api/erinnerung.php?id={id}         Eine Erinnerung per ID aktualisieren     JWT
DELETE /api/erinnerung.php?id={id}         Eine Erinnerung per ID löschen           JWT

*/

require_once __DIR__ . '/../../private/classes/corsMiddleware.php';
require_once __DIR__ . '/../../private/classes/authMiddleware.php';

// CORS-Middleware aufrufen
CorsMiddleware::handle();

$config = require __DIR__ . '/../../private/config/config.php';
require __DIR__ . '/../../private/models/Erinnerung.php';  
require __DIR__ . '/../../private/services/ErinnerungService.php';
require_once __DIR__ . '/../../private/services/LoggerService.php';  

$loggerService = new LoggerService("erinnerung.php", $config['LOG_PATH']);

// Authentifizierungs-Middleware aufrufen
$decoded = AuthMiddleware::handle($config['TOKEN_KEY']);

// Header für JSON-Response setzen
header('Content-Type: application/json');

// id auslesen
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

// benutzerId auslesen
$benutzerId = isset($_GET['BenutzerId']) ? intval($_GET['BenutzerId']) : null;

// Benutzer-ID aus dem Token auslesen
$tokenBenutzerId = $decoded->data->id ?? null;  // BenutzerId aus dem Token

// Rolle aus dem Token auslesen
$tokenRolle = $decoded->data->rolle ?? null; // BenutzerId aus dem Token


if ($benutzerId !== $tokenBenutzerId && $tokenRolle !== 'admin') {

    http_response_code(403); // Fehler 403 Forbidden

    // Loggen
    $loggerService->logEreignis("Zugriff verweigert: BenutzerId stimmt nicht überein.");

    echo json_encode(["message" => "Zugriff verweigert. BenutzerId stimmt nicht überein"]);
    exit();
}

//$loggerService->logEreignis(" ### Anfrage erhalten: id = $id , benutzerId = $benutzerId , tokenRolle = $tokenRolle , tokenBenutzerId = $tokenBenutzerId, Decoded Inhalt: " . json_encode($decoded));

// ErinnerungService instanziieren
$erinnerungService = new ErinnerungService($conn);

// HTTP-Methode auslesen
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // abrufen

        if ($id) {
            // Einen Eintrag abrufen
            $eintrag = $erinnerungService->get($id);
            if ($eintrag) {
                echo json_encode($eintrag);
            } else {

                http_response_code(404); // Fehler 404 Not Found

                // Loggen
                $loggerService->logEreignis("Erinnerung nicht gefunden: ID = " . $id);

                echo json_encode(["message" => "Erinnerung nicht gefunden"]);
            }
        } elseif ($benutzerId) {
            // Alle Einträge für einen spezifischen Benutzer abrufen
            
            $eintraege = $erinnerungService->getByBenutzerId($benutzerId);
            if ($eintraege) {
                echo json_encode($eintraege);
            } else {

                http_response_code(404); // Fehler 404 Not Found

                // Loggen
                $loggerService->logEreignis("Keine Erinnerungen gefunden für BenutzerId = " . $benutzerId);

                echo json_encode(["message" => "Keine Erinnerungen für diesen Benutzer gefunden"]);
            }
        } else {
            // Alle Einträge abrufen
            $alle = $erinnerungService->get();
            echo json_encode($alle);
        }
        break;

    case 'POST':
        // JSON-Daten dekodieren
        $data = json_decode(file_get_contents("php://input"));

        // Datenvalidierung
        if (isset($data->BenutzerId, $data->Termin, $data->Bezeichnung)) {

            // Erinnerung-Objekt erstellen
            $eintrag = new Erinnerung(
                $data->BenutzerId, 
                $data->Termin,   // Verwendung von Termin im Format YYYY-MM-DD
                $data->Bezeichnung, 
                $data->InTage ?? null
            );
    
            // Erinnerung speichern
            $id = $erinnerungService->create($eintrag);

            // Loggen
            $loggerService->logEreignis("Neue Erinnerung erstellt: ID = " . $id);

            echo json_encode(["message" => "Erinnerung erstellt", "id" => $id]);
        } else {

            http_response_code(400); // Fehler 400 Bad Request

            // Loggen
            $loggerService->logEreignis("Fehlgeschlagene Erstellung: Fehlende Daten.");

            echo json_encode(["message" => "Fehlende Daten zur Erstellung der Erinnerung"]);
        }
        break;

    case 'PUT':
        // aktualisieren
        if ($id) {

            // JSON-Daten dekodieren
            $data = json_decode(file_get_contents("php://input"));
            $eintrag = $erinnerungService->get($id);

            if ($eintrag) {
                // ID
                $eintrag->ErinnerungId = $id ?? $eintrag->ErinnerungId;
                $eintrag->BenutzerId = $data->BenutzerId ?? $eintrag->BenutzerId;
                $eintrag->Termin = $data->Termin ?? $eintrag->Termin;  // Termin anstelle von Tag und Monat
                $eintrag->Bezeichnung = $data->Bezeichnung ?? $eintrag->Bezeichnung;
                $eintrag->InTage = $data->InTage ?? $eintrag->InTage;

                $erinnerungService->update($eintrag);

                // Loggen
                $loggerService->logEreignis("Erinnerung aktualisiert: ID = " . $id);

                echo json_encode(["message" => "Erinnerung aktualisiert"]);

            } else {

                http_response_code(404); // Fehler 404 Not Found

                // Loggen
                $loggerService->logEreignis("Erinnerung nicht gefunden zur Aktualisierung: ID = " . $id);

                echo json_encode(["message" => "Erinnerung nicht gefunden"]);

            }
        } else {

            http_response_code(400); // Fehler 400 Bad Request

            // Loggen
            $loggerService->logEreignis("Fehlgeschlagene Aktualisierung: ID fehlt.");

            echo json_encode(["message" => "ID zur Aktualisierung fehlt"]);

        }
        break;

    case 'DELETE':
        // löschen
        if ($id) {
            if ($erinnerungService->delete($id)) {

                // Loggen
                $loggerService->logEreignis("Erinnerung gelöscht: ID = " . $id);

                echo json_encode(["message" => "Erinnerung gelöscht"]);

            } else {

                http_response_code(404); // Fehler 404 Not Found


                // Loggen
                $loggerService->logEreignis("Fehlgeschlagene Löschung: Erinnerung nicht gefunden: ID = " . $id);

                echo json_encode(["message" => "Erinnerung nicht gefunden"]);

            }
        } else {

            http_response_code(400); // Fehler 400 Bad Request

            // Loggen
            $loggerService->logEreignis("Fehlgeschlagene Löschung: ID fehlt.");

            echo json_encode(["message" => "ID zum Löschen fehlt"]);

        }
        break;

    default:
        // nicht unterstützt

        http_response_code(405); // Fehler 405 Methode nicht unterstützt

        // Loggen
        $loggerService->logEreignis("Methode nicht erlaubt: " . $method);

        echo json_encode(["message" => "Methode nicht erlaubt"]);
        break;

}
