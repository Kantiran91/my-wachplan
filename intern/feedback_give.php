<?php
 /**
  * Feedback-Formular
  *
  * In diesem File ist ein Feedback-Formular für den Wachdienst. Damit ist es
  * möglich das die Wachleiter schneller Feedback an die Leitung abgeben.
  *
  * PHP versions 5
  *
  * LICENSE: This source file is subject CC BY 4.0 license
  *
  * @author  Sebastian Friedl <friedl.sebastian@web.de>
  * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
  * @version GIT:  $Date: Sun May 10 09:51:12 2015 +0200$
  * @link    http:/salem.dlrg.de
  **/
require_once '../init.php';
checkSession();

if (isset($_GET['day']) === FALSE) {
    header('Location: intern/index.php');
}

$input['day'] = $database->real_escape_string(Get('day'));

// Hole das datum zum Tag
$queryDay = 'SELECT `id_day`, `date` FROM `wp_days` WHERE `id_day` = "' .
 $input['day'] . '"';
$reslutDay = mysqli_fetch_row($database->query($queryDay));
createHeader('Feedback');
?>
<body>
<?php require 'template/menu.php'; ?>
<div class="modul" id="anleitung">
<h1>
Feedback für den <?php echo date('d.m.Y', strtotime($reslutDay[1])); ?>
</h1>
        Danke das du Hilfts den Wachdienst zu verbessern, bitte fülle das
        angebene Formular aus. Dies sollte dich nur max. 5 Minuten kosten.
    </div>
    <div class="modul" id="formular">
        <form id="feedback_formular" class="formFeedback"  method="GET"
            action="include/back_add_feedback.php"
        >
            <input type="hidden" name="day" value="<?php echo $reslutDay[0]; ?>">
            <h2>Allgemeine Angaben</h2>
            <label for="position">Welche Position hattest du an diesem Tag?</label>
            <br> <select name="position">
                <option value="1">Wachleiter</option>
                <option value="2">stellv. Wachleiter</option>
            </select> <br> Wie war das Wetter an diesem Tag?<br> <select
                name="weather"
            >
                <option value="1">Wolken los</option>
                <option value="2">leicht bewölkt</option>
                <option value="3">stark bewölkt / leichter Wind</option>
                <option value="4">
                teilweise leichte Regenschauer / starker Wind</option>
                <option value="5">teilweise starker Regen / Gewitter / leichter
                    Sturm</option>
                <option value="6">Dauerregen / Gewitter / starker Sturm</option>
            </select> <br>Fand an diesem Tag Wachdienst statt?<br> <label> <input
                type="radio" name="happend" value="TRUE" checked
            > Ja
            </label> <label> <input type="radio" name="happend" value="FALSE">
                Nein
            </label>
            <h2>Angaben zum Wachdienst</h2>
            Wie biele Wachgänger (inkl. dir) waren am Schlosssee?<br> <select
                name="lifeguards"
            >
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">>10</option>
            </select> <br>
             Wie viele kleinere Notfälle gabe es?(ungefähr)<br> <input
                type="text" name="first_aid(little)" value="n/a"
            > <br> Wie viele größere Notfälle gabe es?<br> <select
                name="first_aid(big)"
            >
                <option value="0">0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">>3</option>
            </select> <br> Wie zufrieden warst du mit der Verpflegung?<br> <select
                name="food"
            >
                <option value="0">keine Angabe</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
            </select> <br> Wie zufrieden warst du mit dem Ablauf des Wachdienst?<br>
            <select name="process">
                <option value="0">keine Angabe</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
            </select>
            <h2>sonstiges</h2>
            <br> Verbrauchtes Material / Fehlendes Material <br>
            <textarea name="material" cols="50" rows="10">(max. 180 Zeichen)</textarea>
            <br> Bemerkungen (Sollt es Probleme oder Einsätze gegeben haben,
            bitte hier eine kurze Beschreibung noch einfügen)<br>
            <textarea name="notice" cols="50" rows="10">(max. 180 Zeichen)</textarea>
            <br> <input type="submit" name="senden">
        </form>
    </div>
    <div id="foot"><?php echo VERSION; ?></div>
</body>
</html>
