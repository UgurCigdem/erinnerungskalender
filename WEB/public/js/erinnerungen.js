

let erinnerungCounter = 0; // Zählt die Erinnerungen
let aktuelleErinnerungId = null; // Speichert die ID für Bearbeitung


//localStorage.removeItem('id');

// Token aus dem localStorage auslesen
let token = localStorage.getItem('jwtToken');
let BenutzerId = localStorage.getItem('id');

let apiBaseURL = localStorage.getItem('apiBaseURL'); // 'http://localhost:5001/api/'
let apiLoginUrl = apiBaseURL + '/login.php'; // 'http://localhost:5001/api/login.php'
let apiBenutzerUrl = apiBaseURL + '/benutzer.php';
let apiErinnerungUrl = apiBaseURL + '/erinnerung.php';

// Token aus localStorage auslesen
//console.log("Token aus localStorage:", localStorage.getItem('jwtToken'));
//console.log("BenutzerId aus localStorage:", localStorage.getItem('id'));

// Wenn die BenutzerId vorhanden ist, E-Mail-Feld ausblenden
if (BenutzerId) {
    document.getElementById('erinnerung-email').style.display = 'none'; // Verstecken
    document.getElementById('erinnerung-email-span').style.display = 'none'; // Verstecken
    erinnerungenLaden();
}

//let token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0IiwiYXVkIjoiaHR0cDovL2xvY2FsaG9zdCIsImlhdCI6MTcyNzcwMjYyOCwibmJmIjoxNzI3NzAyNjI4LCJkYXRhIjp7ImlkIjoyLCJlbWFpbCI6InVndXJjaWdkZW1AZ21haWwuY29tIiwicm9sbGUiOiJ1c2VyIn19.OxZdqo17CZXjY7Q6Erd_p3py4JIjr7sKt26yK1Qg4zs';
//let BenutzerId = 2;

//console.log("Token aus localStorage:", token);
//console.log("BenutzerId aus localStorage:", BenutzerId);

// Beispieltoken, falls nichts im localStorage ist
//token = token || 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0IiwiYXVkIjoiaHR0cDovL2xvY2FsaG9zdCIsImlhdCI6MTcyNzcwMjYyOCwibmJmIjoxNzI3NzAyNjI4LCJkYXRhIjp7ImlkIjoyLCJlbWFpbCI6InVndXJjaWdkZW1AZ21haWwuY29tIiwicm9sbGUiOiJ1c2VyIn19.OxZdqo17CZXjY7Q6Erd_p3py4JIjr7sKt26yK1Qg4zs';


