
$('#registerForm').submit(function(event) {
    event.preventDefault();


    let apiBaseURL = localStorage.getItem('apiBaseURL'); // 'http://localhost:5001/api/'
    let apiRegisterUrl = apiBaseURL + '/register.php'; // 'http://localhost:5001/api/login.php'


    let name = $('#name').val();
    let email = $('#email').val();
    let passwort = $('#passwort').val();


    // Token auslesen
    // let csrfToken = $('meta[name="csrf-token"]').attr('content');  // Hier nicht nötig

    $.ajax({
        type: 'POST',
        //url: 'http://localhost:5001/api/register.php',
        url: apiRegisterUrl,
        contentType: 'application/json',
        data: JSON.stringify({ 
            Name: name,
            Email: email, 
            Passwort: passwort,
            //csrf_token: csrfToken // Token mit senden // Hier nicht nötig
         }),
        success: function(response) {
            $('#registerResponse').html(response.message);

            // Vorherige Login-Daten löschen
            localStorage.removeItem('jwtToken');
            localStorage.removeItem('id');
            localStorage.removeItem('name');
            localStorage.removeItem('email');

            // Formular ausblenden, nach erfolgreicher Registrierung 
            $('#registerForm').hide();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            
            // Fehlerhafte Antwort vom Server abfangen
            let errorMessage;

            try {
                // Versuchen, den Text in ein JSON-Objekt zu konvertieren
                const responseJson = JSON.parse(jqXHR.responseText);
                errorMessage = responseJson.message ? responseJson.message : "Unbekannter Fehler";
            } catch (e) {
                // Wenn das Parsen fehlschlägt, das Original-ResponseText anzeigen
                errorMessage = "Es ist ein Fehler aufgetreten: " + jqXHR.responseText;
            }

            // Fehlermeldung anzeigen
            alert(errorMessage);
            $('#registerResponse').html(errorMessage);


        }    
                    
    });

});

