
<?php

class Benutzer {
    
    // Eigenschaften
    public $BenutzerId;
    public $Name;
    public $Email;
    public $Passwort;
    public $Salt;
    public $Rolle;

    // Konstruktor
    public function __construct($Name, $Email, $Passwort, $Rolle = 'user', $Salt = null) {
        $this->Name = $Name;
        $this->Email = $Email;
        $this->Passwort = $Passwort;
        $this->Rolle = $Rolle;
        
        // Salt generieren
        if ($Salt === null) {
            $this->Salt = bin2hex(random_bytes(16));  // Salt automatisch generieren
        } else {
            $this->Salt = $Salt;  // Wenn Salt übergeben wird
        }

    }
    
}

/*
Bsp:
$benutzer = new Benutzer("Max Mustermann", "max@mustermann.de", "passwort");
echo $benutzer->Name;  // Ausgabe: Max Mustermann
echo $benutzer->Salt;  // Ausgabe: zufälliger Salt-Wert

*/
