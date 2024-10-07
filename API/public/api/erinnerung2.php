<?php

/*

# Beschreibung der API-Endpunkte

Methode	URL                           Beschreibung                           Authentifizierung
---------------------------------------------------------------------------------------------
GET    /api/erinnerung2.php?date={date}   Erinnerungen für ein bestimmtes Datum    JWT (Admin)
GET    /api/erinnerung2.php               Alle Erinnerungen abrufen                JWT (Admin)
PUT    /api/erinnerung2.php               Eine Erinnerung aktualisieren            JWT (Admin)

*/

require_once __DIR__ . '/../../private/classes/corsMiddleware.php';
require_once __DIR__ . '/../../private/classes/authMiddleware.php';

// CORS-Middleware aufrufen
CorsMiddleware::handle(['*'], ['GET', 'PUT', 'OPTIONS']);

$config = require __DIR__ . '/../../private/config/config.php';  
require __DIR__ . '/../../private/models/Erinnerung2.php';  
require __DIR__ . '/../../private/services/Erinnerung2Service.php';  
require_once __DIR__ . '/../../private/services/LoggerService.php'; 

// Authentifizierungs-Middleware aufrufen
$decoded = AuthMiddleware::handle($config['TOKEN_KEY']);

$loggerService = new LoggerService("erinnerungen2.php", $config['LOG_PATH']);

// Header für JSON-Response setzen
header('Content-Type: application/json');

// HTTP-Methode auslesen
$method = $_SERVER['REQUEST_METHOD'];

// Benutzer-ID und Rolle aus dem Token auslesen
$tokenBenutzerId = $decoded->data->id ?? null;  // BenutzerId aus dem Token
$tokenRolle = $decoded->data->rolle ?? null;    // Rolle aus dem Token

// Rolle (admin) überprüfen
if ($tokenRolle !== 'admin') {

    // Loggen
    $loggerService->logEreignis("Zugriff verweigert: Admin-Rechte erforderlich");

    http_response_code(403); // 403 Forbidden

    echo json_encode(["message" => "Zugriff verweigert: Admin-Rechte erforderlich"]);
    exit;
}

// Erinnerung2Service instanziieren
$erinnerung2Service = new Erinnerung2Service($conn);

// datum auslesen
$date = isset($_GET['date']) ? $_GET['date'] : null;

switch ($method) {
    case 'GET':
        // Einträge für den Zeitraum

        if ($date) {
            $eintraege = $erinnerung2Service->get($date);

            // Loggen
            $loggerService->logEreignis("Erinnerungen für Datum " . $date . " abgerufen.");

            echo json_encode($eintraege);
        } else {
            // alle Einträge abrufen
            $alle = $erinnerung2Service->getAll();

            // Loggen
            $loggerService->logEreignis("Alle Erinnerungen wurden abgerufen.");

            echo json_encode($alle);
        }
        break;

    case 'PUT':
        // Daten auslesen
        $inputData = json_decode(file_get_contents("php://input"), true);

        if (isset($inputData['ErinnerungId'])) {
            $erinnerungId = $inputData['ErinnerungId'];

            $result = $erinnerung2Service->update($erinnerungId);

            // Loggen
            $loggerService->logEreignis("Erinnerung mit ID " . $erinnerungId . " aktualisiert.");

            echo json_encode($result);
        } else {

            // Loggen
            $loggerService->logEreignis("Fehlgeschlagene Aktualisierung: ErinnerungId fehlt.");

            http_response_code(400); // 400 Bad Request

            echo json_encode(["message" => "id fehlt"]);
        }
        break;

    default:
        // Methode nicht unterstützt

        // Loggen
        $loggerService->logEreignis("Fehlgeschlagene Anfrage: Methode " . $method . " nicht erlaubt.");

        http_response_code(405); // 405 Method Not Allowed

        echo json_encode(["message" => "Methode nicht erlaubt"]);
        break;

}
