<?php
 /**
  * Fügt einen Benutzer in die Datenbank hinzu.
  *
  * Für das hinzufügen wird wie folgt vorgegangen:
  * - Prüfen ob die Eingabe korrekt ist
  * - Prüfen ob der Username schon vorhanden ist
  * - Erstellen eine Passworts
  * - Speichern des Benutzers in die Datenbank
  * - Versenden einer E-Mail mit dem Passwort
  *
  * PHP versions 5
  *
  * LICENSE: This source file is subject CC BY 4.0 license
  *
  * @author  Sebastian Friedl <friedl.sebastian@web.de>
  * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
  * @version GIT: $Date: Sun May 10 09:51:12 2015 +0200$
  * @link    http:/salem.dlrg.de
  **/
require_once '../../init.php';
checkSession();
// Check the length of the array
if ($_POST === NULL || count($_POST) === 9) {
    header('Location: ../system_settings.php?error=Nicht alle Felder ausgefüllt');
    exit();
}

// Prüfen ob der Benutzername schon vergeben ist!
$query = 'SELECT `id_user`FROM `wp_user` WHERE `user_name`=?';
$checkQuery = $database->prepare($query);
$checkQuery->bind_param('s', $_POST['username']);
$checkQuery->execute();
$checkQuery->store_result();
if ($checkQuery->num_rows !== 0) {
    header(
        'Location: ../system_settings.php?error=Benutzername existiert schon!');
    exit();
}


// Konvertiere alles in Strings
$post['username'] = (string) $_POST['username'];
$post['first_name'] = (string) $_POST['first_name'];
$post['last_name'] = (string) $_POST['last_name'];
$post['email'] = (string) $_POST['email'];
$post['telephone'] = (string) $_POST['tele'];
$post['geburtsdatum'] = (string) $_POST['gb'];
$post['abzeichen'] = (string) $_POST['abzeichen'];
$post['rights'] = (string) $_POST['rights'];
if (((bool) $_POST['eh']) === TRUE) {
    $post['med'] = 'eh';
}

if (((bool) $_POST['san']) === TRUE) {
    $post['med'] = 'san';
}


// Check if Felder empty
foreach ($post as $value) {
    if ($value === '') {
        header('Location: ../system_settings.php?error=Nicht alle Felder ausgefüllt');
        exit();
    }

    $database->real_escape_string($value);
}

// create user password
//TODO neuer Passwort gernerator verwenden.
$newpasswort = hash('crc32', $_POST['username'] . time() . $_SERVER['SERVER_ADDR']);
$pwHash = encryptHash($newpasswort);

    // create the db query
$query = $database->prepare(
    'INSERT INTO
		`wp_user`(
		`email`,
		`user_name`,
		`hash`,
		`rights`,
		`telephone`,
		`geburtsdatum`,
		`abzeichen`,
		`med`,
		`first_name`,
		`last_name`
		)
		VALUES (?,?,?,?,?,?,?,?,?,?)');
$query->bind_param(
    'sssissssss',
    $post['email'],
    $post['username'],
    $pwHash,
    $post['rights'],
    $post['telephone'],
    $post['geburtsdatum'],
    $post['abzeichen'],
    $post['med'],
    $post['first_name'],
    $post['last_name']);


// send as mail with Password
$betreff = 'Erflogreiche Anmeldung zum Wachplan der Ortsgruppe Salem';
$mail = new mail($post['email'], $betreff);
$text = 'Hallo ' . $post['first_name'] . ' ' . $post['last_name'] . ',';
$text .= "\n dir wurde von einem der Admins ein Account für den Wachplan
            der Ortsgruppe Salem erstellt.\n Hier kannst du eine Wachtermine
            sehen, denn Daten ändern und dich zusätzlich noch eintragen.
            Solltest du keinen Account brauchen oder nicht zu der DLRG OG Salem
            gehören, melde dich bitte sofort bei uns,
             damit wir das klären können.";
$text .= "\nFür diesen Wachplan benötigst du ein eigenes Passwort,diese lautet:";
$text .= " \n\n" .$newpasswort . "\n";
$text .= "\n---------------------------------------\n";
$text .= "Viele Grüße \n Deine Technische Leitung";
$mail->sendMail($text);


if ($query->execute() === TRUE) {
    header('Location: ../system_settings.php?add=TRUE');
} else {
    header('Location: ../system_settings.php?error=Fehler in der Datenbank!');
}




?>
