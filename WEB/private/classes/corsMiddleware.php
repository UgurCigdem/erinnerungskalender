<?php

// Middleware: CORS Middleware
class CorsMiddleware {

    // Aufrufbeispiele

    // Beispiel 1: Standardwerte verwenden (alle Domains, alle Methoden)
    // CorsMiddleware::handle();

    // Beispiel 2: Nur bestimmte Domains erlauben
    // CorsMiddleware::handle(['http://vertrauenswuerdige-domain.com', 'http://andere-domain.com']);

    // Beispiel 3: Bestimmte Domains und nur GET und POST erlauben
    // CorsMiddleware::handle(['http://vertrauenswuerdige-domain.com'], ['GET', 'POST']);

    // Beispiel 4: Keine spezielle Domain, aber nur PUT und DELETE erlauben
    // CorsMiddleware::handle(['*'], ['PUT', 'DELETE']);

    public static function handle($allowedDomains = ['*'], $allowedMethods = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS']) {
        // Erlaubte Domains festlegen
        if (in_array('*', $allowedDomains)) {
            header('Access-Control-Allow-Origin: *');
        } else {
            $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
            if (in_array($origin, $allowedDomains)) {
                header('Access-Control-Allow-Origin: ' . $origin);
            }
        }

        // Erlaubte Header festlegen
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');

        // Erlaubte Methoden festlegen
        if (empty($allowedMethods)) {
            $allowedMethods = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'];
        }
        header('Access-Control-Allow-Methods: ' . implode(', ', $allowedMethods) . ', OPTIONS');

        // Für OPTIONS-Anfragen nur die Header zurückgeben
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }
    }


}

