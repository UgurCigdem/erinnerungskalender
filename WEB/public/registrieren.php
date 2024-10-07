<?php require __DIR__ . '/_start.php'; ?>

<link href="./css/registrierung.css" rel="stylesheet">

<?php require __DIR__ . '/_head.php'; ?>

<?php $title = "Registrieren";  ?>

<div class="registrieren-container">
    <h2>Registrieren</h2>

        <form id="registerForm" class="registrieren-form" onsubmit="event.preventDefault(); registrieren();">
            <table>
                <tr>
                    <td>Name:</td>
                    <td><input type="text" id="name" class="registrieren-formular-feld" required></td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td><input type="email" id="email" class="registrieren-formular-feld" required></td>
                </tr>
                <tr>
                    <td>Passwort:</td>
                    <td><input type="password" id="passwort" class="registrieren-formular-feld" required></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button type="submit" id="registrieren-submit">REGISTRIEREN</button>
                    </td>
                </tr>
            </table>
        </form>
        <div id="registerResponse"></div>
    </div>


    <script src="./js/registrieren.js"></script>
    
    <?php require __DIR__ . '/_end.php'; ?>