<?php

require_once __DIR__ . '/vendor/autoload.php'; 
//require_once __DIR__ . '/vendor/firebase/php-jwt/src/JWT.php';
//require_once __DIR__ . '/vendor/firebase/php-jwt/src/Key.php';

use Firebase\JWT\Key;
use Firebase\JWT\JWT;

// Middleware: Auth Middleware - Wird für JWT-Überprüfung verwendet
class AuthMiddleware {

    public static function handle() {

        // Konfiguration laden
        $config = require __DIR__ . '/../../private/config/config.php';
        
        // Session- und JWT-Überprüfung hier durchführen
        $headers = getallheaders();

        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(['message' => 'Authorization header fehlt']);
            exit();
        }

        $jwt = str_replace('Bearer ', '', $headers['Authorization']);

        try {
            // JWT-Überprüfung durchführen
            require_once __DIR__ . '/../classes/vendor/firebase/php-jwt/src/JWT.php';
            $decoded = \Firebase\JWT\JWT::decode($jwt, new \Firebase\JWT\Key($config['TOKEN_KEY'], 'HS256'));

            // Wenn erfolgreich, ist der Benutzer authentifiziert
            return $decoded;

        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized: ' . $e->getMessage()]);
            exit();
        }
    }

}
