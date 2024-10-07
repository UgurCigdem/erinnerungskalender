<?php

require_once __DIR__ . '/../../private/services/LoggerService.php';  // Verwende require_once statt require

class Erinnerung2Service {
    private $conn;
    private $loggerService;

    // Konstruktor
    public function __construct($conn) {
        $this->conn = $conn;
        $config = require __DIR__ . '/../../private/config/config.php';
        $this->loggerService = new LoggerService("Erinnerung2Service.php", $config['LOG_PATH']);
    }

    // alle Einträge
    public function getAll() {
        $sql = "SELECT * FROM erinnerung2";
        $cmd = $this->conn->query($sql);
        $result = $cmd->fetchAll(PDO::FETCH_OBJ);  // Alle Einträge zurückgeben
        
        // Loggen
        $this->loggerService->logEreignis("Alle Erinnerungen2 abgerufen.");

        return $result;
    }

    // Einträge eines Datumsbereichs
    public function get($date) {
        $sql = "SELECT * FROM erinnerung2 WHERE :date BETWEEN Von AND Bis";
        $cmd = $this->conn->prepare($sql);
        $cmd->bindParam(':date', $date);
        $cmd->execute();
        $result = $cmd->fetchAll(PDO::FETCH_OBJ);  // Alle passenden Einträge zurückgeben

        // Loggen
        $this->loggerService->logEreignis("Erinnerungen2 für Datum " . $date . " abgerufen.");

        return $result;
    }

    // Termin-Jahr erhöhen
    public function update($erinnerungId) {
        // Termin-Jahr um 1 erhöhen
        $sql = "UPDATE erinnerung SET Termin = DATE_ADD(Termin, INTERVAL 1 YEAR) WHERE ErinnerungId = :ErinnerungId"; // Korrigiere den Spaltennamen
    
        $cmd = $this->conn->prepare($sql);
        $cmd->bindParam(':ErinnerungId', $erinnerungId, PDO::PARAM_INT);
    
        
        if ($cmd->execute()) {

            // Loggen
            $this->loggerService->logEreignis("Erinnerung2 mit ID " . $erinnerungId . " wurde um 1 Jahr verschoben.");

            return ["message" => "Termin wurde um 1 Jahr verschoben"];
        } else {

            // Loggen
            $this->loggerService->logEreignis("Fehler beim Verschieben der Erinnerung2: ID = " . $erinnerungId);
            
            return ["message" => "Fehler beim Aktualisieren des Termins"];
        }
    }
}
