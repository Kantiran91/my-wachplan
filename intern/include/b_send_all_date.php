<?php
 /**
  * Schick an alle Benutzer die Termine wann sie Wachdienst haben.
  *
  * PHP versions 5
  *
  * LICENSE: This source file is subject CC BY 4.0 license
  *
  * @category CategoryName
  * @package  PackageName
  * @author  Sebastian Friedl <friedl.sebastian@web.de>
  * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
  * @version GIT: $Date$
  * @link    http:/salem.dlrg.de
  **/
require_once '../init.php';
check_session();

// Alle Wachdiensttage
$days = get_days();

// Alle Eintragungen
$queryAccsses = '
		SELECT `id`,
             `user_id`,
             `day_id`,
             `email`,
             `position`,
            `first_name`,
            `last_name`,
            `date`
		FROM `wp_access_user_days`
		JOIN `wp_days`
		JOIN `wp_user`
		ON `user_id` =`id_user` AND `day_id` =`id_day`
		Order by `user_id` ASC';
$stmt = $database->prepare($queryAccsses);
$stmt->bind_result(
    $result['accessID'],
    $result['user_id'],
    $result['day_id'],
    $result['email'],
    $result['position'],
    $result['first_name'],
    $result['last_name'],
    $result['date']);
$stmt->execute();
$users = array();
$oldID = 0;
while ($stmt->fetch()) {
    if ($result['user_id'] === $oldID) {
        $users[$result['user_id']]['dates'][] = $result['date'];
    } else {
        $oldID = $result['user_id'];
        $users[$result['user_id']]['dates'][] = $result['date'];
        $users[$result['user_id']]['first_name'] = $result['first_name'];
        $users[$result['user_id']]['last_name'] = $result['first_name'];
        $users[$result['user_id']]['email'] = $result['email'];
    }
}//end while

foreach ($users as $user) {
    $text = 'Hallo ' . $user['first_name'] . ' ' . $user['last_name'] . ",\n";
    $text .= "Danke das du diese Jahr wieder beim Wachdienst mit machst und du
dich im Wachplan eingetragen hast. Wir haben aus allen Wunschterminen einen möglichst
gerechten Wachplan erstellt. Dabei wurdest du für folgende Termine eingeteilt:\n\n";
    foreach ($user['dates'] as $day) {
        $text .= '- ' . date('d.m.Y', strtotime($day)) . "\n";
    }

    $text .= '-------------------------';
    $text .= "Solltest du an einem dieser Termine nicht können, dann melde dich bitte gleich bei uns.
damit wir diese Problem lösen können. Auch wenn es Problem während oder
mit dem Wachdienst gibt kannst du dich gerne an einen von uns wenden.\n
Viele Grüße und viel Spaß beim Wachdienst
Deine Wachdienstleitung";
    $mail = new Mail($user['email'], 'Einteilung Wachdienst');
    $mail->set_text($text);
    $mail->send_mail();
    echo $user['first_name'];
}//end foreach