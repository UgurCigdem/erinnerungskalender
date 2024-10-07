<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title> 

    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token']; ?>">

    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link href="./temp3/css/style.css" rel="stylesheet">

    <?php echo $head ?? "<!-- kein head -->"; ?>

</head>
<body>

    <div class="table">

    
        <header class="row header">
            
            <div class="cell rahmen"> </div>
            <div class="cell mitte rahmen">
                <img src="./temp3/img/logo1.png" id="logo" alt="Logo">
            </div>
            <div class="cell"> </div>
            
        </header>
    

    
        <div class="row">
            
            <nav class="cell streifen"> 
            
            <!-- Menüleiste - START -->

            <?php echo $menu; ?>

            <!-- Menüleiste - END -->
            
            </nav>
            

            
            <main class="cell mitte"> 
                <div id="inhalt">
                    
                    <!-- Dynamischer Inhalt - START -->

                    <?php echo $content; ?>

                    <!-- Dynamischer Inhalt - END -->

                    <div class="ecke ecke-links"></div> 
                    <div class="ecke ecke-rechts"></div> 
                    
                </div>
            </main>
            

            <div class="cell streifen"> </div>
        </div>

    </div>

    <footer class="fusszeile">
            <!-- Fusszeile einfügen -->
    </footer>


</body>
</html>
