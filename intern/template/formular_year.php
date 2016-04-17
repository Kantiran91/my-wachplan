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
  * Registierungsformular für die Benutzer für ein neues Jahr.
  **/
require_once '../init.php';
checkSession();

/*
 * Formular für das Eintragen im Wachplan holen.
 */

?>
<b>An folgenden Tagen kann ich Wachdienst machen:</b>
<form id="registeNewSaison">
    <table>
	<?php
$days = getDays();
$i = 1;
foreach ($days as $day) {
    if ($i === 1) {
        echo '<tr>';
    }

    echo '<td>';
    echo dateDe($day[1]);
    echo '<input type="checkbox" name="';
    echo $day[0];
    echo '" value="' . $day[0];
    echo '" ></td>';
    if ($i >= 5) {
        echo "</tr>\n";
        $i = 0;
    }

    $i ++;
}
?>
	</table>
    <br> <input class="button submit" type="submit" value="senden"> <br> Unter
    Meine Daten bearbeiten, kann ein Wunschpartner eingetragen werden, dieser
    Wunsch wir von uns nach Möglichkeiten berücksichtigt.<br>
</form>
