<?php
/**
 * @author  Sebastian Friedl <friedl.sebastian@web.de>
 * @copyright Copyright (c) 2016, Sebastian Friedl
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

 /**
  * Eintragen eines Benutzers in den vorläufigen Wachplan.
  *
  * Dies dient der Planungsphase, deswegen wird hier die Position noch nicht
  * gespeichert.
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
