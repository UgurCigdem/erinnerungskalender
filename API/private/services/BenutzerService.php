<?php

require_once __DIR__ . '/../../private/services/LoggerService.php';  // LoggerService einbinden

class BenutzerService {
    private $conn;
    private $loggerService;

    // Konstruktor
    public function __construct($conn) {
        $this->conn = $conn;
        $config = require __DIR__ . '/../../private/config/config.php';
        $this->loggerService = new LoggerService("BenutzerService.php", $config['LOG_PATH']);
    }

    // CREATE
    public function create($benutzer) {

        // Prüfen, ob die E-Mail bereits existiert
        if ($this->existsByEmail($benutzer->Email)) {

            // Loggen
            $this->loggerService->logEreignis("Fehlgeschlagene Erstellung des Benutzers: E-Mail bereits vorhanden: " . $benutzer->Email);
            
            throw new PDOException("Benutzer mit dieser E-Mail existiert bereits.");
        }

        // Der 1. Benutzer soll 'admin' sein!
        $anzahl = $this->count();
        if ($anzahl == 0) {
            $benutzer->Rolle = 'admin';
        }

        // Salt generieren
        $benutzer->Salt = bin2hex(random_bytes(16));

        // Passwort mit Salt hashen
        $benutzer->Passwort = password_hash($benutzer->Passwort . $benutzer->Salt, PASSWORD_DEFAULT);

        $sql = "INSERT INTO benutzer (Name, Email, Passwort, Rolle, Salt) VALUES (:Name, :Email, :Passwort, :Rolle, :Salt)";
        $cmd = $this->conn->prepare($sql);
        $cmd->bindParam(':Name', $benutzer->Name);
        $cmd->bindParam(':Email', $benutzer->Email);
        $cmd->bindParam(':Passwort', $benutzer->Passwort);
        $cmd->bindParam(':Rolle', $benutzer->Rolle);
        $cmd->bindParam(':Salt', $benutzer->Salt); // Salt speichern
        $cmd->execute();
        
        // Loggen
        $this->loggerService->logEreignis("Neuer Benutzer erstellt: Name = " . $benutzer->Name . ", E-Mail = " . $benutzer->Email);

        return $this->conn->lastInsertId();  // ID zurückgeben
    }

    // READ
    public function get($id = null) {
        if ($id) {
            $sql = "SELECT * FROM benutzer WHERE BenutzerId = :id";
            $cmd = $this->conn->prepare($sql);
            $cmd->bindParam(':id', $id);
            $cmd->execute();
            $result = $cmd->fetch(PDO::FETCH_OBJ);  // Einen Eintrag zurückgeben

            // Loggen
            $this->loggerService->logEreignis("Benutzer abgerufen: ID = " . $id);

            return $result;
        } else {
            $sql = "SELECT * FROM benutzer";
            $cmd = $this->conn->query($sql);
            $result = $cmd->fetchAll(PDO::FETCH_OBJ);  // Alle Einträge zurückgeben

            // Loggen
            $this->loggerService->logEreignis("Alle Benutzer abgerufen.");

            return $result;
        }
    }

    // UPDATE
    public function update($benutzer) {
        // Aktuellen Benutzer aus der DB abrufen
        $aktuellerBenutzer = $this->get($benutzer->BenutzerId);

        // Salt verwenden oder beibehalten
        $benutzer->Salt = $aktuellerBenutzer->Salt;

        // Nur hashen, wenn ein neues Passwort übergeben wurde
        if (!empty($benutzer->Passwort)) {
            $benutzer->Passwort = password_hash($benutzer->Passwort . $benutzer->Salt, PASSWORD_DEFAULT);
        } else {
            // Behalte das alte Passwort, wenn kein neues angegeben wurde
            $benutzer->Passwort = $aktuellerBenutzer->Passwort;
        }

        $sql = "UPDATE benutzer SET Name = :Name, Email = :Email, Passwort = :Passwort, Rolle = :Rolle, Salt = :Salt WHERE BenutzerId = :BenutzerId";
        $cmd = $this->conn->prepare($sql);
        $cmd->bindParam(':BenutzerId', $benutzer->BenutzerId);
        $cmd->bindParam(':Name', $benutzer->Name);
        $cmd->bindParam(':Email', $benutzer->Email);
        $cmd->bindParam(':Passwort', $benutzer->Passwort);
        $cmd->bindParam(':Rolle', $benutzer->Rolle);
        $cmd->bindParam(':Salt', $benutzer->Salt); // Salt beibehalten oder aktualisieren

        $erfolgreich = $cmd->execute();

        // Loggen
        if ($erfolgreich) {
            $this->loggerService->logEreignis("Benutzer aktualisiert: Name = " . $benutzer->Name . ", E-Mail = " . $benutzer->Email);
        } else {
            $this->loggerService->logEreignis("Fehlgeschlagene Aktualisierung für Benutzer: ID = " . $benutzer->BenutzerId);
        }

        return $erfolgreich;  // True, wenn erfolgreich
    }

