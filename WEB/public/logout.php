<?php include './_start.php'; ?>

<link href="./css/logout.css" rel="stylesheet">

<?php require __DIR__ . '/_head.php'; ?>

<?php $title = "Logout";  ?>

<div class="logout-container">
    <h1>Logout</h1>
    
    <div id="lokalname"> </div>
    <div id="lokalemail"> </div>

    <button type="button" id="logoutButton">Logout</button>

    <div id="message"></div>
    
    <a href="index.php" id="loginLink" style="display: none;">Zurück zum Index</a>

</div>

<script src="./js/logout.js"></script>

<?php include './_end.php'; ?>