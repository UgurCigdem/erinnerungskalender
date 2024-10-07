<?php

/*
# Beschreibung der API-Endpunkte

Methode URL                  Beschreibung                            Authentifizierung
--------------------------------------------------------------------------------------------
POST    /api/login.php       Benutzer einloggen                      Keine für Login

*/

require_once __DIR__ . '/../../private/classes/corsMiddleware.php';
require_once __DIR__ . '/../../private/classes/authMiddleware.php';

// CORS-Middleware aufrufen
CorsMiddleware::handle(); // Alle Methoden erlaubt

$config = require __DIR__ . '/../../private/config/config.php';  
require_once __DIR__ . '/../../private/classes/vendor/autoload.php';  
require_once __DIR__ . '/../../private/services/BenutzerService.php';
require_once __DIR__ . '/../../private/services/LoggerService.php';  

use \Firebase\JWT\JWT;

$loggerService = new LoggerService("login.php", $config['LOG_PATH']);

// Header für JSON-Response setzen
header('Content-Type: application/json');


// ist das eine POST Anfrage?
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // JSON-Daten dekodieren
    $data = json_decode(file_get_contents("php://input"));

    // Daten vorhanden?
    if (isset($data->Email) && isset($data->Passwort)) {
        
        $email = $data->Email;
        $password = $data->Passwort;

        try {
            // BenutzerService instanziieren
            $benutzerService = new BenutzerService($conn);

            // Login-Daten prüfen
            if ($benutzerService->checkLogin($email, $password)) {

                // Benutzer über email abrufen
                $user = $benutzerService->getByEmail($email);

                // JWT erstellen
                $payload = [
                    "iss" => $config['TOKEN_BASE_URL'], // Issuer
                    "aud" => $config['TOKEN_BASE_URL'], // Audience
                    "iat" => time(),   // Issued At
                    "nbf" => time(),   // Not Before
                    "data" => [
                        "id" => $user->BenutzerId,
                        "email" => $user->Email,
                        "rolle" => $user->Rolle
                    ]
                ];

                // JWT-Token mit HS256 signieren
                //$jwt = JWT::encode($payload, $key, 'HS256');
                $jwt = JWT::encode($payload, $config['TOKEN_KEY'], 'HS256');
                
                // Erfolgsnachricht mit Token zurückgeben
                echo json_encode([
                    "message" => "Login erfolgreich!",
                    "id" => $user->BenutzerId,
                    "name" => $user->Name,
                    "email" => $user->Email,
                    "token" => $jwt
                ]);

            } else {

                // Loggen
                $loggerService->logEreignis("Fehlgeschlagener Login-Versuch für E-Mail: " . $email);

                // falschen Anmeldedaten
                http_response_code(401); // 401 Unauthorized

                echo json_encode(["message" => "Falsche Email oder Passwort."]);
            }
        } catch (Exception $e) {
            // Fehlerbehandlung

            // Loggen
            $loggerService->logEreignis("Fehler beim Login-Versuch: " . $e->getMessage());

            http_response_code(500); // 500 Internal Server Error

            echo json_encode(["message" => "Ein Fehler ist aufgetreten: " . $e->getMessage()]);
        }
    } else {
        // Daten fehlen

        // Loggen
        $loggerService->logEreignis("Fehlgeschlagener Login-Versuch: Fehlende Email oder Passwort.");

        http_response_code(400); // 400 Bad Request

        echo json_encode(["message" => "Email und Passwort müssen angegeben werden."]);
    }
} else {
    // keine POST-Anfrage
    
    // Loggen
    $loggerService->logEreignis("Ungültige Anfrage-Methode: " . $_SERVER['REQUEST_METHOD']);

    http_response_code(405); // 405 Method Not Allowed

    echo json_encode(["message" => "Nur POST-Anfragen sind erlaubt."]);
}