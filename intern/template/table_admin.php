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
 * Template: Tabelle des Wachplans für die Administratoren
 *
 * Dieses File gibt die Wachplan Tabelle für die Administatoren aus.
 * Diese unterscheidet sich von der normalen Tabelle über folgende Funktioen:
 * - Teilnehmer können aus dem Wachplan ausgetragen werden
 * - Teilnehmer können in den Wachplan eingetragen werden
 *
 * @TODO    Umbennen des File und umbauen, so das nur noch diese genutzt wird.
 **/
require_once '../init.php';
checkSession();


/**
 * Erzeugt die HTML-Ausgabe eines Hinzufüge-Button in den Wachplan.
 *
 * @param integer $day Tag an dem ein Person hinzugefügt werden soll.
 * @param integer $pos Position an die eine Person eingetragen werden soll.
 *
 * @return void
 */
function genHTMLAddButton($day, $pos)
{
    if (checkRights('wachplanAdmin')) {
        echo "<a class=\"button_pic\" onclick=\"add_ac('" . $pos . "','" . $day .
                     "')\">";
        echo '<img src="../img/Edit_add.png" alt="" width="25"></a>' . "\n";
    } else if ($pos <= 2 && checkRights('wachplanAdmin')) {
        echo "<a class=\"button_pic\" onclick=\"add_self('" . $pos . "','" . $day .
                     "')\">";
        echo '<img src="../img/Edit_add.png" alt="" width="25"></a>' . "\n";
    } else if ($pos > 2) {
        echo "<a class=\"button_pic\" onclick=\"add_self('" . $pos . "','" . $day .
                     "')\">";
        echo '<img src="../img/Edit_add.png" alt="" width="25"></a>' . "\n";
    }

}//end genHTMLAddButton()

/**
 * Erzeugt die HTML-Ausgabe eines Entfernen-Button in den Wachplan.
 *
 * @param integer $id Die ID aus DB der für den eintrag im Wachplan steht.
 *
 * @return void
 */
function genHTMLDeleteButton($id)
{
    echo "<a class=\"button_pic\" onclick=\"delete_ac('" .$id ."')\">";
    echo '<img src="../img/Edit_remove.png" alt="" width="25"></a>' . "\n";

}//end genHTMLDeleteButton()

/*
 * Tabellen für darstellung holen.
 */

// Alle Wachdiensttage
$days = getDays();

// Alle Eintragungen
$queryAccsses = '
		SELECT `id`, `user_id`, `day_id`, `position` ,`first_name`,`last_name`,`date`
		FROM `wp_access_user_days`
		JOIN `wp_days`
		JOIN `wp_user`
		ON `user_id` =`id_user` AND `day_id` =`id_day`
        WHERE `day_id` = ?
		Order by `position` ASC';
$stmt = $database->prepare($queryAccsses);
$stmt->bind_param('i', $dayID);
$stmt->bind_result(
    $result['accessID'],
    $result['user_id'],
    $result['day_id'],
    $result['position'],
    $result['first_name'],
    $result['last_name'],
    $result['date']
);

$table = array();
foreach ($days as $day) {
    $dayID = $day[0];
    $stmt->execute();
    $user = array();
    while ($stmt->fetch()) {
        $tmp['first_name'] = $result['first_name'];
        $tmp['last_name'] = $result['last_name'];
        $tmp['accessID'] = $result['accessID'];
        $tmp['user_id'] = $result['user_id'];
        $user[$result['position']] = $tmp;
    }

    $table[$day[0]]['user'] = $user;
    $table[$day[0]]['date'] = date('d.m.Y', strtotime($day[1]));
    $table[$day[0]]['ID'] = $dayID;
}//end foreach

//FRONTEND
?>
<table class="plan">
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
foreach ($table as $day) {
    echo "<tr>\n";
    echo '<td>';
    echo $day['date'];
    echo "</td>\n";
    for ($i = 1; $i <= 5; $i ++) {
        $users = $day['user'];
        echo '<td>';
        if (isset($users[$i]) === TRUE) {
            echo $users[$i]['first_name'];
            echo ' ' . substr($users[$i]['last_name'], 0, 1) . '.<br>';
            if ((checkRights('wachplanAdmin')) === TRUE
                && checkPast($day['date']) === FALSE
            ) {
                genHTMLDeleteButton($users[$i]['accessID']);
            }
        } else if ((strtotime($day['date']) >= time()) === TRUE) {
            genHTMLAddButton($day['ID'], $i);
        }

        echo "</td>\n";
    }//end for

    echo "</tr>\n";
}//end foreach
?>
    </tbody>
</table>
