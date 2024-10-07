

let token;

var $lokalname = $('#lokalname');
var $lokalemail = $('#lokalemail');

function ausgabeLokaldaten(){
    if(localStorage.getItem('name')) 
        $lokalname.text('Name: ' + localStorage.getItem('name'));
    else
        $lokalname.text(' ');
    if(localStorage.getItem('email')) 
        $lokalemail.text('E-Mail: ' + localStorage.getItem('email'));
    else
        $lokalemail.text(' ');
}

ausgabeLokaldaten();


function einloggen(){

    let apiBaseURL = localStorage.getItem('apiBaseURL'); // 'http://localhost:5001/api/'
    let apiLoginUrl = apiBaseURL + '/login.php'; // 'http://localhost:5001/api/login.php'


    let email = $('#email').val();
    let passwort = $('#passwort').val();

    $.ajax({
        type: 'POST',
        //url: apiBaseURL + '/login.php', // 'http://localhost:5001/api/login.php'
        url: apiLoginUrl, // 'http://localhost:5001/api/login.php'
        contentType: 'application/json',
        data: JSON.stringify({ 
            Email: email, 
            Passwort: passwort 
        }),
        success: function(response) {
            $('#loginResponse').html(response.message);
            token = response.token;

            // ------------------------------------------
            if (response.token) {
                
                // Daten sichern im localStorage
                localStorage.setItem('jwtToken', response.token);
                localStorage.setItem('id', response.id);
                localStorage.setItem('name', response.name);
                localStorage.setItem('email', response.email);

                // Meldung anzeigen
                $('#loginResponse').html('Login erfolgreich! <br />');
                
                ausgabeLokaldaten();

                // Login-Formular ausblenden
                $('#loginForm').hide();

            } else {
                $('#loginResponse').html(response.message);
            }
            // ------------------------------------------

        }

    });

};

