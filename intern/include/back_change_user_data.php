<?php
 /**
  * Hierrüber können die Daten von Benutern geändert werden.
  *
  * PHP versions 5
  *
  * LICENSE: This source file is subject CC BY 4.0 license
  *
  * @author  Sebastian Friedl <friedl.sebastian@web.de>
  * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
  * @version GIT:  $Date: Sun May 10 09:51:12 2015 +0200$
  * @link    http:/salem.dlrg.de
  **/
require_once '../../init.php';
checkSession();
// TODO die prüfung ob die Daten korrekt sind erfolgt nicht!

/*
 * Benutzerdaten ändern
 */

if (isset($_POST['user_name']) === TRUE) {
    if ($_POST['eh'] === 'TRUE') {
        $_POST['med'] = 'EH';
    }

    if ($_POST['san'] === 'TRUE') {
        $_POST['med'] = 'san';
    }

    $stmtChange = $database->prepare(
        'UPDATE `wp_user` SET
         `friend`=?,
         `email`=?,
         `user_name`=?,
         `telephone`=?,
         `geburtsdatum`=?,
         `abzeichen`=?,`med`=?,
         `first_name`=?,
         `last_name`=?,
         `rights`=?
         WHERE `id_user`=?');
    $stmtChange->bind_param(
        'issssssssii',
        $_POST['friend'],
        $_POST['email'],
        $_POST['user_name'],
        $_POST['tele'],
        $_POST['gb'],
        $_POST['abzeichen'],
        $_POST['med'],
        $_POST['first_name'],
        $_POST['last_name'],
        $_POST['rights'],
        $_POST['id']);
    if ($stmtChange->execute() === FALSE) {
        echo '<div class=meldung> Fehler beim Eintragen.<br>
              <a class="button" onclick="hide_massage()" >schließen</a></div>';
    } else {
        echo '<div class=meldung>Daten geändert!<br>
              <a class="button" onclick="hide_massage()" >schließen</a></div>';
    }
}//end if
