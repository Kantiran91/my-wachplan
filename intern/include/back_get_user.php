<?php
 /**
  * Gibt alle Benutzer als Formular aus.
  *
  * Mit diesem Formular kann eine Person ausgewählt werden.
  *
  * PHP versions 5
  *
  * LICENSE: This source file is subject CC BY 4.0 license
  *
  * @author  Sebastian Friedl <friedl.sebastian@web.de>
  * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
  * @version GIT:  $Date: Sun May 10 09:51:12 2015 +0200$
  * @link    http:/salem.dlrg.de
  * @todo    Die Ausgabe sollte verallgemeinert werden. Das heißt die Benutzer
  * sollen im JSON-Formant ausgegeben werden
  * @todo    Trenne des Frontend vom Backend
  **/
require_once '../../init.php';
checkSession();

//@todo ggf. dieser Codeteil der alle Benutzer ausgibt in eine eigene Funktion.
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
		<input type="hidden" value="TRUE" name="eingabe"> <input type="hidden"
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
		</select> <input class="button" type="submit" id="submit" value="senden"><br>
		<br> <a class="button" onclick="hide_massage()">schließen</a>
	</form>

</div>
