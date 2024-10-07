<?php require __DIR__ . '/_start.php'; ?>

<link href="./css/login.css" rel="stylesheet">

<?php require __DIR__ . '/_head.php'; ?>

<?php $title = "Login";  ?>

<div class="login-container">

    <h2>Login</h2>

    <div id="loginResponse"></div>

    <div id="lokalname"> </div>
    <div id="lokalemail"> </div>
    
    <form id="loginForm" class="login-form" onsubmit="event.preventDefault(); einloggen();">
        <table>
            <tr>
                <td>Email:</td>
                <td><input type="email" id="email" class="login-formular-feld" required></td>
            </tr>
            <tr>
                <td>Passwort:</td>
                <td><input type="password" id="passwort" class="login-formular-feld" required></td>
            </tr>
            <tr>
                <td colspan="2">
                    <button type="submit" id="login-submit">LOGIN</button>
                </td>
            </tr>
        </table>
    </form>
    
</div>

<script src="./js/login.js"></script>

<?php require __DIR__ . '/_end.php'; ?>
