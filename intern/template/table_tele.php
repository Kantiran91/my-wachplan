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
  * Template: Tabelle für den Teleonliste
  *
  * die Telefonliste enthält folgende Angaben:
  * - Vor und Nachname
  * - eMail
  * - Telefonnummer
  * - Abzeichen
  **/
require_once '../init.php';
checkSession();

/*
 * Tabellen für darstellung holen.
 */

// Holt alle Daten für die Telefonliste
$queryTele = '
SELECT  `first_name`,
        `last_name` ,
        `email`,
        `telephone`,
        `abzeichen`
FROM  `wp_user` ORDER BY `user_name` ';
$resultTele = $database->query($queryTele);
?>
<table>
	<thead>
		<tr>
			<th>Vorname</th>
			<th>Nachname</th>
			<th>E-Mail</th>
			<th>Telefonnummer</th>
			<th>Abzeichen</th>
		</tr>
	</thead>
		<?php
while ($row = $resultTele->fetch_row()) {
    echo '<tr>';
    echo '<td>';
    // Vorname
    echo $row[0];
    echo '</td>';
    echo '<td>';
    // Nachname
    echo $row[1];
    echo '</td>';
    echo '<td>';
    // E-Mail
    echo $row[2];
    echo '</td>';
    echo '<td>';
    // Tele
    echo $row[3];
    echo '</td>';
    echo '<td>';
    // Abzeichen
    echo $row[4];
    echo '</td>';
    echo "</tr>\n";
}//end while
?>
		</table>
