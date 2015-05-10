<?php
 /**
  * Registierungsformular für die Benutzer für ein neues Jahr.
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
    echo date('d.m.Y', strtotime($day[1]));
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
    <br> <input class="button big" type="submit" value="senden"> <br> Unter
    Meine Daten bearbeiten, kann ein Wunschpartner eingetragen werden, dieser
    Wunsch wir von uns nach Möglichkeiten berücksichtigt.<br>
</form>
