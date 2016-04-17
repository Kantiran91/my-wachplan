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
  * Dies File zeigt eine Tabelle der Feedbacks die abgegben wurden.
  **/
require_once '../init.php';
checkSession();
$query = 'SELECT `feedback_id`,
				 `date`,
				 `position`,
				 `weather`,
				 `happened`,
				 `lifeguards`,
				 `first_aid(small)`,
				 `first_aid(big)`,
				 `food`,
				 `process`,
				 `material`,
				 `notice`
		FROM `wp_feedback`
		join `wp_days` on `wp_days`.`id_day` = `wp_feedback`.`day_id`
		Order by `date`, `position` ';
$result = $database->query($query);
while ($row[] = mysqli_fetch_row($result)) {
    NULL;
}

array_pop($row);
$happend[1] = 'Ja';
$happend[0] = 'Nein';
$position[1] = 'Wachleiter';
$position[2] = 'stellv. Wachleiter';
$weather[1] = 'Wolken los';
$weather[2] = 'leicht bewölkt';
$weather[3] = 'stark bewölkt / leichter Wind';
$weather[4] = 'teilweise leichte Regenschauer / starker Wind';
$weather[5] = 'teilweise starker Regen / Gewitter / leichter Sturm';
$weather[6] = 'Dauerregen / Gewitter / starker Sturm';

// Frontend
createHeader('Feedback');
?>
<body>
	<?php require 'template/menu.php'; ?>
	<div class="modul">
		<h1>Feedback Auswertung</h1>
		<table id='feedback_table'>
			<colgroup>
				<col width='50'>
				<col width='50'>
				<col width='50'>
				<col width='50'>
				<col width='50'>
				<col width='50'>
				<col width='50'>
				<col width='50'>
				<col width='50'>
				<col width='200'>
				<col width='200'>
			</colgroup>
			<thead>
				<tr>
					<th>Datum</th>
					<th>Position</th>
					<th>Wetter</th>
					<th>Erfolgte</th>
					<th>Rettungs schwimmer</th>
					<th>kleine Notfälle</th>
					<th>große Notfälle</th>
					<th>Essen</th>
					<th>Zufriedenheit</th>
					<th>Material</th>
					<th>Bemerkung</th>
				</tr>
			</thead>
			<tbody>
			<?php
foreach ($row as $cell) {
    echo '<tr>';
    echo '<td>';
    echo dateDe($cell[1]);
    echo '</td>';
    echo '<td>';
    echo $position[$cell[2]];
    echo '</td>';
    echo '<td>';
    echo $weather[$cell[3]];
    echo '</td>';
    echo '<td>';
    echo $happend[$cell[4]];
    echo '</td>';
    echo '<td>';
    echo $cell[5];
    echo '</td>';
    echo '<td>';
    echo $cell[6];
    echo '</td>';
    echo '<td>';
    echo $cell[7];
    echo '</td>';
    echo '<td>';
    echo $cell[8];
    echo '</td>';
    echo '<td>';
    echo $cell[9];
    echo '</td>';
    echo '<td>';
    echo $cell[10];
    echo '</td>';
    echo '<td>';
    echo $cell[11];
    echo '</td>';
    echo '</tr>';
}//end foreach
?>
			</tbody>
		</table>
	</div>
	<div id="foot"><?php echo VERSION; ?></div>
</body>
