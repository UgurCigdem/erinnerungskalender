
<ul class="menu">
    <li class="<?= istAktiv('index.php'); ?>"><a href="index.php">Home</a></li>
    <li class="<?= istAktiv('erinnerungen.php'); ?>"><a href="erinnerungen.php">Erinnerungen</a></li>
    <li class="<?= istAktiv('registrieren.php'); ?>"><a href="registrieren.php">Registrieren</a></li>
    <li class="<?= istAktiv('login.php'); ?>"><a href="login.php">Login</a></li>
    <li class="<?= istAktiv('logout.php'); ?>"><a href="logout.php">Logout</a></li>
</ul>


<?php
function istAktiv($page) {
    return basename($_SERVER['PHP_SELF']) == $page ? 'aktiv' : '';
}
?>

