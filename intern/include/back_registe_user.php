<?php
 /**
  * Eintragen eines Benutzers in den vorläufigen Wachplan.
  *
  * Dies dient der Planungsphase, deswegen wird hier die Position noch nicht
  * gespeichert.
  *
  * PHP versions 5
  *
  * LICENSE: This source file is subject CC BY 4.0 license
  *
  * @author  Sebastian Friedl <friedl.sebastian@web.de>
  * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
  * @version GIT:  $Date: Thu May 7 14:43:07 2015 +0200$
  * @link    http:/salem.dlrg.de
  **/
require_once '../../init.php';
checkSession();

// Bereite SQL Abfrage vor.
$stmt = $database->prepare(
'INSERT INTO `wp_poss_acc`(`user_id`, `day_id`) VALUES (?,?)');
$stmt->bind_param('ii', $_SESSION['id'], $dayID);

foreach ($_POST as $day) {
    $dayID = $day;
    $stmt->execute();
}

// Rückgabe an das Frontend
?>
<div class="meldung">
	Das Eintragen war erfolgreich! Du bekommst eine eMail sobald der engültige
	Wachplan fertig ist. <br> <a class="button" onclick="hide_massage()">schließen</a>
</div>
