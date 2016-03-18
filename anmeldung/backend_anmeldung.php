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
 *  Backend des Anmeldeformulars.
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
