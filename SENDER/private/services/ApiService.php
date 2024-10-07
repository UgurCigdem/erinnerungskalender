<?php

require_once __DIR__ . '/../services/LoggerService.php';  // LoggerService einbinden

class ApiService {
    private $token;
    private $config;
    private $loggerService;

    public function __construct() {

        $this->config = require __DIR__ . '/../config/config.php';
        $this->loggerService = new LoggerService("ApiService.php", $this->config['LOG_PATH']);

        try {
            $this->token = $this->getApiToken();
        } catch (Exception $e) {

            // Loggen
            $this->loggerService->logEreignis("Fehler beim Abrufen des API-Tokens: " . $e->getMessage());

            echo json_encode(["message" => "Fehler beim Abrufen des API-Tokens: " . $e->getMessage()]);
            exit;
        }

    }

    // API-Token abrufen
    private function getApiToken() {
        try {

            $apiBaseUrl = $this->config["API_BASE_URL"];
            $apiEmail = $this->config["API_EMAIL"];
            $apiPassword = $this->config["API_PASSWORD"];

            $apiLoginUrl = $apiBaseUrl . "login.php";

            // Daten vorbereiten
            $data = [
                'Email' => $apiEmail,
                'Passwort' => $apiPassword
            ];

            // JSON-Daten erstellen
            $options = [
                'http' => [
                    'header'  => "Content-Type: application/json\r\n",
                    'method'  => 'POST',
                    'content' => json_encode($data),
                    'timeout' => 10
                ],
            ];

            // HTTP-Anfrage erstellen, API aufrufen
            $context = stream_context_create($options);
            $response = @file_get_contents($apiLoginUrl, false, $context);

            if ($response === false) {
                throw new Exception("Verbindung zur API fehlgeschlagen.");
            }

            $jsonData = json_decode($response, true);

            if (isset($jsonData['token'])) {
                return $jsonData['token'];
            } else {
                throw new Exception($jsonData['message'] ?? 'Unbekannter Fehler.');
            }

        } catch (Exception $e) {

            // Loggen
            $this->loggerService->logEreignis("Fehler beim Abrufen des API-Tokens: " . $e->getMessage());

            throw $e;
        }
    }

    // Erinnerungen abrufen
    public function getErinnerungen($datum = null) {

        try {

            $apiBaseURL = $this->config["API_BASE_URL"];
            $apiUrl = $apiBaseURL . "/erinnerung2.php?date=" . urlencode($datum);

            // Wenn kein Datum angegeben ist, Erinnerungen für heute abrufen
            if ($datum == null) {
                $datum = date('Y-m-d');
            }

            // API erreichbar nur für Token mit admin Berechtigung
            $options = [
                'http' => [
                    'header'  => "Content-Type: application/json\r\n" .
                                 "Authorization: Bearer " . $this->token . "\r\n",
                    'method'  => 'GET',
                    'timeout' => 10,
                    'ignore_errors' => true
                ],
            ];

            $context = stream_context_create($options);
            $response = @file_get_contents($apiUrl, false, $context);

            if ($response === false) {
                throw new Exception("Fehler beim Aufruf der API: Ungültiger API-Antwortstatus.");
            }

            return json_decode($response, true);

        } catch (Exception $e) {

            // Loggen
            $this->loggerService->logEreignis("Fehler beim Abrufen der Erinnerungen: " . $e->getMessage());

            echo json_encode(["message" => "Fehler beim Abrufen der Erinnerungen: " . $e->getMessage()]);
            exit;
        }

    }

    // die Erinnerung mit der ID abhaken
    public function updateErinnerung(int $erinnerungId) {

        try {

            $apiBaseURL = $this->config["API_BASE_URL"];
            $apiUrl = $apiBaseURL . "/erinnerung2.php";

            // ErinnerungId im Body (nicht im URL!)
            $daten['ErinnerungId'] = $erinnerungId;

            $options = [
                'http' => [
                    'header'  => "Content-Type: application/json\r\n" .
                                 "Authorization: Bearer " . $this->token . "\r\n",
                    'method'  => 'PUT',
                    'content' => json_encode($daten),
                    'timeout' => 10,
                    'ignore_errors' => true
                ],
            ];

            // API aufrufen
            $context = stream_context_create($options);
            $response = @file_get_contents($apiUrl, false, $context);

            if ($response === false) {
                throw new Exception("Fehler beim Aufruf der API: Ungültiger API-Antwortstatus.");
            }

            return json_decode($response, true);

        } catch (Exception $e) {

            // Loggen
            $this->loggerService->logEreignis("Fehler beim Aktualisieren der Erinnerung: " . $e->getMessage());

            echo json_encode(["message" => "Fehler beim Aktualisieren der Erinnerung: " . $e->getMessage()]);
            exit;
        }
        
    }
}

?>