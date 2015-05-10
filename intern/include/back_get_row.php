<?php
 /**
  * Liest eine Zeile aus dem Wachplan aus.
  *
  * Dabei entspricht eine Zeile einem Wachtag und enthält die Benuter mit
  * Position und Name
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

//TODO Prüfen ob hier nicht eher Vor und Nachname besser wären?
$query = 'SELECT  `id` ,  `user_id` ,  `day_id` ,  `position` ,  `user_name`
		FROM  `wp_access_user_days`
		JOIN  `wp_days`
		JOIN  `wp_user` ON  `user_id` =  `id_user`
		AND  `day_id` =  `id_day` WHERE `day_id` =' .
 $_POST['day'] . ' ORDER by `position` ASC';
$reslut = $database->query($query);
$leute[] = array();
while ($user = mysqli_fetch_row($reslut)) {
    $leute[$user[3]] = $user;
}

for ($i = 1; $i <= 5; $i ++) {
    if (isset($leute[$i]) === FALSE || $leute[$i] === NULL) {
        $leute[$i] = '';
    } else {
        $leute[$i] = $leute[$i][4];
    }
}

?>
<div id=meldung>
    <form id=eintrag action="include/back_save_row.php" method="POST">
        <input type="hidden" value="<?php echo $_POST['day']; ?>" name="day">
        Wachleiter: <input type="text" name="wl"
            value="<?php echo $leute[1]; ?>"
        > stllv.Wachleiter: <input type="text" name="swl"
            value="<?php echo $leute[2]; ?>"
        ><br> 1. Wachgänger<input type="text" name="wg1"
            value="<?php echo $leute[3]; ?>"
        ><br> 2. Wachgänger<input type="text" name="wg2"
            value="<?php echo $leute[4]; ?>"
        ><br> 3. Wachgänger<input type="text" name="wg3"
            value="<?php echo $leute[5]; ?>"
        ><br> <input type="submit" id="submit" value="senden">
    </form>
</div>
