<?php
require_once __DIR__ . '/../../private/config/config.php';
require_once __DIR__ . '/../../private/services/LoggerService.php';  
require_once __DIR__ . '/../../private/services/ApiService.php';  
require_once __DIR__ . '/../../private/classes/corsMiddleware.php';
require_once __DIR__ . '/../../private/classes/authMiddleware.php';  

// CORS und Authentifizierungsmiddleware aufrufen
// Aufrufbeispiele
    // Beispiel 1: Standardwerte verwenden (alle Domains, alle Methoden)
    // CorsMiddleware::handle();
    
    // Beispiel 2: Nur bestimmte Domains erlauben
    // CorsMiddleware::handle(['http://vertrauenswuerdige-domain.com', 'http://andere-domain.com']);
    
    // Beispiel 3: Bestimmte Domains und nur GET und POST erlauben
    // CorsMiddleware::handle(['http://vertrauenswuerdige-domain.com'], ['GET', 'POST']);
    
    // Beispiel 4: Keine spezielle Domain, aber nur PUT und DELETE erlauben
    // CorsMiddleware::handle(['*'], ['PUT', 'DELETE']);



// CORS-Middleware aufrufen
//CorsMiddleware::handle(); // Alle Methoden erlaubt
CorsMiddleware::handle(['*'], ['POST', 'OPTIONS']); // CORS Middleware anwenden
//AuthMiddleware::handle(); // Authentifizierung prüfen


// Header für JSON-Response setzen
header('Content-Type: application/json');

// Überprüfen, ob die CSRF-Token vorhanden sind und übereinstimmen
$headers = getallheaders();
if (!isset($headers['CSRF-Token']) || !hash_equals($_SESSION['csrf_token'], $headers['CSRF-Token'])) {
    // Fehler: CSRF-Token fehlt oder ist ungültig
    http_response_code(403); // Zugriff verweigert
    echo json_encode(['message' => 'Ungültiger CSRF-Token']);
    exit;
}

/*
// Überprüfen, ob der Authorization-Header vorhanden ist
if (!isset($headers['Authorization'])) {
    // Fehler: Authorization-Header fehlt
    http_response_code(401); // Nicht autorisiert
    echo json_encode(['message' => 'Authorization header fehlt']);
    exit;
}
*/

// HTTP-Methode auslesen
$method = $_SERVER['REQUEST_METHOD'];

// LoggerService instanziieren
$loggerService = new LoggerService("erinnerung-gast.php", $config['LOG_PATH']);

switch ($method) {
    case 'POST':
        // JSON-Daten empfangen und dekodieren
        $data = json_decode(file_get_contents("php://input"));

        // Datenvalidierung
        if (isset($data->Email, $data->Termin, $data->Bezeichnung, $data->InTage)) {
            
            // JSON-Daten aus dem Request verwenden
            $email = $data->Email;
            $termin = $data->Termin;
            $beschreibung = $data->Bezeichnung;
            $inTage = $data->InTage;

            // ApiService instanziieren
            $apiService = new ApiService();

            // Benutzer-ID abrufen
            $benutzerId = $apiService->getBenutzerId($email);

            // Falls der Benutzer nicht existiert, erstelle ihn
            if ($benutzerId == 0) {
                $benutzerId = $apiService->setBenutzer($email);
                $loggerService->logEreignis("Neuer Benutzer erstellt: E-Mail = " . $email . ", Benutzer-ID = " . $benutzerId);
            } else {
                $loggerService->logEreignis("Benutzer gefunden: E-Mail = " . $email . ", Benutzer-ID = " . $benutzerId);
            }

            // Erinnerung erstellen
            $result = $apiService->setErinnerung($benutzerId, $termin, $beschreibung, $inTage);

            if (isset($result['id'])) {
                $loggerService->logEreignis("Erinnerung erfolgreich erstellt: Benutzer-ID = " . $benutzerId . ", Termin = " . $termin . ", Erinnerung-ID = " . $result['id']);
            } else {
                $loggerService->logEreignis("Fehler beim Erstellen der Erinnerung für Benutzer-ID = " . $benutzerId);
            }

            error_log(print_r($result, true));

            // Erfolgreiches Ergebnis zurückgeben
            echo json_encode($result);

        } else {
            // Fehlende Daten – Fehler zurückgeben
            $loggerService->logEreignis("Fehlende Daten zur Erstellung der Erinnerung: E-Mail = " . ($data->Email ?? 'keine Email'));
            http_response_code(400);
            echo json_encode([
                "message" => "Fehlende Daten zur Erstellung der Erinnerung: " .
                             (isset($data->Email) ? $data->Email : 'keine Email') . ", " .
                             (isset($data->Termin) ? $data->Termin : 'kein Termin') . ", " .
                             (isset($data->Bezeichnung) ? $data->Bezeichnung : 'keine Bezeichnung') . ", " .
                             (isset($data->InTage) ? $data->InTage : 'keine InTage')
            ]);
        }
        break;
}

?>