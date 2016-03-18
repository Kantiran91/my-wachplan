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
  * Fügt einen einzelen Tag dem Wachplan hinzu
  **/
require_once '../../init.php';
checkSession();

$stmt = $database->prepare('INSERT INTO `wp_days`( `date`) VALUES (?)');
$stmt->bind_param('s', $day);
$day = date('Y-m-d', strtotime($_POST['day']));
$stmt->execute();
?>
<!-- Anzeige das alles funktuniert -->
<div class="meldung">
	Der neue Wachtag wurde hinzugefügt. <br> <a class="button"
		onclick="hide_massage()">schließen</a>
</div>