function datumFormatierenInYYYYMMDD(dateObj) {
    const year = dateObj.getFullYear();
    const month = String(dateObj.getMonth() + 1).padStart(2, '0'); // Monat 0-basiert, daher +1
    const day = String(dateObj.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

function datumFormatierenInDDMMYYYY(datum) {
    const dateObj = new Date(datum);
    return dateObj.toLocaleDateString('de-DE', {
        day: '2-digit',   // sorgt für zweistellige Tage
        month: '2-digit', // sorgt für zweistellige Monate
        year: 'numeric'   // gibt das Jahr vierstellig aus
    });
}

function datumFormatierenInDDMM(datum) {
    const dateObj = new Date(datum);
    return dateObj.toLocaleDateString('de-DE', {
        day: '2-digit',   // sorgt für zweistellige Tage
        month: '2-digit'  // sorgt für zweistellige Monate
    });
}



// Funktion zum Leeren des Formulars
function formularLeeren() {
    document.getElementById('erinnerungId').value = '0'; // Setze die ID zurück
    document.getElementById('erinnerung-tag').value = ''; // Leere das Tag-Feld
    document.getElementById('erinnerung-monat').value = ''; // Leere das Monat-Feld
    document.getElementById('erinnerung-bezeichnung').value = ''; // Leere die Bezeichnung
    document.getElementById('erinnerung-auswahl').value = '0'; // Setze die Auswahl zurück
    document.getElementById('erinnerung-email').value = ''; // Leere das E-Mail-Feld

    //alert("Das Formular wurde geleert.");
}


function erinnerungSenden() {
    
    // Aktuelles Jahr auslesen
    const aktuellesJahr = new Date().getFullYear();

    // Formularwerte auslesen
    const tag = document.getElementById('erinnerung-tag').value;
    const monat = document.getElementById('erinnerung-monat').value;
    const bezeichnung = document.getElementById('erinnerung-bezeichnung').value;
    const erinnerungWert = parseInt(document.getElementById('erinnerung-auswahl').value, 10); // Erinnerungstage als Zahl auslesen
    const email = document.getElementById('erinnerung-email').value;
    
    // ID
    const erinnerungId = document.getElementById('erinnerungId').value;

    if(erinnerungWert==0 || tag == "" || monat == "" || bezeichnung == "") {
        alert("Bitte alle Felder ausfüllen");
        return;
    }

    // DatumBis erstellen (das eingegebene Datum)
    let datumBis = new Date(aktuellesJahr, monat - 1, tag); // Monat 0-basiert

    // DatumVon berechnen 
    let datumVon = new Date(datumBis); 
    datumVon.setDate(datumVon.getDate() - erinnerungWert); // Tage abziehen

    // liegt in der Vergangenheit?
    const aktuellesDatum = new Date(); // aktuelles Datum
    if (datumVon < aktuellesDatum) {
        datumVon.setFullYear(datumVon.getFullYear() + 1); // 1 Jahr hinzufügen
    }

    //console.log("datumVon:", datumVon);
    //console.log("datumBis:", datumBis);

    // Datum formatieren
    datumBis = datumFormatierenInYYYYMMDD(datumBis);


    if (BenutzerId) {

        // ordentlicher Benutzer

        // Daten vorbereiten
        const data = {
            BenutzerId: BenutzerId, // Benutzer ID (Beispiel)
            Termin: datumBis, // YYYY-MM-DD Format
            Bezeichnung: bezeichnung, // Text aus Formular
            InTage: erinnerungWert, // InTage
        };

        // Ist ErinnerungId vorhanden --> PUT-Anfrage
        if (erinnerungId && erinnerungId !== '0') {

            // PUT-Anfrage (Aktualisieren)
            $.ajax({
                type: 'PUT',
                //url: `http://localhost:5001/api/erinnerung.php?BenutzerId=${BenutzerId}&id=${erinnerungId}`, // ID aus dem Formular
                url: `${apiErinnerungUrl}?BenutzerId=${BenutzerId}&id=${erinnerungId}`, // ID aus dem Formular
                contentType: 'application/json',
                headers: {
                    'Authorization': 'Bearer ' + token  // Bearer Token im Header
                },
                data: JSON.stringify(data),
                success: function(response) {
                    // Erfolgsrückmeldung
                    alert("Erinnerung erfolgreich aktualisiert.");

                    // Formular leeren nach dem Speichern
                    formularLeeren();

                    // Liste der Erinnerungen neu laden
                    erinnerungenLaden();
                },
                error: function(xhr, status, error) {

                    // Fehlerbehandlung
                    try {
                        const response = JSON.parse(xhr.responseText);
                        alert("Fehler beim Aktualisieren der Erinnerung: " + xhr.status + " - " + response.message);
                    } catch (e) {
                        alert("Fehler beim Aktualisieren der Erinnerung: " + xhr.status + " - " + xhr.responseText);
                    }
                
                }
            });

        } else {

            // POST-Anfrage (Erstellen)
            $.ajax({
                type: 'POST',
                //url: `http://localhost:5001/api/erinnerung.php?BenutzerId=${BenutzerId}`, // ID aus dem Formular
                url: `${apiErinnerungUrl}?BenutzerId=${BenutzerId}`, // ID aus dem Formular
                contentType: 'application/json',
                headers: {
                    'Authorization': 'Bearer ' + token  // Bearer Token im Header
                },
                data: JSON.stringify(data),
                success: function(response) {
                    // Erfolgsrückmeldung
                    alert(response.message);

                    // Formular leeren nach dem Speichern
                    formularLeeren();

                    // Liste der Erinnerungen neu laden
                    erinnerungenLaden();
                },
                error: function(xhr, status, error) {

                    // Fehlerbehandlung
                    try {
                        const response = JSON.parse(xhr.responseText);
                        alert(response.message || "Fehler beim Senden der Erinnerung.");
                    } catch (e) {
                        alert("Fehler beim Senden der Erinnerung: " + xhr.status);
                    }
                    
                }
            });

        }

    } 
    else {
        
        // außerordentlicher Benutzer

        // CSRF-Token aus dem Meta-Tag auslesen
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Daten vorbereiten
        const data = {
            Email: email, // Email aus Formular
            Termin: datumBis, // YYYY-MM-DD Format
            Bezeichnung: bezeichnung, // Text aus Formular
            InTage: erinnerungWert, // InTage
        };

        $.ajax({
            type: 'POST',
            url: 'api/erinnerung-gast.php',
            contentType: 'application/json',
            headers: {
                // Anonymus kein Token
                //'Authorization': 'Bearer ' + token,  // Bearer Token im Header
                'CSRF-Token': csrfToken,  // CSRF-Token im Header
            },
            data: JSON.stringify(data),
            success: function(response) {
                // Erfolgsrückmeldung
                alert(response.message);
            },
            error: function(xhr, status, error) {
                
                // Standard-Fehlerbehandlung
                try {
                    const response = JSON.parse(xhr.responseText);
                    alert(response.message || "Fehler beim Erstellen der Erinnerung.");
                } catch (e) {
                    alert("Fehler beim Erstellen der Erinnerung: " + xhr.status);
                }

            }
        });

    }


}


function erinnerungenLaden() {

    if (token) {

        // Methode	URL	Beschreibung	Authentifizierung
        // GET	/api/erinnerung.php	Liste aller Erinnerungen abrufen	JWT

        $.ajax({
            type: 'GET',
            //url: `http://localhost:5001/api/erinnerung.php?BenutzerId=${BenutzerId}`,
            url: `${apiErinnerungUrl}?BenutzerId=${BenutzerId}`,
            contentType: 'application/json',
            headers: {
                'Authorization': 'Bearer ' + token  // Bearer Token im Header
            },
            success: function(response) {
                // Erfolgsfall

                // Liste vor dem Hinzufügen der Daten leeren
                $('#erinnerung-liste-body').empty();

                if (response.length > 0) {
                    $('#erinnerung-liste').show(); // Tabelle anzeigen, wenn Daten geladen wurden
                    response.forEach(function(erinnerung) {
                        HinzufuegenZurTabelle(erinnerung);
                    });
                } else {
                    $('#erinnerung-liste').hide(); // Tabelle ausblenden, wenn keine Daten vorhanden sind
                }

            },
            error: function(xhr, status, error) {

                // Fehlerbehandlung
                try {
                    const response = JSON.parse(xhr.responseText);

                    // Fehler in der Webseite anzeigen
                    $('#erinnerung-response').html(response.message || "Fehler beim Abrufen der Erinnerungen.");
                    //alert(response.message || "Fehler beim Abrufen der Erinnerungen.");

                } catch (e) {
                    alert("Fehler beim Abrufen der Erinnerungen: " + xhr.status);
                }

            }

        });

    } else {
        alert("Kein Token vorhanden, bitte anmelden.");
    }
}  


function HinzufuegenZurTabelle(erinnerung) {

    const tableBody = document.getElementById('erinnerung-liste-body');
    const row = document.createElement('tr');
    row.setAttribute('id', `erinnerung-${erinnerung.ErinnerungId}`);

    // Datum formatieren
    //const datumFormatiert = datumFormatierenInDDMMYYYY(erinnerung.Termin);
    const datumFormatiert = datumFormatierenInDDMM(erinnerung.Termin);
    
    row.innerHTML = `
        <td>${datumFormatiert}</td>
        <td>${erinnerung.Bezeichnung}</td>
        <td>${erinnerung.InTage} Tage</td>
        <td>
            <nobr> 
                <button class="erinnerung-bearbeiten" onclick='event.preventDefault(); ErinnerungBearbeiten(${JSON.stringify(erinnerung)})'>bearbeiten</button> | 
                <button class="erinnerung-loeschen" onclick="event.preventDefault(); ErinnerungLoeschen(${erinnerung.ErinnerungId})">löschen</button>
            </nobr>
            <!-- 
            <a href="#" class="erinnerung-bearbeiten" onclick='ErinnerungBearbeiten(${JSON.stringify(erinnerung)})'>bearbeiten</a> | 
            <a href="#" class="erinnerung-loeschen" onclick="ErinnerungLoeschen(${erinnerung.ErrinnerungId})">löschen</a>
            -->

        </td>
    `;

    tableBody.appendChild(row);

}



function ErinnerungBearbeiten(erinnerung) {

    // ErinnerungId in das versteckte Formularfeld schreiben
    document.getElementById('erinnerungId').value = erinnerung.ErinnerungId;
    document.getElementById('erinnerung-tag').value = new Date(erinnerung.Termin).getDate().toString().padStart(2, '0');
    document.getElementById('erinnerung-monat').value = (new Date(erinnerung.Termin).getMonth() + 1).toString().padStart(2, '0');
    document.getElementById('erinnerung-bezeichnung').value = erinnerung.Bezeichnung;
    document.getElementById('erinnerung-auswahl').value = erinnerung.InTage;

    // Wenn die E-Mail nicht aus localStorage geladen wird, dann setzen
    if (erinnerung.Email) {
        document.getElementById('erinnerung-email').value = erinnerung.Email;
    }

    //console.log("Bearbeite Erinnerung:", erinnerung);

}

//------------------

function ErinnerungLoeschen(id) {

    // Benutzer um Bestätigung bitten
    const loeschenBestaetigung = confirm("Möchten Sie diesen Eintrag wirklich löschen?");

    if (loeschenBestaetigung) {
        // Wenn bestätigt, API mit DELETE aufrufen
        $.ajax({
            type: 'DELETE',
            //url: `http://localhost:5001/api/erinnerung.php?BenutzerId=${BenutzerId}&id=${id}`,
            url: `${apiErinnerungUrl}?BenutzerId=${BenutzerId}&id=${id}`,
            headers: {
                'Authorization': 'Bearer ' + token // Bearer Token im Header
            },
            success: function(response) {
                // Erfolgreich gelöscht
                alert("Der Eintrag wurde erfolgreich gelöscht.");
                // Entferne die gelöschte Zeile aus der Tabelle
                document.getElementById(`erinnerung-${id}`).remove();
            },
            error: function(xhr, status, error) {
                
                // Fehlerbehandlung
                try {
                    const response = JSON.parse(xhr.responseText);
                    alert(response.message || "Fehler beim Löschen der Erinnerung.");
                } catch (e) {
                    alert("Fehler beim Löschen der Erinnerung: " + xhr.status);
                }

            }
        });

    } else {
        // Abgebrochen
        alert("Löschen abgebrochen.");
    }

}


