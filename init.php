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
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 'OFF');
}

// ---------- END DEBUG ----------//

// ---------- PHP INI ----------//
// Damit Umlaute korret dargestellt werden: UTF-8 verwenden.
header('Content-Type: text/html; charset=utf-8');
// ---------- END PHP INI ----------//

// ---------- SESSION ----------//
// Startet eine Session für 3600sec = 60 minuten
session_set_cookie_params(3600);
session_start();
// ---------- END SESSION ----------//

// ---------- CONSTANTEN ----------//
// Alle Konstanten werden hier festgelegt
// name des root verzeichnis
define('ROOT', realpath(dirname(__FILE__)));
// Versionsnummer des RELEASE
define('VERSION', 'Version 0.4.2');

// ---------- END CONSTANTEN ----------//

// ---------- INCLUDES ----------//
if ((include_once ROOT . '/config/config.php') === FALSE) {
    echo 'config Datei nicht vorhanden.';
}

require_once ROOT . '/include/mail.inc';
require_once ROOT . '/include/header.php';

// ---------- END INCLUDES ----------//

// ---------- SQL --------------------//
// SQL-Datenbank
$database = new mysqli($db['host'], $db['user'], $db['pass'], $db['name']);
$database->set_charset('utf8');
// ---------- END SQL ----------//

// ---------- GOLOBAL FUNCTIONS ----------//


/**
 * Prüft ob die Session noch gültig ist.
 *
 * Wenn die Session nicht mehr gültig ist, wird der Benutzer auf die Startseite
 * umgelenkt.
 *
 * @return boolean TRUE wenn die Session gültig ist
 * @todo Ausgabe einer Fehlermeldung das die Session gültig ist
 */
function checkSession()
{
    if (isset($_SESSION['logged']) === FALSE || $_SESSION['logged'] === FALSE) {
        header('location: ../index.php');
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
 *
 * @param integer $userID ID die geprüft werden soll.
 * @param boolean $error Wenn TRUE wird auf die Hauptseite verwiesen.
 * Wenn FALSE erfolgt keine Reaktion. Es wird nur TRUE bzw. FALSE zurückgegeben.
 *
 * @return boolean TRUE wenn die Person berechtigt ist.
 */
function checkID($userID, $error = TRUE)
{
    if ($_SESSION['id'] == $userID) {
        if ($error === TRUE) {
            header('location: index.php');
        }

        return FALSE;
    } else {
        return TRUE;
    }

}//end checkRights()


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
    global $database;
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

// ---------- END GOLOBAL FUNCTIONS ----------//
