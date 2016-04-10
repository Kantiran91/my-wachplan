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
  * Backend Sichert die Daten von dem Vorläufigen Wachplan im Wachplan
  *
  * Dient der Funktion das ein vorläufiger Wachplan erstellt werden kann.
  * Die Daten die von diesem Wachplan kommen, werden hier in die Datenbank
  * gespeichert.
  * @see     system_settings.php
  * @see     template/ss_temp_wachplan.php
  **/
require_once '../../init.php';
checkSession();
checkRightsAndRedirect('WachplanSettings');

$query = 'INSERT
INTO `wp_access_user_days`(`user_id`, `day_id`, `position`)
VALUES (?,?,?)';
$stmtSave = $database->prepare($query);
$stmtSave->bind_param('iii', $userID, $dayID, $pos);
//// var_dump($_POST);
foreach ($_POST as $key => $day) {
    $dayID = $key;
    if ($day['wl'] !== '') {
        $pos = 1;
        $userID = $day['wl'];
        $stmtSave->execute();
    }

    if ($day['wl2'] !== '') {
        $pos = 2;
        $userID = $day['wl2'];
        $stmtSave->execute();
    }

    if ($day['wg1'] !== '') {
        $pos = 3;
        $userID = $day['wg1'];
        $stmtSave->execute();
    }

    if ($day['wg2'] !== '') {
        $pos = 4;
        $userID = $day['wg2'];
        $stmtSave->execute();
    }

    if ($day['wg3'] !== '') {
        $pos = 5;
        $userID = $day['wg3'];
        $stmtSave->execute();
    }
}//end foreach

header('Location:../index.php');
