<?php require __DIR__ . '/_start.php'; ?>

<link href="./css/erinnerung.css" rel="stylesheet">

<?php require __DIR__ . '/_head.php'; ?>

<?php $title = "Erinnerungen";  ?>
    
    <div class="erinnerung-container">

        <h2>Erinnerungen</h2>

        <form onsubmit="event.preventDefault(); erinnerungSenden();">

            <input type="hidden" id="erinnerungId" value="0">

            <table class="erinnerung-form">
                <tr>
                    <td><b>Datum</b> (TT/MM)</label></td>
                    <td><b>Bezeichnung</b></label></td>
                    <td><b>Erinnerung</b></label></td>
                </tr>
                <tr>
                    <td>
                        
                            <input value="11" type="text" id="erinnerung-tag" placeholder="TT" maxlength="2">
                            <input value="12" type="text" id="erinnerung-monat" placeholder="MM" maxlength="2">
                        
                    </td>
                    <td><input value="Experiment 12345" type="text" id="erinnerung-bezeichnung" placeholder="Bezeichnung"></td>
                    <td>
                        <select id="erinnerung-auswahl">
                            <option value="0">--bitte auswählen--</option>
                            <option value="1">1 Tag</option>
                            <option value="2" selected>2 Tage</option>
                            <option value="4">4 Tage</option>
                            <option value="7">1 Woche</option>
                            <option value="14">14 Tage</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span id="erinnerung-email-span">E-Mail</span>
                    </td>
                    <td>
                        <input value="ugur0@ugur.at" type="text" id="erinnerung-email" placeholder="E-Mail">
                    </td>
                    <td>
                        
                        <button type="button" id="erinnerung-neu-button" onclick="formularLeeren();">NEU</button>
                        <button type="submit" id="erinnerung-speichern">SPEICHERN</button>
                            
                    </td>
                </tr>
            </table>
    
            <br />
    
            <table class="erinnerung-liste" id="erinnerung-liste">
                <thead>
                    <tr>
                        <th>Datum</th>
                        <th>Bezeichnung</th>
                        <th>Erinnerung</th>
                        <th>Aktion</th>
                    </tr>
                </thead>
                <tbody id="erinnerung-liste-body">
                    <!-- Hier werden die Erinnerungen über AJAX hinzugefügt -->
                </tbody>
    
            </table>

            <div id="erinnerung-response"></div>
        </form>
    </div>
    
    <script src="./js/erinnerungen.js"></script>

<?php require __DIR__ . '/_end.php'; ?>
