<?php
/**
 * Template: Tabelle des Wachplans für die Wachgänger
 *
 * Dieses File unterscheidet sich von der Tabelle der Administartoren
 * - TN können nur sich selbst austragen
 * - TN können sich als selbst eintragen.
 *
 * PHP versions 5
 *
 * LICENSE: This source file is subject CC BY 4.0 license
 *
 * @author  Sebastian Friedl <friedl.sebastian@web.de>
 * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
 * @version GIT: $Date: Wed Apr 22 13:22:48 2015 +0200$
 * @link    http:/salem.dlrg.de
 * @see     table_admin.php Dieses File ist veraltet und sollte nicht mehr genuzt werden.
 * @todo    Dieses File löschen.
 **/
require_once '../init.php';
checkSession();

/*
 * Tabellen für darstellung holen.
 */

$days = getDays();

// Alle Eintragungen
$queryAccsses = '
		SELECT `id`, `user_id`, `day_id`, `position` ,`first_name`,`last_name`,`date`
		FROM `wp_access_user_days`
		JOIN `wp_days`
		JOIN `wp_user`
		ON `user_id` =`id_user` AND `day_id` =`id_day`
		Order by `date` ASC';
$reslutAccsses = $database->query($queryAccsses);


/**
 * Fügt einen Hinzufüge Button an der Stelle ein.
 *
 * Dieser Button löst die JavaSkript Funktion add_self aus.
 *
 * @param number $i   Position an der sich die Wachgänger einträgt.
 * @param number $day Tag an dem der Wachgänger Wachdienst machen will.
 *
 * @return void
 */
function setAddButton(number $i, number $day)
{
    ?>
    <a class="button_pic" onclick="add_self('<?php echo $i . ',' . $day[0]; ?>')">
        <img src="../img/Edit_add.png" alt="" width="25">
    </a>
    <?php
    NULL;

}//end setAddButton()

// FRONTEND
?>
<table>
    <thead>
        <tr>
            <th>Datum</th>
            <th>Wachleiter</th>
            <th>stellv. Wachleiter</th>
            <th>1.Wachgänger</th>
            <th>2.Wachgänger</th>
            <th>3.Wachgänger</th>
        </tr>
    </thead>
    <tbody>
		<?php
$eintrag = mysqli_fetch_row($reslutAccsses);
foreach ($days as $day) {
    echo '<tr>';
    echo '<td>';
    echo date('d.m.Y', strtotime($day[1]));
    echo '</td>';
    // Suche alle Personen für diese Tag raus
    $user = array();
    while ($day[0] === $eintrag[2]) {
        $user[$eintrag[3]] = $eintrag;
        $eintrag = mysqli_fetch_row($reslutAccsses);
    }

    for ($i = 1; $i <= 5; $i ++) {
        echo '<td>';
        if ((($user === NULL) && (isset($user[$i])) === TRUE) === FALSE) {
            echo $user[$i][4];
            echo ' ' . substr($user[$i][5], 0, 1) . '.';
        } else if ($i < 3 && $_SESSION['rights'] < 1) {
            NULL;
            // @todo soll hier wirklich nichts gemacht werden?
        } else {
            // @todo ggf. durch checkPast ersetzten.
            if (strtotime($day[1]) >= time()) {
                setAddButton($i, $day);
            }
        }

        echo '</td>';
    }

    echo '</tr>\n';
}//end foreach
?>
		</tbody>
</table>
