<?php
/**
 * Regelmässige Wartungsarbeiten werden hierrüber erledigt
 *
 * Folgende Regelmässige Aktionen werden duchgeführt:
 * - eMail-Benachrichtigung über Wachdienst!
 * - eMail-Benachrichtigung über Feedback!
 * - eMail-Benachrichtigung über Loggins
 * Das Skript sollte einmal am Tag ausgeführt werden.
 *
 * PHP versions 5
 *
 * LICENSE: This source file is subject CC BY 4.0 license
 *
 * @author  Sebastian Friedl <friedl.sebastian@web.de>
 * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
 * @version GIT: $Date: Thu May 7 14:43:07 2015 +0200$
 * @link    http:/salem.dlrg.de
 **/
require_once '../init.php';


/**
 * Gibt das Datum als Formatierten String zurück.
 *
 * @param string $date String mit Datum.
 *
 * @return string Datum in der Darstellung d.m.Y
 * @TODO   Überführen in die Init datei. ggf. auch eine funktion für sql Datum
 */
function dateDe($date)
{
    return date('d.m.Y', strtotime($date));

}//end dateDe()

if (isset($_GET['key']) === TRUE && $_GET['key'] === 'c8f207d9') {
    // Mail für Admin vorbereiten.
    $mailAdmin = new mail('sebastian.friedl@salem.dlrg.de', 'Cronjob');
    $mailAdminText = "Hallo Sebastian, die Daten von heute:\n";
    $mailAdminText .= "-------------------------------------\n";

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

    if ($wochentag === '1') {
        // Hole die Tage an denen Wachdienst gemacht wird.
        $datumNextFormat = $datumNext->format('Y-m-d');
        $queryDays = "SELECT `id_day`, `date`
				FROM `wp_days`
				WHERE `date`BETWEEN '" . $datum .
         "' AND ' " . $datumNextFormat . "'";
        $resultDays = $database->query($queryDays);
        while ($row = $resultDays->fetch_row()) {
            $days[] = $row;
        }

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
            $betreffUser = 'Wachdienst am ' . dateDe($day[1]);
            foreach ($users as $user) {
                $mail = new mail($user[1], $betreffUser);
                $mailUserText = 'Hallo ' . $user[2] . ",\n";
                if ($user[3] === 1 || $user[3] === 2) {
                    //// $leader[] = $user[3];
                    $mailUserText .= 'du bist am ' .
                     date('d.m.Y', strtotime($day[1]));
                    $mailUserText .= "für den Wachdienst als Wachleiter bzw.
                     stellv. Wachleiter eingeleit.\n";
                    $mailUserText .= "Deine Wachgänger sind:\n";
                    foreach ($users as $teilnehmer) {
                        $mailUserText .= $teilnehmer[2] . "\t";
                        $mailUserText .= $teilnehmer[1] . "\t";
                        $mailUserText .= $teilnehmer[4] . "\n";
                    }

                    $mailUserText .= "Bitte überprüfe ob Sie alle kommen können.
                     Sollte es ein Probleme geben,
                     melde dich bitte bei einem von uns. \n";
                } else {
                    $mailUserText .= 'du bist am ' . dateDe($day[1]) .
                     "für den Wachdienst eingeleit.\n";
                    $mailUserText .= "Ihr trefft euch um 11:45 am San-Raum \n";
                    $mailUserText .= "Solltet du kurzfristig nicht können oder
                     krank werden, bitten wir dich deinem Wachleiter, oder
                     falls dieser nicht erreichbar ist uns technischen Leitern
                     so schnell wie möglich Bescheid zugeben\n und / oder zu
                    Versuchen jemand andern zu finden.Eine mögliche Liste von
                     Wachgängern findest du im internen Bereich.\n\n
                    Für den Wachdienst benötigst du noch folgende Sachen:\n";
                    $mailUserText .= "Schwimmbekleidung\n";
                    $mailUserText .= "dein DLRG T-Shirt (rot/gelb)\n";
                    $mailUserText .= "ggf. Taucherbrille, Schnorchel,
                     Flossen(rot/gelb)\n";
                    $mailUserText .= "ggf. Sonnenschutz wie Hut oder
                     Sonnencreme\n (Falls du kein T-Shirt haben, liegen
                     am Schlosssee welche zum Ausleihen bereit.)\n\n";
                }//end if

                $mailUserText .= "------------------------------------------\n";
                $mailUserText .= "Viel Glück und Spaß beim Wachdienst \n
                Deine Technische Leitung\n Daniel Schmid \t 0176/82148811\n
                Sebastian Friedl \t 0151/25255314";
                $mail->setText($mailUserText);
                $mail->sendMail();
                echo '<br>\n\n';
            }//end foreach

            $users = NULL;
            echo "-------------------------------------\n";
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
            $mailWl = new mail($row[4], 'Feedback für den :' . dateDe($row[1]));
            $wlText = 'Hallo' . $row[5] .
             ",\n danke für den Wachdienst am
            vergangenen Wochenende!\n Um den Wachdienst für euch noch besser
             und einfacher zu gestalten, ist uns deine Meinung wichtig.\n
             Bitte nimm füll doch kurz den Feedbackbogen auf folgender Seite aus:\n
             seepelikan.bplaced.net/wachplan/intern/feedback_wl.php?day=" .
             $row[0];
            $wlText .= "\n\n Danke für deine Hilfe \n Deine Technische Leitung";
            $mailWl->sendMail($wlText);
        }

        echo "-------------------------------------\n";
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

    $mailAdminText .= "Login Versuche:\n";
    foreach ($login as $loginVersuch) {
        $mailAdminText .= $loginVersuch[0] . "\t";
        $mailAdminText .= $loginVersuch[1] . "\t";
        $mailAdminText .= $loginVersuch[2] . "\t";
        $mailAdminText .= $loginVersuch[3] . "\n";
    }

    $mailAdminText .= "-------------------------------------\n";
    $mailAdmin->setText($mailAdminText);
    $mailAdmin->sendMail();
}//end if
