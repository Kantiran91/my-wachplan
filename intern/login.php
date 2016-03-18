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
 * Skript für den Login-Vorgang.
 *
 * Die Datei prüft die eingabe des Loginformulars
 * und schaut ob es eine Benutzer mit dem Namen gibt und das Passwort
 * übereinstimmt.
 *
 **/
require_once $_SERVER['DOCUMENT_ROOT'] .'/wachplan/init.php';
require_once INCLUDEPATH .'LdapUser.inc';
require_once INCLUDEPATH .'DefaultUser.inc';

if (empty($_POST['username']) === TRUE || empty($_POST['pass']) === TRUE) {
    header(
        'Location: ../index.php?error=Passwort oder Username nicht eingetragen');
    exit();
} else {
    $pUserName      = post('username');
    $pLoginPassword  = post('pass');

    $user = generateUserObject();

    if ($user == null){
        errorMessage(__FILE__,__LINE__,null,'Unknown Error');
        exit;
    }

    if (!$user->loginUser($pUserName,$pLoginPassword)){
        addLogLine('0');
        header('Location: ../index.php?error=Passwort oder Benutzername sind falsch');
        exit;
    }//end if

    addLogLine('1');
    $attributes = $user->getUserBaseAttributes($pUserName);

    $_SESSION['logged']         = TRUE;
    $_SESSION['id']             = $attributes['id_user'];
    $_SESSION['email']          = $attributes['email'];
    $_SESSION['user_name']      = $attributes['user_name'];
    $_SESSION['rights']         = $attributes['rights'];
    $_SESSION['userInterface']  = serialize ($user);

    if (isset($_SESSION['local']) === TRUE) {
        $_SESSION['local']  = $_SESSION['local'];
    } else {
        $_SESSION['local']  = 'index.php';
    }//end if

    header('Location:' . $_SESSION['local']);
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
   $database = $GLOBALS['database'];
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
