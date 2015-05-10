<?php
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
  *
  * PHP versions 5
  *
  * LICENSE: This source file is subject CC BY 4.0 license
  *
  * @author  Sebastian Friedl <friedl.sebastian@web.de>
  * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
  * @version GIT:  $Date: Sun May 10 09:51:12 2015 +0200$
  * @link    http:/salem.dlrg.de
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

// TODO Als Funktion auslagern
$meta = $stmt->result_metadata();
while ($field = $meta->fetch_field()) {
    $params[] = &$row[$field->name];
}

call_user_func_array(
    array(
     $stmt,
     'bind_result',
    ),
    $params);

$result = array();
while ($stmt->fetch()) {
    foreach ($row as $key => $val) {
        $tmp[$key] = $val;
    }

    $result[] = $tmp;
}

echo json_encode($result);
$stmt->close();

?>
