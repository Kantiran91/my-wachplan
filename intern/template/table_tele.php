<?php
 /**
  * Template: Tabelle für den Teleonliste
  *
  * die Telefonliste enthält folgende Angaben:
  * - Vor und Nachname
  * - eMail
  * - Telefonnummer
  * - Abzeichen
  *
  * PHP versions 5
  *
  * LICENSE: This source file is subject CC BY 4.0 license
  *
  * @author  Sebastian Friedl <friedl.sebastian@web.de>
  * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
  * @version GIT:  $Date: Thu May 7 14:43:07 2015 +0200$
  * @link    http:/salem.dlrg.de
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
