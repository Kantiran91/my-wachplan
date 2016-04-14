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
  * Gibt alle Benutzer als Formular aus.
  *
  * Mit diesem Formular kann eine Person ausgewählt werden.
  *
  *
  * @TODO    Die Ausgabe sollte verallgemeinert werden. Das heißt die Benutzer
  * sollen im JSON-Formant ausgegeben werden
  * @TODO    Trenne des Frontend vom Backend
  **/
require_once '../../init.php';
checkSession();

//@TODO ggf. dieser Codeteil der alle Benutzer ausgibt in eine eigene Funktion.
$day = get('day');
$position = get('position');
$query = 'SELECT `id_user`, `user_name` FROM `wp_user` ORDER BY `user_name`';
$result = $database->query($query);
while ($users[] = mysqli_fetch_row($result)) {
    NULL;
}

array_pop($users);

?>

<div class=meldung>
	<form id=eintrag action="include/back_add_acc.php" method="get">
		<input type="hidden" value="true" name="eingabe"> <input type="hidden"
			value="<?php echo $day; ?>" name="day" readonly size="10"> <input type="hidden"
			value="<?php echo $position; ?>" name="position" readonly size="10"> <select
			name="name">
		<?php
foreach ($users as $user) {
    echo '<option value="';
    echo $user[0] . '">';
    echo $user[1];
    echo '</option>' . "\n";
}
?>
		</select> <input class="button submit" type="submit" id="submit" value="senden"><br>
		<br> <a class="button" onclick="hide_massage()">schließen</a>
	</form>

</div>
