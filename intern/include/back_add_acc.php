<?php
 /**
  * Backendskript zum Eintragen einer Person in den Wachplan.
  *
  * Hier wird eine Person in den Wachplan eingetragen. Dabe wird geprüft ob
  * die Person vom Benutzer eingetragen werden darf und ob die Person auch die
  * Position besetzten kann.
  *
  * PHP versions 5
  *
  * LICENSE: This source file is subject CC BY 4.0 license
  *
  * @author  Sebastian Friedl <friedl.sebastian@web.de>
  * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
  * @version GIT: $Id$ $Date: Sun May 10 09:51:12 2015 +0200$
  * @link    http:/salem.dlrg.de
  * @TODO    Prüfen ob eine Mail verschickt wird wenn jemand eingetragen wird bzw. sich einträgt.
  **/
require_once '../../init.php';
checkSession();
// Prüfen ob eine Eingabe vorhanden ist
if (isset($_GET['eingabe']) === TRUE && $_GET['eingabe'] === 'TRUE') {
    // Prüfen die Person andere eintragen will und darf!
    if ((isset($_GET['name']) === TRUE) && ($_GET['name'] === $_SESSION['id']) === FALSE) {
        if (checkRights(2) === FALSE) {
            exit();
        }

        $userID = $_GET['name'];
    } else {
        $userID = $_SESSION['id'];
    }

    // Prüfen das keine Wachgänger als Wachleiter eingeteilt wird!
    if ($_GET['position'] <= 2) {
        $queryCheck = 'SELECT `user_name` FROM `wp_user` WHERE `rights` >= 1 and `id_user`=' .
         $userID;
        $result = $database->query($queryCheck);
        if ($result->num_rows !== 1) {
            header(
                'Location: ../index.php?error=Die Person ist für den Posten nicht qualifiziert!');
            exit();
        }
    }

    // Prüfen ob der Tag in der Vergangenheit liegt.
    $stmtDay = $database->prepare(
        'SELECT `date` FROM `wp_days` WHERE `id_day` =?');
    $stmtDay->bind_param('s', $_GET['day']);
    $stmtDay->execute();
    $stmtDay->bind_result($date);
    $stmtDay->fetch();
    $stmtDay->close();
    if (strtotime($date) < time()) {
        header(
            'Location: ../index.php?error=Das Datum liegt in der Vergangenheit!');
        exit();
    }

    // SQL Abfrage zum Einfügen
    $queryAdd = 'INSERT INTO `wp_access_user_days`
                 SET `user_id` = ?, `day_id`=?, `position`=?';
    $stmtAdd = $database->prepare($queryAdd);
    $stmtAdd->bind_param('sss', $userID, $_GET['day'], $_GET['position']);

    // Prüfen ob erfolgreich, ansonsten geben eine Fehlermeldung aus.!
    if ($stmtAdd->execute() === TRUE) {
        header('Location: ../index.php');
        exit();
    } else {
        $stmtDay->close();
        header('Location: ../index.php?error=Fehlercode back_add_acc1');
        exit();
    }
} else {
    header('Location: ../index.php?error=Fehlercode back_add_acc2');
    exit();
}//end if
