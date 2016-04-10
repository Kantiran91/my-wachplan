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
  * Die Systemeinstellungen.
  *
  * Dieses File enthält die Einstellungen des Wachplan Programm. Dabei stehen folgende
  * Funktionen zur Verfügung.
  * - Hinzufügen von Benutzern
  * - Ändern von den Daten von Benutzern (inklusive der Rechte)
  * - Hinzufügen von Wachtagen zum Wachplan
  * - Anzeige des vorläufigen Wachplan (+ möglichkeit diesen in den Echten zu Übertragen)
  **/
require_once '../init.php';
checkSession();
checkRightsAndRedirect('settings');
// for the userdata change
// get all userdata
$stmtUserdata = $database->prepare(
    'SELECT `id_user`, `user_name` FROM `wp_user` ORDER BY `user_name`');
$stmtUserdata->bind_result($idUser, $userName);
$stmtUserdata->execute();
$users = array();
while ($stmtUserdata->fetch()) {
    $users[] = array(
                $idUser,
                $userName,
               );
}

// FRONTEND
createHeader('Systemeinstellungen');
?>
<body>
	<?php require 'template/menu.php'; ?>
    <div class="modul">
		<?php require 'template/ss_change_user_data.php'; ?>
	</div>
    <div class="modul" id="newDays">
        <h2>Neue Wachtage hinzufügen</h2>
	<?php
require 'template/ss_add_days.php';
?>
	</div>
    <div class="modul" id="tempWachplan">
        <h2>Vorläufiger Wachplan</h2>
	<?php
require 'template/ss_temp_wachplan.php';
?>
<h2>Benutzer die noch nicht eingetragen sind.</h2>
	<?php
require 'template/ss_fehlend.php';
?>
	</div>
    <div class="modul" id="logout">
        <h1>
            <a href=logout.php>Logout</a>
        </h1>
    </div>
    <div id="foot"><?php echo VERSION; ?></div>
		<?php
//@TODO An die allgemeine Fehlermeldung anpassen und ggf. in das File back_add_user.php ausgliedern.
if (isset($_GET['add']) === TRUE) {
    echo '<div class=meldung>';
    if ($_GET['add'] === 'TRUE') {
        echo 'Neuer Benutzer angelegt!<br>';
    } else {
        echo 'Benutzer anlegen fehlgeschlagen';
    }

    echo '<a class="button" onclick="hide_massage()" >schließen</a>';
    echo '	</div>';
}
?>
</body>
</html>