    // DELETE
    public function delete($id) {
        $sql = "DELETE FROM benutzer WHERE BenutzerId = :id";
        $cmd = $this->conn->prepare($sql);
        $cmd->bindParam(':id', $id);

        $erfolgreich = $cmd->execute();

        // Loggen
        if ($erfolgreich) {
            $this->loggerService->logEreignis("Benutzer gelöscht: ID = " . $id);
        } else {
            $this->loggerService->logEreignis("Fehlgeschlagene Löschung für Benutzer: ID = " . $id);
        }

        return $erfolgreich;  // True, wenn erfolgreich
    }

    // COUNT
    public function count() {
        $sql = "SELECT COUNT(*) as anzahl FROM benutzer";
        $cmd = $this->conn->query($sql);
        $anzahl = $cmd->fetch(PDO::FETCH_OBJ)->anzahl;  // Anzahl der Benutzer zurückgeben

        // Loggen
        $this->loggerService->logEreignis("Anzahl der Benutzer abgerufen: " . $anzahl);

        return $anzahl;
    }

    // Überprüfung der Login-Daten
    public function checkLogin($email, $passwort) {
        // Den Benutzer anhand der E-Mail-Adresse abrufen
        $sql = "SELECT * FROM benutzer WHERE Email = :Email";
        $cmd = $this->conn->prepare($sql);
        $cmd->bindParam(':Email', $email);
        $cmd->execute();
        $benutzer = $cmd->fetch(PDO::FETCH_OBJ);

        // Überprüfen, ob der Benutzer gefunden wurde
        if ($benutzer) {
            $salt = $benutzer->Salt;
            $hashedPassword = $benutzer->Passwort;

            // Passwort und Salt kombinieren und überprüfen
            if (password_verify($passwort . $salt, $hashedPassword)) {

                // Loggen
                $this->loggerService->logEreignis("Login erfolgreich: E-Mail = " . $email);

                return true;  // Login erfolgreich
            } else {

                // Loggen
                $this->loggerService->logEreignis("Fehlgeschlagener Login-Versuch: Falsches Passwort für E-Mail = " . $email);

                return false; // Falsches Passwort
            }
        } else {

            // Loggen
            $this->loggerService->logEreignis("Fehlgeschlagener Login-Versuch: Benutzer nicht gefunden für E-Mail = " . $email);

            return false; // Benutzer nicht gefunden
        }
    }
    
    // Benutzer abrufen
    public function getByEmail($email) {
        $sql = "SELECT * FROM benutzer WHERE Email = :Email";
        $cmd = $this->conn->prepare($sql);
        $cmd->bindParam(':Email', $email);
        $cmd->execute();
        $result = $cmd->fetch(PDO::FETCH_OBJ);  // Benutzer zurückgeben
        
        // Loggen
        $this->loggerService->logEreignis("Benutzer abgerufen: E-Mail = " . $email);

        return $result;
    }

    // prüfen, ob Benutzer existiert
    public function existsByEmail($email) {
        $sql = "SELECT COUNT(*) as anzahl FROM benutzer WHERE Email = :Email";
        $cmd = $this->conn->prepare($sql);
        $cmd->bindParam(':Email', $email);
        $cmd->execute();
        $anzahl = $cmd->fetch(PDO::FETCH_OBJ)->anzahl;  // true zurückgeben, wenn Benutzer existiert
        
        // Loggen
        $this->loggerService->logEreignis("Prüfung, ob Benutzer existiert: E-Mail = " . $email . ", Ergebnis = " . ($anzahl > 0 ? 'Ja' : 'Nein'));

        return $anzahl > 0;
    }

    // prüfen, ob der Benutzer einen Punkt als Name enthält
    public function existsByEmailAndDotInName($email) {
        $sql = "SELECT COUNT(*) as anzahl FROM benutzer WHERE Email = :Email AND Name LIKE '.'";
        $cmd = $this->conn->prepare($sql);
        $cmd->bindParam(':Email', $email);
        $cmd->execute();
        $anzahl = $cmd->fetch(PDO::FETCH_OBJ)->anzahl;  // true zurückgeben, wenn ein Benutzer gefunden
        
        // Loggen
        $this->loggerService->logEreignis("Prüfung, ob Benutzer mit Punkt im Namen existiert: E-Mail = " . $email . ", Ergebnis = " . ($anzahl > 0 ? 'Ja' : 'Nein'));

        return $anzahl > 0;
    }
}
