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
  * Schick an alle Benutzer die Termine wann sie Wachdienst haben.
  **/
require_once '../../init.php';
checkSession();

// Alle Wachdiensttage
$days = getDays();

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
        $users[$result['user_id']]['last_name'] = $result['last_name'];
        $users[$result['user_id']]['email'] = $result['email'];
    }
}//end while

echo '<h1>' .count($users) . '</h1>';
foreach ($users as $user) {

    $daysAsString = '';
    foreach ($user['dates'] as $day) {
        $daysAsString .= '- ' . dateDe($day) . "\n";
    }

    $keywords = array(
        'firstName' => $user['first_name'],
        'lastName' => $user['last_name'],
        'days'   => $daysAsString,
     );

     $mail = new Mail($user['email']);
     $mail->loadTemplate('sendDates', $keywords);
     $mail->sendMail();

     echo '<h3>' . $user['first_name'] . "</h3>\n";
}//end foreach

