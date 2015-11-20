<?php
/**
 *  Backend des Anmeldeformulars.
 *
 * Lange Beschreibung des Datei-Inhaltes, wenn erforderlich.
 *
 * PHP versions 5
 *
 * LICENSE: This source file is subcject CC BY 4.0 license
 *
 * @author  Sebastian Friedl <friedl.sebastian@web.de>
 * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
 * @version GIT: $Date: Thu May 7 14:43:07 2015 +0200$
 * @link    http:/salem.dlrg.de
 **/
require_once '../init.php';

// Alte Funktion zum speichern in der CSV-Datei!
$handle = fopen('teilnehmer.csv', 'a+');

$line = '';
foreach ($_POST as $attr) {
    $line .= htmlentities($attr, ENT_QUOTES, 'utf-8');
    $line .= ';';
}

$line .= "\n";
fwrite($handle, $line);

// Sende eine Mail an Admins!
$nachricht = new mail('friedl.sebastian@web.de', 'Neuer Benutzer');

$text = "Ein neuer \nBenutzer " .post('email') . "muss\n  freigeschaltet werden";
$nachricht->setText($text);
$nachricht->sendMail();
