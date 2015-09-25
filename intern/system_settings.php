<?php
 /**
  * Die Systemeinstellungen.
  *
  * Dieses File enthält die Einstellungen des Wachplan Programm. Dabei stehen folgende
  * Funktionen zur Verfügung.
  * - Hinzufügen von Benutzern
  * - Ändern von den Daten von Benutzern (inklusive der Rechte)
  * - Hinzufügen von Wachtagen zum Wachplan
  * - Anzeige des vorläufigen Wachplan (+ möglichkeit diesen in den Echten zu Übertragen)
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
require_once '../init.php';
checkSession();
checkRights(2);
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
		<?php require 'template/ss_add_user.php'; ?>
	</div>
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
//@todo Fehlermeldungen als Funktion ausgliedern.
//@todo An die allgemeine Fehlermeldung anpassen und ggf. in das File back_add_user.php ausgliedern.
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
