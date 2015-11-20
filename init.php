<?php
/**
 * Die Datei inisalisiert alle wichtigen Werte vorab
 * und bindet wichtige Dateien vorab ein.
 *
 * Die folgenden Dinge werden hier erledigt:
 * - Die DEBUG Funktionen werden ein und ausgestellt
 * - Die Umlaute werden in UFT-8 dargesteööt
 * - Die Sessionparamter werden eingestellt
 * - Die Konstanten werden festgelegt
 * - Es wird ein DB Verbindung mit sqli aufgebaut ($database)
 * - Die Wichtigesten Include-Files werden eingelesen
 * - Globae Funktionen werden definiert
 *
 * PHP versions 5
 *
 * LICENSE: This source file is subject CC BY 4.0 license
 *
 * @author  Sebastian Friedl <friedl.sebastian@web.de>
 * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
 * @version GIT: $Id$ $Date: Sun May 10 09:51:12 2015 +0200$
 * @link    http:/salem.dlrg.de
 **/

// ---------- DEBUG --------------------//
// Bei RELEASE auf FALSE stellen
define('DEBUG', TRUE);
if (DEBUG === TRUE) {
    ini_set('display_errors', 'ON');
    session_set_cookie_params(10);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 'OFF');
    session_set_cookie_params(36000);
}

/*
 *  Die Funktion errorHandler leitet alle Fehlermeldungen in eine eigene Datei um.
 */

set_error_handler('errorHandler');
// ---------- END DEBUG ----------//

// ---------- PHP INI ----------//
// Damit Umlaute korret dargestellt werden: UTF-8 verwenden.
header('Content-Type: text/html; charset=utf-8');
// ---------- END PHP INI ----------//

// ---------- SESSION ----------//
// Startet eine Session für 3600sec = 60 minuten
session_start();
// ---------- END SESSION ----------//

// ---------- CONSTANTEN ----------//
// Alle Konstanten werden hier festgelegt
// name des root verzeichnis
define('ROOT', realpath(dirname(__FILE__)));
// Versionsnummer des RELEASE
define('VERSION', 'Version 0.5');

// ---------- END CONSTANTEN ----------//

// ---------- INCLUDES ----------//
if ((include_once ROOT . '/config/config.php') === FALSE) {
    echo 'config Datei nicht vorhanden.';
}

require_once ROOT . '/include/mail.inc';
require_once ROOT . '/include/header.php';

// ---------- END INCLUDES ----------/

// ---------- SQL --------------------//
// SQL-Datenbank
$database = new mysqli($db['host'], $db['user'], $db['pass'], $db['name']);
$database->set_charset('utf8');
$GLOBALS['database'] = $database;
// ---------- END SQL ----------//

// ---------- GOLOBAL FUNCTIONS ----------//


/**
 * Prüft ob die Session noch gültig ist.
 *
 * Wenn die Session nicht mehr gültig ist, wird der Benutzer auf die Startseite
 * umgelenkt.
 *
 * @return boolean TRUE wenn die Session gültig ist
 */
function checkSession()
{
    if (isset($_SESSION['logged']) === FALSE || $_SESSION['logged'] === FALSE) {
        header('location: ../index.php?error=Sessionistabgelaufen');
        $_SESSION['local'] = $_SERVER['REQUEST_URI'];
        exit();
    } else {
        return TRUE;
    }

}//end checkSession()

/**
 * Prüft ob der Benutzer die Berechtigung für die Seite hat.
 *
 * Es gibt 4 Rechte
 * -0 User
 * -1 Wachleiter    können als Wachleiter eingetragen werden und Feedback geben
 * -2 Admins        haben alle Rechte
 *
 * @param integer $inputRights Rechte die für die Seite notwendig sind.
 * @param boolean $error       Wenn TRUE wird auf die Hauptseite verwiesen.
 * Wenn FALSE erfolgt keine Reaktion. Es wird nur TRUE bzw. FALSE zurückgegeben.
 *
 * @return boolean TRUE wenn die Person berechtigt ist.
 */
function checkRights($inputRights, $error = TRUE)
{
    if ($_SESSION['rights'] < $inputRights) {
        if ($error === TRUE) {
            header('location: index.php');
        }

        return FALSE;
    } else {
        return TRUE;
    }

}//end checkRights()

/**
 * Prüft ob die Benutzer ID mit der User ID in der Session übereinstimmt.
 *
 * @param integer $userID ID die geprüft werden soll.
 * @param boolean $error  Wenn TRUE wird auf die Hauptseite verwiesen.
 * Wenn FALSE erfolgt keine Reaktion. Es wird nur TRUE bzw. FALSE zurückgegeben.
 *
 * @return boolean TRUE wenn die Person berechtigt ist.
 */
function checkID($userID, $error = TRUE)
{
    if ((int) $_SESSION['id'] === $userID) {
        if ($error === TRUE) {
            header('location: index.php');
        }

        return FALSE;
    } else {
        return TRUE;
    }

}//end checkID()

