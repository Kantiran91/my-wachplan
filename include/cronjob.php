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
 * Regelmässige Wartungsarbeiten werden hierrüber erledigt
 *
 * Folgende Regelmässige Aktionen werden duchgeführt:
 * - eMail-Benachrichtigung über Wachdienst!
 * - eMail-Benachrichtigung über Feedback!
 * - eMail-Benachrichtigung über Loggins
 * Das Skript sollte einmal am Tag ausgeführt werden.
 **/
require_once '../init.php';


if (isset($_GET['key']) === TRUE && get('key') === 'c8f207d9') {
    // Mail für Admin vorbereiten.
    $mailAdmin = new mail('sebastian.friedl@salem.dlrg.de');

    // Akutelle Zeit auslesen.
    $timestampAkutell = time();
    $wochentag = date('w', $timestampAkutell);
    $datum = date('Y-m-d', $timestampAkutell);
    $timeZone = new DateTimeZone('Europe/Berlin');
    $datumNext = new DateTime($datum, $timeZone);
    $datumNext->modify('+7 day');
    $datumOld = new DateTime($datum, $timeZone);
    $datumOld->modify('-7 day');

    /*
     * Reminder Email
     * Immer Montags die Wachgänger für die nächste Woche informieren
     * und die Wachleiter vom vergangenen Wochenende eine Erinnerung an das
     *
     * Feedback senden.
     */

    if ($wochentag === '1' || get('debug') == "true") {
        // Hole die Tage an denen Wachdienst gemacht wird.
        $datumNextFormat = $datumNext->format('Y-m-d');
        $queryDays = "SELECT `id_day`, `date`
				FROM `wp_days`
				WHERE `date`BETWEEN '" . $datum .
         "' AND ' " . $datumNextFormat . "'";
        $resultDays = $database->query($queryDays);
        while ($row = $resultDays->fetch_row()) {
            $days[] = $row;
        }//end while

        foreach ($days as $day) {
            $resultAcc = $database->query(
                "SELECT `id`,
                `wp_user`.
                `email`,
                `wp_user`.
                `user_name` ,
                 `position`,
                 `wp_user`.
                `telephone` ,
                `date`
			FROM `wp_access_user_days`
			JOIN `wp_user` ON `wp_user`.`id_user`=`wp_access_user_days`.`user_id`
			JOIN `wp_days` ON `wp_days`.`id_day`=`wp_access_user_days`.`day_id`
			WHERE `day_id`='" . $day[0] . "'"
            );
            while ($users[] = $resultAcc->fetch_row()) {
                NULL;
            }

            array_pop($users);
            foreach ($users as $user) {
                $mailKeywords = array(
                                 'tag'     => dateDe($day[1]),
                                 'vorname' => $user[2],
                                );
                $mail = new mail($user[1]);
                if ($user[3] === 1 || $user[3] === 2) {
                    $tnListe = '';
                    foreach ($users as $teilnehmer) {
                        $tnListe .= $teilnehmer[2] . "\t";
                        $tnListe .= $teilnehmer[1] . "\t";
                        $tnListe .= $teilnehmer[4] . "\n";
                    }//end foreach

                    $mailKeywords['teilnehmer']  = $tnListe;
                    $mail->loadTemplate('wlCron', $mailKeywords);
                } else {
                    $mail->loadTemplate('wgCron', $mailKeywords);
                }//end if

                $mail->sendMail();
            }//end foreach

            $users = NULL;
        }//end foreach

        /*
         * Feedback Email
         */

        $queryOldWl = "SELECT `id_day`,
         `date`,
         `user_id` ,
        `position`,
        `email`,
        `user_name`
		FROM `wp_days`
		JOIN `wp_access_user_days` ON `day_id`=`id_day`
	    JOIN `wp_user` ON `user_id` = `id_user`
		WHERE `position` < 3  AND`date`BETWEEN ' " .
         $datumOld->format('Y-m-d') . " ' AND ' " . $datum . "'";
        $resultOldWl = $database->query($queryOldWl);
        while ($row = $resultOldWl->fetch_row()) {
            $feedbackMail = new mail($row[4]);
            $feedKeywords = array(
                             'tag'     => dateDe($row[1]),
                             'vorname' => $row[5],
                             'dayId'   => $row[0],
                            );
            $feedbackMail->loadTemplate('feedbackCron', $feedKeywords);
            $feedbackMail->sendMail();
        }//end while
    }//end if

    /*
     * Logins über Wachen
     */

    $queryLogin =
    'SELECT
    count(`wp_log_login`.`username`) AS `anzahl`,
    `wp_log_login`.`username` AS `username`,
    `wp_log_login`.`datum` AS `datum` ,
    `pw_korrekt`
	FROM `wp_log_login`
	WHERE 1 group by `wp_log_login`.`username` ,`pw_korrekt`
	order by `wp_log_login`.`datum` desc limit 0,10;';
    $resultLogin = $database->query($queryLogin);
    $login = array();
    while ($row = $resultLogin->fetch_row()) {
        $login[] = $row;
    }

    $loginTrysString = '';
    foreach ($login as $loginVersuch) {
        $loginTrysString .= $loginVersuch[0] . "\t";
        $loginTrysString .= $loginVersuch[1] . "\t";
        $loginTrysString .= $loginVersuch[2] . "\t";
        $loginTrysString .= $loginVersuch[3] . "\n";
    }//end foreach

    $keywords = array('loginversuche' => $loginTrysString);
    $mailAdmin->loadTemplate('adminCron', $keywords);
    $mailAdmin->sendMail();
}//end if
