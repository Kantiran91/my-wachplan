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
  * Benutzer aus dem Wachplan austragen.
  **/
require_once '../../init.php';
checkSession();

// Hole die Daten zu der Verknüpfung die gelöscht werden soll!
$id = $database->real_escape_string($_GET['acc']);

$queryUser = 'SELECT `id_user`, `email`, `user_name`,`date`
			FROM `wp_user`
			Join `wp_access_user_days` ON `id_user` =`user_id`
			JOIN  `wp_days` ON  `id_day` =  `day_id`
			WHERE `wp_access_user_days`.`id` = ';
$queryUser .= '"' . $id . '"';

$result = $database->query($queryUser);
if (is_bool($result) === FALSE) {
    $user = $result->fetch_row();

    // Prüfen ob der Benutzer einen andern Benutzer löschen will und darf!
    if (($user[0] === $_SESSION['id']) === FALSE) {
        checkRightsAndRedirect('wachplanAdmin');
    }

    // Prüfen ob das zu löschen Datum in der Vergangenheit liegt.
    if (checkDateIsInPast($user[3]) === TRUE) {
        throw new Exception('Das Datum liegt in der Vergangenheit!');
    }
} else {
    throw new Exception('Fehler in der Datenbank! Bitte melde dich beim Administrator!');
}

// Informiere den Wachleiter bzw. Technischen Leiter
$betreff = 'Austragung am  ' . dateDe($user[3]);
if (($user[0] === $_SESSION['id'])) {
    $mailAdresse = 'tl@salem.dlrg.de';
    $meldung = new mail($mailAdresse, $betreff);
    $text = "Hallo Wachleiter \n Leider kann ich ". $user[1]. ' am ' .
                dateDe($user[3]) .
                 ' doch nicht am Wachdienst teilnehmen.';
} else {
        // Informiere den Wachgänger
    $meldung = new mail($user[1], $betreff);
    $text = 'Hallo' . $user[2] . " \n Du wurdest von einem Wachleiter am  " .
                 dateDe($user[3]);
    $text .= " aus dem Wachplan ausgetragen.\n Wir danken dir trotzdem für die
               Bereitschaft beim Wachdienst mit zuarbeiten.\n Wenn das Austragen
               nicht mit dir abgesprochen wurde, melde dich bitte bei un, damit
               wir das klären können.\n Viele Grüße \n Das Wachdienst Team";
}//end if

$meldung->setText($text);
$meldung->sendMail();

$queryDel = 'DELETE FROM `wp_access_user_days` WHERE `id`=';
$queryDel .= '"' . $id . '"';
if ($database->query($queryDel) === TRUE) {
    echo '<div class=meldung>';
    echo '<h4>Person gelöscht.</h4>';
    echo "Die Person wurde ausgetragen.\n";
    echo '<a class="button" onclick="change_window()">schließen</a>';
    echo '</div>';
} else {
    throw new Exception('Austragen des Wachgängers ist fehlgeschlagen!');
}
