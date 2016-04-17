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
  * Startseite des internen Bereichs.
  *
  * Von hieraus hat man Zugriff aus das Menu. Und alle Funktionen je nach Rechten
  * die man braucht. Zusätzlich werden hier folgende Daten dargestellt:
  * - Meine eigenen Daten + Termine
  * - eine Liste von eintragbaren Terminen
  * - der gesamte Wachplan
  * - die Telefonliste
  **/
require_once '../init.php';
checkSession();




/**
 * Erzeugt einen Feedbackbutton.
 *
 * @param integer $day Tag für den ein Feedback erzeugt werden soll.
 *
 * @return void
 */
function setButtonFeedback($day)
{
    echo '<td>';
    echo '<a class="button" href="feedback_give.php?day=' . $day .
     '">Feedback</a>';
    echo '</td> ';

}//end setButtonFeedback()

createHeader('Startseite');


function selectDaysForUser($aUserId)
{
    $stmt = $GLOBALS['database']->prepare('
        SELECT
            `id`,
            `date` ,
            `position`,
            `id_day`
        FROM  `wp_access_user_days`
        JOIN  `wp_days` ON `wp_days`.`id_day` =  `wp_access_user_days`.`day_id`
        WHERE `wp_access_user_days`.`user_id` =? ORDER BY `date`');
    $stmt->bind_param('i', $aUserId);
    $row = array();
    $table = array();
    $stmt->bind_result(
        $row['id'],
        $row['date'],
        $row['position'],
        $row['id_day']
        );
    $stmt->execute();
    while ($stmt->fetch()) {
        $table[] = $row;
    }
    return $table;
}

/*
 * FRONTEND
 */
?>
<body>
	<?php require 'template/menu.php'; ?>
	<div class="modul" id="own_data">
        <h1>Meine persönlichen Daten</h1>
        <h2>Meine Daten</h2>
        <p>Name: <?php echo $_SESSION['user_name']; ?></p>
        <p>E-Mail: <?php echo $_SESSION['email']; ?> </p>
        <a class="button" id="buttonUserSettings" href="change_data.php"> Meine Daten bearbeiten </a>
        <h2>Meine Termine</h2>
        <table id="own_table">
            <thead>
                <tr>
                    <th>Datum</th>
                    <th>Position</th>
                </tr>
            </thead>
            <tbody>
	<?php
foreach (selectDaysForUser($_SESSION['id']) as $zeile) {
    //@TODO schaueb ob Frontend und Backend besser getrennt werden können.
    echo '<tr>';
    echo '<td>';
    echo dateDe($zeile['date']);
    echo '</td>';
    echo '<td>';
    if ((int) $zeile['position'] === 1) {
        echo 'Wachleiter ';
    } else if ((int) $zeile['position'] === 2) {
        echo 'stellv. Wachleiter ';
    } else {
        echo 'Wachgänger';
    }

    echo '</td> ';
    if (checkDateIsInPast($zeile['date']) === FALSE) {
        echo '<td>';
        echo '<a class="button"  id="' . $zeile['date'] .
                     '" onclick="cancel_date(' . $zeile['id'] . ')">absagen</a>';
        echo '</td>';
    }
    if ((int) $zeile['position'] === 1 || (int) $zeile['position'] === 2) {
        setButtonFeedback($zeile['id_day']);
    }

    echo "</tr>\n";
}//end foreach
?>
	 </tbody>
        </table>
    </div>
    <div class="modul" id="formular">
        <h1>Mögliche Wachtage 2016</h1>
	<?php require 'template/formular_year.php'; ?>
	</div>
    <div class="modul" id="plan">
        <h1>Wachplan</h1>
	<?php
    $wpEnable = TRUE;
    if ($wpEnable === TRUE) {
        include 'template/table_admin.php';
    } else {
        echo 'Der Wachplan ist noch nicht für alle verfügbar.
        Bitte fülle das Formular oben aus.<br> Sobald der Wachplan fertig ist,
        bekommst du eine Email mit deinen Terminen!<br>';
    }
?>
	</div>
    <div class="modul" id="tele_plan">
        <h1>Telefonliste</h1>
	<?php
require 'template/table_tele.php';
?>
	</div>
    <!-- <div class="modul" id="logout">
        <h1>
            <a href=logout.php>Logout</a>
        </h1>
    </div> -->
    <div id="foot"><?php echo VERSION; ?></div>
	<?php
if (isset($_GET['error']) === TRUE) {
    include __DIR__ . '/../include/errormeldung.php';
}
?>
</body>
</html>
