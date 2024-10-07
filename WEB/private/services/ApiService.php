<?php

require_once __DIR__ . '/../services/LoggerService.php';  // LoggerService einbinden

class ApiService {
    private $token;
    private $config;
    private $loggerService;
    

    public function __construct() {
        $this->config = require __DIR__ . '/../config/config.php';
        $this->loggerService = new LoggerService("ApiService.php", $this->config['LOG_PATH']);

        //$this->config['API_BASE_URL']
        //$this->config['API_EMAIL']
        //$this->config['API_PASSWORD']

        $this->loggerService->logEreignis(" >>>  ");


        try {
            $this->token = $this->getApiToken();
            
            $this->loggerService->logEreignis(" >>>  " . $this->token);

        } catch (Exception $e) {
            // Loggen
            $this->loggerService->logEreignis("Fehler beim Abrufen des API-Tokens: " . $e->getMessage());
            echo json_encode(["message" => "Fehler beim Abrufen des API-Tokens: " . $e->getMessage()]);
            exit;
        }
    }

    // Benutzer-ID abrufen
    public function getBenutzerId($email) {

        $apiBenutzerUrl = $this->config['API_BASE_URL'] . '/benutzer.php';
        $apiUrl = $apiBenutzerUrl . '?email=' . urlencode($email);

        // GET-Anfrage mit JWT-Token
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
        $response = file_get_contents($apiUrl, false, $context);

        if ($response === false) {
            $this->loggerService->logEreignis("Fehler beim Abrufen der Benutzer-ID für E-Mail: " . $email);
            return 0;
        }

        $jsonData = json_decode($response, true);

        if (isset($jsonData['BenutzerId'])) {
            $this->loggerService->logEreignis("Benutzer-ID erfolgreich abgerufen für E-Mail: " . $email);
            return $jsonData['BenutzerId'];
        } else {
            $this->loggerService->logEreignis("Benutzer-ID nicht gefunden für E-Mail: " . $email);
            return 0;
        }
    }

    // Benutzer erstellen und Benutzer-ID zurückgeben
    public function setBenutzer($email) {
        //global $apiBenutzerUrl;

        $apiBenutzerUrl = $this->config['API_BASE_URL'] . '/benutzer.php';
        
        $apiUrl = $apiBenutzerUrl;
        
        $data = [
            'Name' => ".",
            'Email' => $email,
            'Passwort' => ".",
        ];

        // POST-Anfrage mit JWT-Token
        $options = [
            'http' => [
                'header'  => "Content-Type: application/json\r\n" .
                             "Authorization: Bearer " . $this->token . "\r\n",
                'method'  => 'POST',
                'content' => json_encode($data),
                'timeout' => 10
            ],
        ];

        $context = stream_context_create($options);
        $response = file_get_contents($apiUrl, false, $context);

        if ($response === false) {
            $this->loggerService->logEreignis("Fehler beim Erstellen des Benutzers mit E-Mail: " . $email);
            return "Fehler: Verbindung zur API fehlgeschlagen.";
        }

        $jsonData = json_decode($response, true);

        if (isset($jsonData['id'])) {
            $this->loggerService->logEreignis("Benutzer erfolgreich erstellt: E-Mail = " . $email . ", ID = " . $jsonData['id']);
            return $jsonData['id'];
        } else {
            $this->loggerService->logEreignis("Fehler beim Erstellen des Benutzers: " . ($jsonData['message'] ?? 'Benutzer konnte nicht erstellt werden.') . " für E-Mail: " . $email);
            return "Fehler: " . ($jsonData['message'] ?? 'Benutzer konnte nicht erstellt werden.');
        }
    }
    
    // API-Token abrufen
    private function getApiToken() {
        //global $apiLoginUrl, $apiEmail, $apiPassword;

        $apiLoginUrl = $this->config['API_BASE_URL'] . '/login.php';

        //$this->config['API_EMAIL']
        //$this->config['API_PASSWORD']

        $data = [
            'Email' => $this->config['API_EMAIL'],
            'Passwort' => $this->config['API_PASSWORD'],
        ];

        $options = [
            'http' => [
                'header'  => "Content-Type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($data),
                'timeout' => 10
            ],
        ];

        $context = stream_context_create($options);
        $response = file_get_contents($apiLoginUrl, false, $context);

        if ($response === false) {
            $this->loggerService->logEreignis("Fehler beim Abrufen des API-Tokens: Verbindung zur API fehlgeschlagen.");
            throw new Exception("Verbindung zur API fehlgeschlagen.");
        }

        $jsonData = json_decode($response, true);

        if (isset($jsonData['token'])) {
            $this->loggerService->logEreignis("API-Token erfolgreich abgerufen.");
            return $jsonData['token'];
        } else {
            $this->loggerService->logEreignis("Fehler beim Abrufen des API-Tokens: " . ($jsonData['message'] ?? 'Unbekannter Fehler.'));
            throw new Exception($jsonData['message'] ?? 'Unbekannter Fehler.');
        }
    }

    // Erinnerung erstellen
    public function setErinnerung($benutzerId, $termin, $bezeichnung, $intage) {
        
        //global $apiErinnerungUrl;

        $apiErinnerungUrl = $this->config['API_BASE_URL'] . '/erinnerung.php';

        $data = [
            'BenutzerId' => $benutzerId,
            'Termin' => $termin,
            'Bezeichnung' => $bezeichnung,
            'InTage' => $intage
        ];

        // POST-Anfrage mit JWT-Token
        $options = [
            'http' => [
                'header'  => "Content-Type: application/json\r\n" .
                             "Authorization: Bearer " . $this->token . "\r\n",
                'method'  => 'POST',
                'content' => json_encode($data),
                'timeout' => 10
            ],
        ];

        $context = stream_context_create($options);
        $response = file_get_contents($apiErinnerungUrl, false, $context);

        if ($response === false) {
            $this->loggerService->logEreignis("Fehler beim Erstellen der Erinnerung für Benutzer-ID: " . $benutzerId);
            return "Fehler: Verbindung zur API fehlgeschlagen.";
        }

        $this->loggerService->logEreignis("Erinnerung erfolgreich erstellt für Benutzer-ID: " . $benutzerId . ", Termin: " . $termin);
        return json_decode($response, true);
    }
}
