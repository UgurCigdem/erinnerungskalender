<?php

class VorlageHelper {

    // die Vorlage aus einer Datei laden, Platzhalter ersetzen
    public static function ladeVorlage($vorlagenPfad, $platzhalter) {

        // Vorlage: '/../private/templates/temp2/template.html'
        // Schlüssselwörter: Name, Beschreibung, Datum

        // Datei einlesen
        if (!file_exists($vorlagenPfad)) {
            throw new Exception("Vorlagendatei nicht gefunden: " . $vorlagenPfad);
        }
        
        $vorlage = file_get_contents($vorlagenPfad);

        // Schlüsselwörter durch die Werte ersetzen
        foreach ($platzhalter as $schluessel => $wert) {
            $vorlage = str_replace("{{" . $schluessel . "}}", $wert, $vorlage);
        }

        // HTML-Inhalt zurückgeben
        return $vorlage;
    }

    public static function datumFormatierenInDDMMYYYY($datum) {
        $dateObj = DateTime::createFromFormat('Y-m-d', $datum);
        return $dateObj->format('d.m.Y');
    }

}
