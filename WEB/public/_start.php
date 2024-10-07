<?php  $config = require_once __DIR__ . '/../private/config/config.php'; ?>
<?php ob_start(); // Starte den Ausgabe-Puffer ?>

<?php require __DIR__ . '/_menu.php'; ?>

<?php 
$menu = ob_get_clean(); // Puffer übernehmen
ob_start(); // Starte den Ausgabe-Puffer 
?>

<script>

    // API-URL für AJAX-Anfragen
    localStorage.setItem('apiBaseURL', '<?php echo $config['API_BASE_URL'] ; ?>');

</script>

