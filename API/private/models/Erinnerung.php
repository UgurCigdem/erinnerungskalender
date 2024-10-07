<?php

class Erinnerung {
    
    // Eigenschaften
    public $ErinnerungId;
    public $BenutzerId;
    public $Termin;  
    public $Bezeichnung;
    public $InTage;
    public $GesendetImJahr;

    // Konstruktor
    public function __construct($BenutzerId, $Termin, $Bezeichnung, $InTage = null, $GesendetImJahr = null) {
        $this->BenutzerId = $BenutzerId;
        $this->Termin = $Termin;  // Datum für den Termin (format: YYYY-MM-DD)
        $this->Bezeichnung = $Bezeichnung;
        $this->InTage = $InTage;
        $this->GesendetImJahr = $GesendetImJahr;
    }

}

