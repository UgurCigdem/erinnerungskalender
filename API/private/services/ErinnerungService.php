<?php

require_once __DIR__ . '/../../private/services/LoggerService.php';  // Verwende require_once statt require

class ErinnerungService {
    private $conn;
    private $loggerService;

    // Konstruktor
    public function __construct($conn) {
        $this->conn = $conn;
        $config = require __DIR__ . '/../../private/config/config.php';
        $this->loggerService = new LoggerService("ErinnerungService.php", $config['LOG_PATH']);
    }

    // CREATE
    public function create($erinnerung) {
        $sql = "INSERT INTO erinnerung (BenutzerId, Termin, Bezeichnung, InTage) 
                VALUES (:BenutzerId, :Termin, :Bezeichnung, :InTage)";
       
        $cmd = $this->conn->prepare($sql);
        $cmd->bindParam(':BenutzerId', $erinnerung->BenutzerId);
        $cmd->bindParam(':Termin', $erinnerung->Termin);  // Neues Feld Termin
        $cmd->bindParam(':Bezeichnung', $erinnerung->Bezeichnung);
        $cmd->bindParam(':InTage', $erinnerung->InTage);
        $cmd->execute();
        $lastInsertId = $this->conn->lastInsertId();

        // Loggen
        $this->loggerService->logEreignis("Neue Erinnerung erstellt: ID = " . $lastInsertId . ", BenutzerId = " . $erinnerung->BenutzerId);
        
        return $lastInsertId; // Neue ID zurückgeben
    }

    // READ
    public function get($id = null) {
        if ($id) {
            $sql = "SELECT * FROM erinnerung WHERE ErinnerungId = :id ORDER BY Termin ASC";
            $cmd = $this->conn->prepare($sql);
            $cmd->bindParam(':id', $id);
            $cmd->execute();
            $result = $cmd->fetch(PDO::FETCH_OBJ); // Einen Eintrag zurückgeben

            // Loggen
            $this->loggerService->logEreignis("Erinnerung abgerufen: ID = " . $id);

            return $result;
        } else {
            $sql = "SELECT * FROM erinnerung  ORDER BY Termin ASC";
            $cmd = $this->conn->query($sql);
            $result = $cmd->fetchAll(PDO::FETCH_OBJ); // Alle Einträge zurückgeben

            // Loggen
            $this->loggerService->logEreignis("Alle Erinnerungen abgerufen.");

            return $result;
        }
    }

    // GET BY BenutzerId
    public function getByBenutzerId($benutzerId) {
        $sql = "SELECT * FROM erinnerung WHERE BenutzerId = :BenutzerId ORDER BY Termin ASC";
        $cmd = $this->conn->prepare($sql);
        $cmd->bindParam(':BenutzerId', $benutzerId);
        $cmd->execute();
        $result = $cmd->fetchAll(PDO::FETCH_OBJ); // Alle Einträge für den Benutzer zurückgeben

        // Loggen
        $this->loggerService->logEreignis("Erinnerungen für BenutzerId = " . $benutzerId . " abgerufen.");

        return $result;
    }

    // UPDATE
    public function update($erinnerung) {
        $sql = "UPDATE erinnerung SET 
                BenutzerId = :BenutzerId,
                Termin = :Termin,   /* Termin statt Tag und Monat */
                Bezeichnung = :Bezeichnung,
                InTage = :InTage
                WHERE ErinnerungId = :ErinnerungId";

        $cmd = $this->conn->prepare($sql);
        $cmd->bindParam(':ErinnerungId', $erinnerung->ErinnerungId);
        $cmd->bindParam(':BenutzerId', $erinnerung->BenutzerId);
        $cmd->bindParam(':Termin', $erinnerung->Termin);  // Neues Feld Termin
        $cmd->bindParam(':Bezeichnung', $erinnerung->Bezeichnung);
        $cmd->bindParam(':InTage', $erinnerung->InTage);

        $success = $cmd->execute();

        // Loggen
        if ($success) {
            $this->loggerService->logEreignis("Erinnerung aktualisiert: ID = " . $erinnerung->ErinnerungId);
        } else {
            $this->loggerService->logEreignis("Fehlgeschlagene Aktualisierung der Erinnerung: ID = " . $erinnerung->ErinnerungId);
        }

        return $success; // true, wenn erfolgreich
    }

    // DELETE
    public function delete($id) {
        $sql = "DELETE FROM erinnerung WHERE ErinnerungId = :id";
        $cmd = $this->conn->prepare($sql);
        $cmd->bindParam(':id', $id);
        $success = $cmd->execute();

        // Loggen
        if ($success) {
            $this->loggerService->logEreignis("Erinnerung gelöscht: ID = " . $id);
        } else {
            $this->loggerService->logEreignis("Fehlgeschlagene Löschung der Erinnerung: ID = " . $id);
        }
        
        return $success; // true, wenn erfolgreich
    }
}
