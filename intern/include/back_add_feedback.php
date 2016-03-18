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
  * Backendskript zum hinzufügen des Feedbacks zur Datenbank.
  **/
require_once '../../init.php';

// parse all intput
$feedbackInput['day'] = (int) $_GET['day'];
$feedbackInput['position'] = (int) $_GET['position'];
$feedbackInput['weather'] = (int) $_GET['weather'];
$feedbackInput['happend'] = (string) $_GET['happend'];
$feedbackInput['lifeguards'] = (int) $_GET['lifeguards'];
$feedbackInput['first_aid(little)'] = (string) $_GET['first_aid(little)'];
$feedbackInput['first_aid(big)'] = (int) $_GET['first_aid(big)'];
$feedbackInput['food'] = (int) $_GET['food'];
$feedbackInput['process'] = (int) $_GET['process'];
$feedbackInput['material'] = (string) $_GET['material'];
$feedbackInput['notice'] = (string) $_GET['notice'];
foreach ($feedbackInput as $value) {
    $database->real_escape_string($value);
}

// Hole das datum zum Tag
$queryDay = 'SELECT `id_day`, `date` FROM `wp_days` WHERE `id_day` = "' .
 $feedbackInput['day'] . '"';
$reslutDay = mysqli_fetch_row($database->query($queryDay));

// übersetzen für die Mail:
$position[1] = 'Wachleiter';
$position[2] = 'stellv. Wachleiter';
$weather[1] = 'Wolken los';
$weather[2] = 'leicht bewölkt';
$weather[3] = 'stark bewölkt / leichter Wind';
$weather[4] = 'teilweise leichte Regenschauer / starker Wind';
$weather[5] = 'teilweise starker Regen / Gewitter / leichter Sturm';
$weather[6] = 'Dauerregen / Gewitter / starker Sturm';

// send as mail
$mail = new mail('tl@salem.dlrg.de', 'Feedback: ' . date('d.m.Y', strtotime($reslutDay[1])));
$mailText = "Es ist ein Feedbackbogen eingegangen:\n";
$mailText .= 'Tag: ' . date('d.m.Y', strtotime($reslutDay[1]));
$mailText .= "\nPosition: " . $position[$feedbackInput['position']];
$mailText .= "\nWetter: " . $weather[$feedbackInput['weather']];
$mailText .= "\nWachdienst fand statt: " . $feedbackInput['happend'];
$mailText .= "\nAnzahl Rettungsschwimmer: " . $feedbackInput['lifeguards'];
$mailText .= "\nkleine Notfälle: " . $feedbackInput['first_aid(little)'];
$mailText .= "\ngroße Notfälle: " . $feedbackInput['first_aid(big)'];
$mailText .= "\nZufriedenheit Essen: " . $feedbackInput['food'];
$mailText .= "\nZufriedenheit gesamt: " . $feedbackInput['process'];
$mailText .= "\nBenutzte Material: \n" . $feedbackInput['material'];
$mailText .= "\n\nBemerkungen:\n " . $feedbackInput['notice'];
$mail->sendMail($mailText);

// create the db query
$query = ' INSERT INTO `wp_feedback`
		( `day_id`,
          `position`,
          `weather`,
          `happened`,
          `lifeguards`,
          `first_aid(small)`,
          `first_aid(big)`,
          `food`,
          `process`,
          `material`,
          `notice`)
		VALUES ( ';
$query .= $feedbackInput['day'] . ',';
$query .= $feedbackInput['position'] . ',';
$query .= $feedbackInput['weather'] . ',';
$query .= $feedbackInput['happend'] . ',';
$query .= $feedbackInput['lifeguards'] . ',';
$query .= '"' . $feedbackInput['first_aid(little)'] . '",';
$query .= $feedbackInput['first_aid(big)'] . ',';
$query .= $feedbackInput['food'] . ',';
$query .= $feedbackInput['process'] . ',';
$query .= '"' . $feedbackInput['material'] . '",';
$query .= '"' . $feedbackInput['notice'] . '")';

// starting the request
$database->query($query);
header('Location: ../index.php');

?>
