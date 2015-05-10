<?php
 /**
  * Startseite des internen Bereichs.
  *
  * Von hieraus hat man Zugriff aus das Menu. Und alle Funktionen je nach Rechten
  * die man braucht. Zusätzlich werden hier folgende Daten dargestellt:
  * - Meine eigenen Daten + Termine
  * - eine Liste von eintragbaren Terminen
  * - der gesamte Wachplan
  * - die Telefonliste
  *
  * PHP versions 5
  *
  * LICENSE: This source file is subject CC BY 4.0 license
  *
  * @author  Sebastian Friedl <friedl.sebastian@web.de>
  * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
  * @version GIT: $Date: Wed Apr 22 13:22:48 2015 +0200$
  * @link    http:/salem.dlrg.de
  **/
require_once '../init.php';
checkSession();
// Wenn Person hinzugefügt wird
// Holt alle Wachtage für den Benutzer
$queryOwn = '
SELECT  `id`,
        `user_name`,
        `date` ,
        `position`,
        `id_day`
FROM  `wp_access_user_days`
JOIN  `wp_user`
JOIN  `wp_days` ON  `wp_user`.`id_user` =  `wp_access_user_days`.`user_id`
AND  `wp_days`.`id_day` =  `wp_access_user_days`.`day_id`  WHERE `user_name` =';
$queryOwn .= '"' . $_SESSION['user_name'] . '" ORDER BY `date`';
$ergebnisOwn = $database->query($queryOwn);


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
        <a class="button big" href="change_data.php"> Meine Daten bearbeiten </a>
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
foreach ($ergebnisOwn as $zeile) {
    //@todo schaueb ob Frontend und Backend besser getrennt werden können.
    echo '<tr>';
    echo '<td>';
    echo date('d.m.Y', strtotime($zeile['date']));
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

    if (checkPast($zeile['date']) === FALSE) {
        echo '<td>';
        echo '<a class="button"  id="' . $zeile['date'] .
                     '" onclick="cancel_date(' . $zeile['id'] . ')">absagen</a>';
        echo '</td>';
    }
    if ((int) $zeile['position'] === 1 ||(int) $zeile['position'] === 2) {
        setButtonFeedback($zeile['id_day']);
    }

    echo "</tr>\n";
}//end foreach
?>
	 </tbody>
        </table>
    </div>
    <div class="modul" id="formular">
        <h1>Mögliche Waddchtage 2015</h1>
	<?php
require 'template/formular_year.php';
?>
	</div>
    <div class="modul" id="plan">
        <h1>Wachplan</h1>
	<?php //@todo Verschlanken da nur noch eine externe Datei notwendig ist.
if ($_SESSION['rights'] >= 2) {
    include 'template/table_admin.php';
} else {
    //TODO $wpEnable als Globale Constante definieren und nutzen!
    $wpEnable = TRUE;
    if ($wpEnable === TRUE) {
        include 'template/table_admin.php';
    } else {
        echo 'Der Wachplan ist noch nicht für alle verfügbar.
        Bitte fülle das Formular oben aus.<br> Sobald der Wachplan fertig ist,
        bekommst du eine Email mit deinen Terminen!<br>';
    }
}
?>
	</div>
    <div class="modul" id="tele_plan">
        <h1>Telefonliste</h1>
	<?php
require 'template/table_tele.php';
?>
	</div>
    <div class="modul" id="logout">
        <h1>
            <a href=logout.php>Logout</a>
        </h1>
    </div>
    <div id="foot"><?php echo VERSION; ?></div>
	<?php
if (isset($_GET['error']) === TRUE) {
    include __DIR__ . '/../include/errormeldung.php';
}
?>
</body>
</html>
