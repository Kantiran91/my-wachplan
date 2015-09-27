<?php
/**
 * Frontend zur Neuanforderung des Passwortes
 *
 * PHP versions 5
 *
 * LICENSE: This source file is subject CC BY 4.0 license
 *
 * @author  Sebastian Friedl <friedl.sebastian@web.de>
 * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
 * @version GIT: $Date: Thu May 7 14:43:07 2015 +0200$
 * @link    http:/salem.dlrg.de
 **/
require_once '../init.php';
include_once '../intern/aservice/UserSettings.inc';

if (count($_GET) !== 2) {
    header('Location: ../index.php?fehler=3');
    exit();
}

$stmtGet = $database->prepare(
    'SELECT `id_user`,`email`
    FROM `wp_user`
    WHERE `first_name`= ? AND `last_name`=? ');
$stmtGet->bind_param('ss', $_GET['first_name'], $_GET['last_name']);
$stmtGet->bind_result($id, $email);
$stmtGet->execute();
$stmtGet->store_result();
$stmtGet->fetch();

// Wenn nur eine Person mit dem Namen übereinstimmt, ist dies die Korrekte
// Person
if ($stmtGet->num_rows !== 1) {
    $stmtGet->close();
    // wenn nicht übereinstimmt
    header('Location: ../index.php?fehler=3');
    exit();
} else {
    // wenn übereinstimmt
    $stmtGet->close();
    // Passwort generieren
    $newpasswort = UserSettings::randomstring(9);
    $hashPass = encrypt_hash($newpasswort);

    // Passwort speichern
    $query = 'UPDATE `wp_user` SET `hash`= ? WHERE `id_user`= ?';
    $stmtSet = $database->prepare($query);
    $stmtSet->bind_param('si', $hashPass, $id);
    if ($stmtSet->execute() === TRUE) {
        $mail = new mail($email, 'neue Passwort');
        $mail->set_text("Dein neues Passwort lautet :\n" . $newpasswort . "\n");
        $mail->sendMail();
    }
}//end if
?>
<div class="meldung">
    <p>Benutzer gefunden</p>
    <br> Dein neues Passwort wird dir zugeschickt.<br> <a class="button"
        onclick="hide_massage()"
    >schließen</a>
</div>
