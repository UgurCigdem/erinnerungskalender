<?php

/*
# Beschreibung der API-Endpunkte

Methode	URL                              Beschreibung	                        Authentifizierung
----------------------------------------------------------------------------------------------
GET    /api/benutzer.php?id={id}         Einen Benutzer per ID abrufen              JWT
GET    /api/benutzer.php?email={email}   Einen Benutzer per E-Mail abrufen          JWT
GET    /api/benutzer.php                 Alle Benutzer abrufen                      JWT
POST   /api/benutzer.php                 Einen neuen Benutzer erstellen             JWT
PUT    /api/benutzer.php?id={id}         Einen Benutzer per ID aktualisieren        JWT
DELETE /api/benutzer.php?id={id}         Einen Benutzer per ID löschen              JWT
*/

require_once __DIR__ . '/../../private/classes/corsMiddleware.php';
require_once __DIR__ . '/../../private/classes/authMiddleware.php';

// CORS und Authentifizierungsmiddleware aufrufen
CorsMiddleware::handle(); // Alle Methoden erlaubt
AuthMiddleware::handle();

$config = require_once __DIR__ . '/../../private/config/config.php';  
require_once __DIR__ . '/../../private/models/Benutzer.php';  
require_once __DIR__ . '/../../private/services/BenutzerService.php';
require_once __DIR__ . '/../../private/services/LoggerService.php';  // LoggerService einbinden

$loggerService = new LoggerService("benutzer.php", $config['LOG_PATH']);

// Header für JSON-Response setzen
header('Content-Type: application/json');

// Methode auslesen
$method = $_SERVER['REQUEST_METHOD'];

// Instanziiere den BenutzerService
$benutzerService = new BenutzerService($conn);

// id vom URL auslesen
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

// email vom URL auslesen
$email = isset($_GET['email']) ? $_GET['email'] : null;

switch ($method) {
    
    case 'GET':
        // Benutzer per ID oder E-Mail abrufen

        if ($id) {
            // Einen Benutzer nach ID abrufen
            $benutzer = $benutzerService->get($id);
            if ($benutzer) {

                // Loggen
                $loggerService->logEreignis("Benutzer abgerufen: ID = " . $id);

                echo json_encode($benutzer);
            } else {

                // Loggen
                $loggerService->logEreignis("Fehlgeschlagene Abfrage: Benutzer mit ID = " . $id . " nicht gefunden.");

                http_response_code(404); // 404 Not Found (ID nicht vorhanden)

                echo json_encode(["message" => "Benutzer nicht gefunden"]);
            }
        } elseif ($email) {
            // Einen Benutzer nach E-Mail abrufen
            
            $benutzer = $benutzerService->getByEmail($email);
            if ($benutzer) {

                // Loggen
                $loggerService->logEreignis("Benutzer abgerufen: E-Mail = " . $email);

                echo json_encode($benutzer);
            } else {

                // Loggen
                $loggerService->logEreignis("Fehlgeschlagene Abfrage: Benutzer mit E-Mail = " . $email . " nicht gefunden.");

                http_response_code(404); // 404 Not Found (Email nicht vorhanden)

                echo json_encode(["message" => "Benutzer nicht gefunden"]);
            }
        } else {
            // Alle Benutzer abrufen
            $benutzer = $benutzerService->get();

            // Loggen
            $loggerService->logEreignis("Alle Benutzer wurden abgerufen.");

            echo json_encode($benutzer);
        }
        break;

    case 'POST':
        // Benutzer erstellen
        $data = json_decode(file_get_contents("php://input"));
        if (isset($data->Name, $data->Email, $data->Passwort)) {
            try {
                // Neuen Benutzer mit automatisch generiertem Salt erstellen
                $neuerBenutzer = new Benutzer($data->Name, $data->Email, $data->Passwort, $data->Rolle ?? 'user');
                
                $benutzerId = $benutzerService->create($neuerBenutzer);

                // Loggen
                $loggerService->logEreignis("Neuer Benutzer erstellt: Name = " . $data->Name . ", E-Mail = " . $data->Email);

                echo json_encode(["message" => "Benutzer erstellt", "id" => $benutzerId]);
            } catch (PDOException $e) {
                $loggerService->logEreignis("Fehlgeschlagene Erstellung eines Benutzers: " . $e->getMessage());

                http_response_code(409); // HTTP-Statuscode 409 - Conflict

                echo json_encode(["message" => $e->getMessage()]);
            }
        } else {

            // Loggen
            $loggerService->logEreignis("Fehlgeschlagene Erstellung eines Benutzers: Fehlende Daten.");

            http_response_code(400); // 400 - Bad Request

            echo json_encode(["message" => "Fehlende Daten zur Erstellung des Benutzers"]);
        }
        break;

    case 'PUT':
        // Benutzer aktualisieren
        if ($id) {
            $data = json_decode(file_get_contents("php://input"));
            $benutzer = $benutzerService->get($id);
            if ($benutzer) {
                // Update-Daten und Passwort-Handling (Salt & Hash)
                $benutzer->Name = $data->Name ?? $benutzer->Name;
                $benutzer->Email = $data->Email ?? $benutzer->Email;

                // Nur neues Passwort hashen und Salt anwenden, wenn es übergeben wurde
                if (!empty($data->Passwort)) {
                    $benutzer->Passwort = $data->Passwort;
                }

                $benutzer->Rolle = $data->Rolle ?? $benutzer->Rolle;
                $benutzerService->update($benutzer);

                // Loggen
                $loggerService->logEreignis("Benutzerdaten aktualisiert: ID = " . $id . ", Name = " . $benutzer->Name);

                echo json_encode(["message" => "Benutzer aktualisiert"]);
            } else {

                // Loggen
                $loggerService->logEreignis("Fehlgeschlagene Aktualisierung: Benutzer mit ID = " . $id . " nicht gefunden.");

                http_response_code(404); //  HTTP-Statuscode 404 - Not Found

                echo json_encode(["message" => "Benutzer nicht gefunden"]);
            }
        } else {

            // Loggen
            $loggerService->logEreignis("Fehlgeschlagene Aktualisierung: ID zur Aktualisierung fehlt.");

            http_response_code(400); // 400 - Bad Request

            echo json_encode(["message" => "ID zur Aktualisierung fehlt"]);
        }
        break;

    case 'DELETE':
        // Benutzer löschen
        if ($id) {
            if ($benutzerService->delete($id)) {

                // Loggen
                $loggerService->logEreignis("Benutzer gelöscht: ID = " . $id);

                echo json_encode(["message" => "Benutzer gelöscht"]);
            } else {

                // Loggen
                $loggerService->logEreignis("Fehlgeschlagene Löschung: Benutzer mit ID = " . $id . " nicht gefunden.");

                http_response_code(404); // 404 Not Found (Benutzer nicht gefunden)

                echo json_encode(["message" => "Benutzer nicht gefunden"]);
            }
        } else {

            // Loggen
            $loggerService->logEreignis("Fehlgeschlagene Löschung: ID zum Löschen fehlt.");

            http_response_code(400); // 400 - Bad Request

            echo json_encode(["message" => "ID zum Löschen fehlt"]);
        }
        break;

    default:
        // Methode nicht unterstützt

        // Loggen
        $loggerService->logEreignis("Fehlgeschlagene Anfrage: Methode " . $method . " nicht erlaubt.");

        http_response_code(405); // 405 - Method Not Allowed

        echo json_encode(["message" => "Methode nicht erlaubt"]);
        break;
}