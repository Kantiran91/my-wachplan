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
  * Backendskript zum Eintragen einer Person in den Wachplan.
  *
  * Hier wird eine Person in den Wachplan eingetragen. Dabe wird geprüft ob
  * die Person vom Benutzer eingetragen werden darf und ob die Person auch die
  * Position besetzten kann.
  * @TODO    Prüfen ob eine Mail verschickt wird wenn jemand eingetragen wird bzw. sich einträgt.
  **/
require_once '../../init.php';
checkSession();
//var_dump($_GET);
// Prüfen ob eine Eingabe vorhanden ist
if (isset($_GET['eingabe']) === TRUE && $_GET['eingabe'] === 'true') {
    // Prüfen die Person andere eintragen will und darf!
    if ((isset($_GET['name']) === TRUE) && ((int)get('name') === $_SESSION['id']) === FALSE) {
        checkRightsAndRedirect('wachplanAdmin');
        $userID = (int)$_GET['name'];
    } else {
        $userID = (int)$_SESSION['id'];
    }

    if (checkPosition($userID) === false)  {
        header(
            'Location: ../index.php?error=Die Person ist für den Posten nicht qualifiziert!');
        exit();
    }

    $day = get('day');
    if (checkIfDayIsNotInPast($day) === false)  {
        header(
            'Location: ../index.php?error=Der Tag liegt in der Vergangenheit!');
        exit();
    }

    // SQL Abfrage zum Einfügen
    $queryAdd = 'INSERT INTO `wp_access_user_days`
                 SET `user_id` = ?, `day_id`=?, `position`=?';
    $stmtAdd = $database->prepare($queryAdd);
    $stmtAdd->bind_param('iii', $userID, $_GET['day'], $_GET['position']);

    // Prüfen ob erfolgreich, ansonsten geben eine Fehlermeldung aus.!
    if ($stmtAdd->execute() === TRUE) {
        header('Location: ../index.php');
        exit();
    } else {
        header('Location: ../index.php?error=Eintragen in Datenbank fehlgeschlagen.');
        exit();
    }
} else {
    errorMessage(__FILE__,__LINE__,""," Input was not correct: " .$_GET['eingabe']);
    exit();
}

/**
 * @param userID
 * @param result Kommentar.
 * @todo LDAP anpassen
 */
function checkPosition($userID)
{
    if ($_GET['position'] <= 2) {
        $queryCheck = 'SELECT `user_name` FROM `wp_user` WHERE `rights` >= 1 and `id_user`=' . $userID;
        $result = $GLOBALS['database']->query($queryCheck);
        return $result->num_rows !== 1;
    }
}

/**
 * Kurze Beschreibung
 *
 * Lange Beschreibung
 * Kommentar.
 */

function checkIfDayIsNotInPast($day)
{
    // Prüfen ob der Tag in der Vergangenheit liegt.
    $stmtDay = $GLOBALS['database']->prepare(
        'SELECT `date` FROM `wp_days` WHERE `id_day` =?');
    $stmtDay->bind_param('s',$day);
    $stmtDay->execute();
    $stmtDay->bind_result($date);
    $stmtDay->fetch();
    $stmtDay->close();
    return !checkDateIsInPast($date);
}

//end if
