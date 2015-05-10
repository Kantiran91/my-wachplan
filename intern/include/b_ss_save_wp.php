<?php
 /**
  * Backend Sichert die Daten von dem Vorläufigen Wachplan im Wachplan
  *
  * Dient der Funktion das ein vorläufiger Wachplan erstellt werden kann.
  * Die Daten die von diesem Wachplan kommen, werden hier in die Datenbank
  * gespeichert.
  *
  * PHP versions 5
  *
  * LICENSE: This source file is subject CC BY 4.0 license
  *
  * @author  Sebastian Friedl <friedl.sebastian@web.de>
  * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
  * @version GIT: $Id$ $Date: Sun May 10 09:51:12 2015 +0200$
  * @link    http:/salem.dlrg.de
  * @see     system_settings.php
  * @see     template/ss_temp_wachplan.php
  **/
require_once '../../init.php';
checkSession();
checkRights(2);

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
