<?php

class Erinnerung2 {

    // Eigenschaften der View
    public $ErinnerungId;
    public $BenutzerId;
    public $Name;
    public $Email;
    public $Von;  // Startdatum (Termin - InTage)
    public $Bis;  // Enddatum (Termin)
    public $Bezeichnung;

    // Konstruktor
    public function __construct($ErinnerungId, $BenutzerId, $Name, $Email, $Von, $Bis, $Bezeichnung) {
        $this->ErinnerungId = $ErinnerungId;
        $this->BenutzerId = $BenutzerId;
        $this->Name = $Name;
        $this->Email = $Email;
        $this->Von = $Von;
        $this->Bis = $Bis;
        $this->Bezeichnung = $Bezeichnung;
    }
    
}