/**
 * Prüft ob ein Datum in der Vergangenheit liegt.
 *
 * @param string $date Datum das mit dem Datum von heute verglichen werden soll.
 *
 * @return boolean TRUE wenn das Datum in der Vergangenheit liegt.
 */
function checkPast($date)
{
    if (strtotime($date) >= time()) {
        return FALSE;
    } else {
        return TRUE;
    }

}//end checkPast()

/**
 * Die Funktion verschlüsstel ein Passowrt über den sha512 Schlüssel.
 *
 * Die Funktion verschlüssel die Passworte. Dafür wird der
 * sha512 hash genutzt.Zusätzlich kann ein Salt eingeben werden. Wird keiner
 * Angegeben wird ein statischer Salt genommen.
 *
 * @param string $password Passwort für das ein Hash erzeugt werden soll.
 * @param string $salt     Der Salt für das Passwort
 * (bei statische enfällt diese Option).
 *
 * @return string          Gibt den Hash-Wert zurück
 */
function encryptHash($password, $salt = 'c1ab2c7a')
{
    $saltedPassword = $salt . $password;
    $passwordHash = hash('sha512', $saltedPassword);
    return $passwordHash;

}//end encryptHash()

/**
 * Gibt die Wachtage aus der Datenbank aus.
 *
 * Holt die Wachtage aus der Datebnbank. Dabei wird ein Array zurückgeben,
 * Dabei ist jeder Wachtag wieder ein Array bestehen aus ID(0) und Datum als
 * String(1)
 *
 * @return multitype:string  Array der Wachtage. Für den Aufbau siehe Beschreibung
 */
function getDays()
{
    $tage = array();
    $database = $GOBALS['database'];
    $prepare = $database->prepare('SELECT * FROM `wp_days` ORDER BY `date`');
    $prepare->execute();
    $prepare->bind_result($dayId, $date);
    $prepare->store_result();
    while ($prepare->fetch()) {
        $tag[0] = $dayId;
        $tag[1] = $date;
        $tage[] = $tag;
    }

    return $tage;

}//end getDays()

/**
 * Speichert den Fehler zusätzlich in einem Fehler LOG.
 *
 * @param string $fehlercode  Code des Fehlers.
 * @param string $fehlertext  Beschriebung des Fehlers.
 * @param string $fehlerdatei Datei in der der Fehler aufgetreten ist.
 * @param string $fehlerzeile Zeile in der der Fehler aufgetreten ist.
 *
 * @TODO  implement a class for error handling in php, frontend and javascript
 * @TODO  Bei RELEASE sollen ERROR noch angezeigt werden!
 * @TODO  Bei RLEASE sollen nur Error und warning gemappt werden.
 *
 * @return void|boolean Kommentar.
 */
function errorHandler($fehlercode, $fehlertext, $fehlerdatei, $fehlerzeile)
{
    $fehlerart;
    switch ($fehlercode) {
        case E_USER_ERROR:
            $fehlerart = 'ERROR';
        break;

        case E_USER_WARNING:
            $fehlerart = 'WARNING';
        break;

        case E_USER_NOTICE:
            $fehlerart = 'NOTICE';
        break;

        default:
            $fehlerart = 'Unbekannter Fehlertyp';
        break;
    }

    $zeile = '[' . date('Y-m-d H:i:s') . '] ';
    $zeile .= $fehlerart . ' : ' . $fehlertext . ' in ' . $fehlerdatei .
                 ' Zeile:' . $fehlerzeile . "\n";

    $datei = fopen(ROOT . '/phperror.log', 'a');
    fwrite($datei, $zeile);

    /*
     * Wenn DEBUG wird dem Benutzer der Fehler ausgegeben,ansonsten in nur in
     * die Datei umgeleitet
     */

    return (!DEBUG);

}//end errorHandler()

/**
 * Generiert eine Standardfehlermeldung für Benutzerfehler.
 *
 * @param string  $file    Name des Files in dem der Fehler aufgetreten ist.
 * @param integer $line    Zeile in der der Fehler aufgetreten ist.
 * @param string  $methode Methode in der der Fehler aufgetreten ist.
 *
 * @return void
 */
function errorMessage($file, $line, $methode = '')
{
    if ($methode === '') {
        trigger_error('Error in ' . $file . 'on Line' . $line);
    } else {
        trigger_error(
            'Error in ' . $methode . 'in File ' . $file . 'on Line ' . $line
        );
    }

}//end errorMessage()

/**
 * Sichere Zugriff auf die $_GET Parameter.
 *
 * @param string $param Name des Parameter der ausgegeben wird.
 *
 * @return string
 */
function get($param)
{
    return htmlentities($_GET[$param], ENT_QUOTES, 'utf-8');

}//end get()

/**
 * Sichere Zugriff auf die $_POST Parameter.
 *
 * @param string $param Name des Parameter der ausgegeben wird.
 *
 * @return string
 */
function post($param)
{
    return htmlentities($_POST[$param], ENT_QUOTES, 'utf-8');

}//end post()

// ---------- END GOLOBAL FUNCTIONS ----------//
