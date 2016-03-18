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
  * Holt aus der Datenbank alle Daten eines Benutzers
  * und gibt dies im JSON-Format aus
  *
  * Zu diesen Daten gehören:
  * - ID
  * - Freund
  * - eMail
  * - Anmeldename
  * - Rechte
  * - Telefonnummer
  * - Geburtsdatum
  * - Abzeichen
  * - Medizinsche Qualifikation
  * - Vor und Nachname
  **/
require_once '../../init.php';
checkSession();

$query = 'SELECT
	    `id_user`,
		`friend`,
		`email`,
		`user_name`,
		`rights`,
		`telephone`,
		`geburtsdatum`,
		`abzeichen`,
		`med`,
		`first_name`,
		`last_name`
		FROM
		`wp_user`
		WHERE `id_user`=?';
$stmt = $database->prepare($query);
$stmt->bind_param('i', $_POST['user_id']);
$stmt->execute();

echo json_encode(getResultAsArray($stmt));
$stmt->close();

?>
