
var $message = $('#message');
var $loginLink = $('#loginLink');
var $logoutButton = $('#logoutButton');

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


$('#logoutButton').click(function() {

    if (localStorage.getItem('jwtToken')) {

        // Login-Daten löschen
        localStorage.removeItem('jwtToken');
        localStorage.removeItem('id');
        localStorage.removeItem('name');
        localStorage.removeItem('email');

        ausgabeLokaldaten();

        $message.text('Sie wurden erfolgreich ausgeloggt.');
        $loginLink.show(); // Link anzeigen, um zurück zum Index zu gelangen
        $logoutButton.hide();
        

    } else {
        $message.text('Sie waren nicht eingeloggt.');
    }
    
});

