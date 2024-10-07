<?php

// Konfiguration laden
$config = require __DIR__ . '/../../private/config/config.php';

class LoggerService
{
    private $quelle;
    private $logPath;

    /**
     * Konstruktor
     *
     * @param string $quelle Quelle der Log-Nachricht
     * @param string $logPath Pfad zur Logdatei
     */
    public function __construct($quelle, $logPath)
    {
        $this->quelle = $quelle;
        $this->logPath = __DIR__ . '/../' . $logPath;
    }

    /**
     * Ereignis loggen
     *
     * @param string $nachricht Nachricht, die geloggt werden soll
     */
    public function logEreignis($nachricht)
    {
        // Pfad prüfen, ob der Ordner existiert, andernfalls Ordner erstellen
        if (!file_exists($this->logPath)) {
            mkdir($this->logPath, 0777, true); // Rekursiv Ordner erstellen
        }

        // Dateiname im Format YYYY-MM-DD.log
        $datum = date('Y-m-d');
        $logDatei = $this->logPath . '/' . $datum . '.log';

        // Nachricht mit Zeitstempel und Quelle
        $datumUhrzeit = date('d.m.Y H:i:s');
        $nachricht = "[" . $datumUhrzeit . "] [" . $this->quelle . "] " . $nachricht . PHP_EOL;

        // Schreiben in die Logdatei
        if (file_put_contents($logDatei, $nachricht, FILE_APPEND) === false) {
            error_log("Fehler beim Schreiben in die Logdatei: " . $logDatei);
        }
    }
}

?>