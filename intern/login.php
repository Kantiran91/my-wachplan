<?php
/**
 * Skript für den Login-Vorgang.
 *
 * Die Datei prüft die eingabe des Loginformulars
 * und schaut ob es eine Benutzer mit dem Namen gibt und das Passwort
 * übereinstimmt.
 *
 * PHP versions 5
 *
 * LICENSE: This source file is subject CC BY 4.0 license
 *
 * @author  Sebastian Friedl <friedl.sebastian@web.de>
 * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
 * @version GIT: $Date: Wed Apr 22 13:22:48 2015 +0200$
 * @link    http:/salem.dlrg.de
 **/
require_once '../init.php';
// prüfen, ob die eingabefelder ausgefüllt wurden
if (empty($_POST['username']) === TRUE || empty($_POST['pass']) === TRUE) {
    // wenn sie nicht ausgefüllt wurden
    header(
        'Location: ../index.php?error=Passwort oder Username nicht eingetragen');
    exit();
} else {
    // wenn sie ausgefüllt wurden
    // SQL-Daten ermitten und holen
    $mail = $database->real_escape_string($_POST['username']);
    $mail = $database->real_escape_string($_POST['pass']);

    $anfrage = '';
    $anfrage .= 'SELECT `id_user`, `email`,`user_name`, `hash`, `rights`
        FROM  `wp_user`
        WHERE `user_name`=';
    $anfrage .= '"' . $_POST['username'] . '" AND `hash` =';
    $anfrage .= '"' . encryptHash($_POST['pass']) . '"';
    $abfrageErgebnis = $database->query($anfrage);
    // prüfung, ob genau eine der spalten mit der eingabe überein stimmt
    if ($abfrageErgebnis->num_rows !== 1) {
        // wenn nicht übereinstimmt
        addLogLine('0');
        header('Location: ../index.php?error=Falsches Passwort');
        exit();
    } else {
        // wenn übereinstimmt

        addLogLine('1');
        // Session erzeugen und alle wichtigen Daten in dieser Speichern
        $zeile = mysqli_fetch_assoc($abfrageErgebnis);
        $_SESSION['logged'] = TRUE;
        $_SESSION['id'] = $zeile['id_user'];
        $_SESSION['email'] = $zeile['email'];
        $_SESSION['user_name'] = $zeile['user_name'];
        $_SESSION['rights'] = $zeile['rights'];
        if (isset($_SESSION['local']) === TRUE) {
            $_SESSION['local'] = $_SESSION['local'];
        } else {
            $_SESSION['local'] = 'index.php';
        }

        header('Location:' . $_SESSION['local']);
    }//end if
}//end if


/**
 * Es wird ein Log angelegt.
 *
 * Diese Funktion sollte später durch eine eigene Log-Klasse ersetzt werden,
 * die alle wichtigen Sachen mit logt.
 *
 * @param string $pwKorrekt Wenn das einloggen erflogreich 1,ansonsten 0.
 *
 * @return void
 */
function addLogLine($pwKorrekt)
{
   $database = $GOBALS['database'];
    $mail = $database->real_escape_string($_POST['username']);
    $anfrage = 'INSERT
    INTO `wp_log_login` (`id`, `username`, `datum`, `ip`, `pw_korrekt`)
    VALUES (';
    $anfrage .= 'NULL,';
    $anfrage .= "'" . $mail . "',";
    $anfrage .= "'" . date('Y-m-d H:i:s') . "',";
    $anfrage .= "'" . $_SERVER['REMOTE_ADDR'] . "',";
    $anfrage .= "'" . $pwKorrekt . "'";
    $anfrage .= ')';
    $database->query($anfrage);

}//end addLogLine()

?>
