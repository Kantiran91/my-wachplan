<?php
 /**
  * Fügt mehere Wachtage in die Datenbank ein.
  *
  * PHP versions 5
  *
  * LICENSE: This source file is subject CC BY 4.0 license
  *
  * @author  Sebastian Friedl <friedl.sebastian@web.de>
  * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
  * @version GIT: $Date: Sun May 10 09:51:12 2015 +0200$
  * @link    http:/salem.dlrg.de
  **/
require_once '../../init.php';
checkSession();

// Hole die Start und Enddaten
$startDay = strtotime($_POST['start']);
$stopDay = strtotime($_POST['end']);

// Hole welche Wochentage eingetragen werden sollen
//TODO Prüfen ob das array schon im Frontend erzeugt werden kann.
$weekdays = array();
if (isset($_POST['mon']) === TRUE) {
    $weekdays[] = 1;
}

if (isset($_POST['di']) === TRUE) {
    $weekdays[] = 2;
}

if (isset($_POST['mi']) === TRUE) {
    $weekdays[] = 3;
}

if (isset($_POST['do']) === TRUE) {
    $weekdays[] = 4;
}

if (isset($_POST['fr']) === TRUE) {
    $weekdays[] = 5;
}

if (isset($_POST['sa']) === TRUE) {
    $weekdays[] = 6;
}

if (isset($_POST['so']) === TRUE) {
    $weekdays[] = 7;
}

// Finde alle Tage die Wachtage sind
$day = $startDay;
$dayArray = array();
while ($day <= $stopDay) {
    foreach ($weekdays as $weekday) {
        if ($weekday === date('N', $day)) {
            $dayArray[] = date('Y-m-d', $day);
        }
    }

    $day += 86400;
}

// Daten in die Datenbank schreiben.
$stmt = $database->prepare('INSERT INTO `wp_days`( `date`) VALUES (?)');
$stmt->bind_param('s', $day);
foreach ($dayArray as $newDay) {
    $day = $newDay;
    $stmt->execute();
}
?>
<!-- Anzeige das alles funktuniert -->
<div class="meldung">
	Die neuen Wachtage wurde hinzugefügt. <br> <a class="button"
		onclick="hide_massage()">schließen</a>
</div>


