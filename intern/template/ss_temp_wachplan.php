<?php
/**
 * Erstellt den vorläufigen Wachplan
 *
 * Über einen Algorithmus wird geschaut wer Zeit hat und dabei am
 * besten geeigntet ist. Dabei spielen folgende Fähigkeiten/Eigenschaften eine
 * Rolle:
 * - Alter (wird spezielle Verrechnet)
 * - Rettungsschwimmerabzeichen
 * - Medzinsche Abzeichen
 *
 * Der Algorthmus versucht dabei zuerst alle Wachleiterposten dann alle stellv.
 * Wachleiter und dann die Wachgänger Position zu füllen
 *
 * PHP versions 5
 *
 * LICENSE: This source file is subject CC BY 4.0 license
 *
 * @author  Sebastian Friedl <friedl.sebastian@web.de>
 * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
 * @version GIT:  $Date: Thu May 7 14:43:07 2015 +0200$
 * @link    http:/salem.dlrg.de
 * @TODO    Die Auswahl erfolgt dynmaisch die Positionierung an dem Tag, sollte
 * dann aber statisch erfolgen.
 * @TODO    Der Wachleiter sollte immer 18 Jahre alt sein.
 * Die Prüfung noch ein binden.
 * @TODO    Die Anzahl der über diese Verfahren eingeteilten Wachtage
 * soll über eine Variable eingeteilt werden.
 * @TODO    Trenne von Frontend und Backend
 **/
require_once '../init.php';

checkSession();


/**
 * Holt aus dem Array die nächste Priorität raus.
 *
 * Wenn die Prio im Array existiert wird die nächst kleiner gesucht,
 * bis dies nicht mehr existiert.
 *
 * @param integer $prio  Priorität nach der im Array gesucht wird.
 * @param array   $array Verschachtelte Array, das durchsucht werden soll.
 *
 * @return integer Gibt die Prio aus wenn sie nicht im Array existiert.
 */
function getNextPrio($prio, array $array)
{
    if (array_key_exists((int) $prio, $array) === TRUE) {
        return getNextPrio(($prio - 1), $array);
    } else {
        return $prio;
    }

}//end getNextPrio()

// TODO In init.php ausgliedern.
date_default_timezone_set('Europe/Berlin');

/*
 * Tabellen für darstellung holen.
 * TODO Diese Anfrage schöner gestalten. Zum Beispiel durch ein Prepare und dann
 * über verschiedene Rechte holen.
 */

$days = getDays();

// Erstelle eine Liste von Wachleitern und eine Liste von Wahgängern
// Wachleiter holen
$queryWl = '
		SELECT
            `id_user`,
            `first_name`,
            `last_name`,
              `abzeichen`,
            `rights`,`med`,
            `geburtsdatum`
        FROM  `wp_user`
        WHERE `rights` >=1';
$stmtWl = $database->prepare($queryWl);
$stmtWl->execute();
// TODO schöner so das die Resultate als Array kommen.
$meta = $stmtWl->result_metadata();
while ($field = $meta->fetch_field()) {
    $params[] = &$row[$field->name];
}

call_user_func_array(
    array(
     $stmtWl,
     'bind_result',
    ),
    $params);
$wl = array();
while ($stmtWl->fetch()) {
    foreach ($row as $key => $val) {
        $tmp[$key] = $val;
    }

    $tmp['countDays'] = 0;
    $wl[$tmp['id_user']] = $tmp;
}

$stmtWl->close();

// Wachgänger holen
$queryWg = '
		SELECT
            `id_user`,
            `first_name`,
            `last_name` ,
              `abzeichen`,
            `rights`,`med`,
            `geburtsdatum`
        FROM  `wp_user`
        WHERE `rights` =0';
$stmtWg = $database->prepare($queryWg);
$stmtWg->execute();
// TODO schöner so das die Resultate als Array kommen.
$meta = $stmtWg->result_metadata();
$params = array();
while ($field = $meta->fetch_field()) {
    $params[] = &$row[$field->name];
}

call_user_func_array(
    array(
     $stmtWg,
     'bind_result',
    ),
    $params);
$wg = array();
while ($stmtWg->fetch()) {
    foreach ($row as $key => $val) {
        $tmp[$key] = $val;
    }

    $tmp['countDays'] = 0;
    $wg[$tmp['id_user']] = $tmp;
}

$stmtWg->close();

// Priobrechnen berechnung
$paramAbzeichen['DRSA Bronze'] = 1;
$paramAbzeichen['DRSA Silber'] = 3.5;
$paramAbzeichen['DRSA Gold'] = 3.7;
$paramMed['EH'] = 2.5;
$paramMed['san'] = 3.5;
// Berechnung des Alterswert (a-(b/X))=Y
$paramAlter['a'] = 80;
$paramAlter['b'] = 20;

foreach ($wl as &$person) {
    // brechnung des Alters einer Person
    $gb = new DateTime(date('Y-m-d', strtotime(($person['geburtsdatum']))));
    // Aktuelles Datum
    $today = new DateTime(date('Y-m-d'));
    $interval = $today->diff($gb);
    $monate = $interval->format('%m');
    $age = ($interval->format('%Y') + ($interval->format('%m') / 12));
    $age += 13;

    $prio = 0;
    // Berechnung der Start-Prio
    if (array_key_exists($person['abzeichen'], $paramAbzeichen) === TRUE) {
        $prio += $paramAbzeichen[$person['abzeichen']];
    }

    if (array_key_exists($person['med'], $paramAbzeichen) === TRUE) {
        $prio += $paramAbzeichen[$person['med']];
    }

    $prio += ($paramAlter['b'] - ($paramAlter['a'] / $age));
    $person['prio'] = (($prio * 10) + 60);
}//end foreach

foreach ($wg as &$person) {
    // brechnung des Alters einer Person
    $gbString = $person['geburtsdatum'];
    $gb = new DateTime(date('Y-m-d', strtotime($gbString)));
    // Aktuelles Datum
    $today = new DateTime(date('Y-m-d'));
    $interval = $today->diff($gb);
    $monate = $interval->format('%m');
    $age = ($interval->format('%Y') + ($interval->format('%m') / 12));
    $age += 13;
    $prio = 0;
    // Berechnung der Start-Prio
    if (array_key_exists($person['abzeichen'], $paramAbzeichen) === TRUE) {
        $prio += $paramAbzeichen[$person['abzeichen']];
    }

    if (array_key_exists($person['med'], $paramAbzeichen) === TRUE) {
        $prio += $paramAbzeichen[$person['med']];
    }

    $prio += ($paramAlter['b'] - ($paramAlter['a'] / $age));
    $person['prio'] = (($prio * 10) + 60);
}//end foreach

// Alle Eintragungen in den Wachplan
$queryAccsses = '
		SELECT `id`, `user_id`, `day_id`
		FROM `wp_poss_acc`
		JOIN `wp_days`
		JOIN `wp_user`
		ON `user_id` =`id_user` AND `day_id` =`id_day`
		WHERE `day_id` = ?';
$stmtAcc = $database->prepare($queryAccsses);
$stmtAcc->bind_param('i', $paramID);
// TODO schöner so das die Resultate als Array kommen.
$stmtAcc->bind_result($id, $userId, $dayId);
// TODO Dirty!! Das $daysContent sollte ersetzt werden.
$daysContent = array();
foreach ($days as $day) {
    $paramID = $day[0];
    $stmtAcc->execute();
    while ($stmtAcc->fetch()) {
        $day[] = $userId;
    }

    $daysContent[] = $day;
}

$tableContent = array();
foreach ($daysContent as $day) {
    // Extrahiere aus dem Day Array die Daten fürs Datum
    $dayId = $day[0];
    $tableContent[$dayId]['dayId'] = $day[0];
    $tableContent[$dayId]['date'] = $day[1];
    array_shift($day);
    array_shift($day);

    // Erstelle je ein Array mit Wachleitern und Wachgänger für den Termin
    $tempTableWl = array();
    $tempTableWg = array();
    while (! empty($day)) {
        if (isset($wl[$day[0]]) === TRUE) {
            $temp = $wl[$day[0]];
            $tempTableWl[] = $temp;
            array_shift($day);
        } else {
            $temp = $wg[$day[0]];
            $tempTableWg[] = $temp;
            array_shift($day);
        }
    }

    $tableContent[$dayId]['wlTemp'] = $tempTableWl;
    $tableContent[$dayId]['wgTemp'] = $tempTableWg;
}//end foreach

// Erstellen der Reihenfolge für die Wachleiter
foreach ($tableContent as &$row) {
    $dayId = $row['dayId'];
    $wlArray = array();
    foreach ($row['wlTemp'] as $user) {
        $userID = $user['id_user'];
        $prio = getNextPrio($wl[$userID]['prio'], $wlArray);
        $wlArray[$prio] = $user;
    }

    $first = TRUE;
    krsort($wlArray);
    $waitUser = array();
    foreach ($wlArray as $user) {
        $userID = $user['id_user'];
        $userData = $wl[$userID];
        if ($first === TRUE) {
            if ($userData['countDays'] < 6) {
                $wl[$userID]['prio'] -= 10;
                $wl[$userID]['countDays'] += 1;
                $first = FALSE;
            } else {
                $waitUser[] = array_shift($wlArray);
            }
        }
    }

    if (empty($waitUser) === FALSE) {
        array_push($wlArray, NULL);
        $wlArray = array_merge($wlArray, $waitUser);
    }

    $tableContent[$dayId]['wl'] = $wlArray;
}//end foreach

// Spalte für den stellv. Wachleiter füllen.
foreach ($tableContent as &$row) {
    $dayId = $row['dayId'];
    $tempArray = array();
    foreach ($row['wlTemp'] as &$user) {
        $userID = $user['id_user'];
        $prio = getNextPrio($wl[$userID]['prio'], $tempArray);
        $tempArray[$prio] = $user;
    }

    krsort($tempArray);
    $tempArray = array_filter($tempArray, 'is_array');
    $wlID = reset($row['wl'])['id_user'];
    $waitUser = array();
    $first = TRUE;
    foreach ($tempArray as $key => &$user) {
        if ($user === NULL) {
            break;
        }

        $userID = $user['id_user'];
        $userData = $wl[$userID];
        if ($wlID === $userID) {
            unset($tempArray[$key]);
            //// array_shift($tempArray);
        } else if ($first === TRUE) {
            if ($userData['countDays'] < 6) {
                $wl[$userID]['prio'] -= 10;
                $wl[$userID]['countDays'] += 1;
                $first = FALSE;
            } else {
                $waitUser[] = array_shift($tempArray);
            }
        } else if ($userData['countDays'] >= 6) {
            $waitUser[] = $tempArray[$key];
            unset($tempArray[$key]);
        }
    }//end foreach

    if (empty($waitUser) === FALSE) {
        array_push($tempArray, NULL);
        $tempArray = array_merge($tempArray, $waitUser);
    }

    $tableContent[$dayId]['wl2'] = array();
    $tableContent[$dayId]['wl2'] = $tempArray;
}//end foreach

// Übrige Wachleiter auf die Wachgänger übertragen
foreach ($tableContent as &$row) {
    $dayId = $row['dayId'];
    $tempArray = $row['wlTemp'];

    $wlID = reset($row['wl'])['id_user'];
    $wl2ID = reset($row['wl2'])['id_user'];
    foreach ($tempArray as $key => &$user) {
        if ($user === NULL) {
            break;
        }

        $userID = $user['id_user'];
        $userData = $wl[$userID];
        if ($wlID === $userID) {
            unset($tempArray[$key]);
        } else if (($wl2ID === $userID) === TRUE) {
            unset($tempArray[$key]);
        }

        $wg[$userID] = $userData;
    }

    $tableContent[$dayId]['wgTemp'] = array_merge(
        $tableContent[$dayId]['wgTemp'],
        $tempArray);
}//end foreach

// für den 1. Wachgänger
foreach ($tableContent as &$row) {
    $dayId = $row['dayId'];
    $tempArray = array();
    foreach ($row['wgTemp'] as &$user) {
        $userID = $user['id_user'];
        $prio = getNextPrio($wg[$userID]['prio'], $tempArray);
        $tempArray[$prio] = $user;
    }

    krsort($tempArray);
    $tempArray = array_filter($tempArray, 'is_array');
    $waitUser = array();
    $first = TRUE;
    foreach ($tempArray as $key => &$user) {
        if ($user === NULL) {
            break;
        }

        $userID = $user['id_user'];
        $userData = $wg[$userID];
        if ($first === TRUE) {
            if ($userData['countDays'] < 6) {
                $wg[$userID]['prio'] -= 10;
                $wg[$userID]['countDays'] += 1;
                $first = FALSE;
            } else {
                $waitUser[] = array_shift($tempArray);
            }
        } else if (($userData['countDays'] >= 6) === TRUE) {
            $waitUser[] = $tempArray[$key];
            unset($tempArray[$key]);
        }
    }//end foreach

    if (empty($waitUser) === FALSE) {
        array_push($tempArray, NULL);
        $tempArray = array_merge($tempArray, $waitUser);
    }

    $tableContent[$dayId]['wg1'] = array();
    $tableContent[$dayId]['wg1'] = $tempArray;
}//end foreach

// 2. Wachgänger
foreach ($tableContent as &$row) {
    $dayId = $row['dayId'];
    $tempArray = array();
    foreach ($row['wgTemp'] as &$user) {
        $userID = $user['id_user'];
        $prio = getNextPrio($wg[$userID]['prio'], $tempArray);
        $tempArray[$prio] = $user;
    }

    krsort($tempArray);
    $tempArray = array_filter($tempArray, 'is_array');
    $wg1ID = reset($row['wg1'])['id_user'];
    $waitUser = array();
    $first = TRUE;
    foreach ($tempArray as $key => &$user) {
        if ($user === NULL) {
            break;
        }

        $userID = $user['id_user'];
        $userData = $wg[$userID];
        if ($wg1ID === $userID) {
            unset($tempArray[$key]);
        } else if ($first === TRUE) {
            if ($userData['countDays'] < 6) {
                $wg[$userID]['prio'] -= 10;
                $wg[$userID]['countDays'] += 1;
                $first = FALSE;
            } else {
                $waitUser[] = array_shift($tempArray);
            }
        } else if ($userData['countDays'] >= 6) {
            $waitUser[] = $tempArray[$key];
            unset($tempArray[$key]);
        }
    }//end foreach

    if (empty($waitUser) === FALSE) {
        array_push($tempArray, NULL);
        $tempArray = array_merge($tempArray, $waitUser);
    }

    $tableContent[$dayId]['wg2'] = array();
    $tableContent[$dayId]['wg2'] = $tempArray;
}//end foreach

// 3. Wachgänger
foreach ($tableContent as &$row) {
    $dayId = $row['dayId'];
    $tempArray = array();
    foreach ($row['wgTemp'] as &$user) {
        $userID = $user['id_user'];
        $prio = getNextPrio($wg[$userID]['prio'], $tempArray);
        $tempArray[$prio] = $user;
    }

    krsort($tempArray);
    $tempArray = array_filter($tempArray, 'is_array');
    $wg1ID = reset($row['wg1'])['id_user'];
    $wg2ID = reset($row['wg2'])['id_user'];
    $waitUser = array();
    $first = TRUE;
    foreach ($tempArray as $key => &$user) {
        if ($user === NULL) {
            break;
        }

        $userID = $user['id_user'];
        $userData = $wg[$userID];
        if ($wg1ID === $userID) {
            unset($tempArray[$key]);
        } else if ($wg2ID === $userID) {
            unset($tempArray[$key]);
        } else if ($first === TRUE) {
            if ($userData['countDays'] < 6) {
                $wg[$userID]['prio'] -= 10;
                $wg[$userID]['countDays'] += 1;
                $first = FALSE;
            } else {
                $waitUser[] = array_shift($tempArray);
            }
        } else if ($userData['countDays'] >= 6) {
            $waitUser[] = $tempArray[$key];
            unset($tempArray[$key]);
        }
    }//end foreach

    if (empty($waitUser) === FALSE) {
        array_push($tempArray, NULL);
        $tempArray = array_merge($tempArray, $waitUser);
    }

    $tableContent[$dayId]['wg3'] = array();
    $tableContent[$dayId]['wg3'] = $tempArray;
}//end foreach

// FRONTEND
?>
<form id="wp_save" action="include/b_ss_save_wp.php" method="POST">
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
foreach ($tableContent as &$row) {
    ?>
    <!-- Wachleiter -->
            <tr>
                <td><?php echo $row['date']; ?></td>
                <td><select name="<?php echo $row['dayId']; ?>[wl]">
    <?php
    foreach ($row['wl'] as $person) {
        echo '<option value=';
        if ($person !== NULL) {
            echo $person['id_user'];
        }

        echo '>';
        if ($person !== NULL) {
            echo $person['id_user'] . ' : ' . $person['first_name'] . ' ' .
                         $person['last_name'];
        }

        echo '</option>';
    }
    ?>
			 <option></option>
                </select></td>
                <!-- stellv. Wachleiter -->
                <td><select name="<?php echo $row['dayId']; ?>[wl2]">
	<?php
    foreach ($row['wl2'] as $person) {
        echo '<option value=';
        if ($person !== NULL) {
            echo $person['id_user'];
        }

        echo '>';
        if ($person !== NULL) {
            echo $person['id_user'] . ': ' . $person['first_name'] . ' ' .
                         $person['last_name'];
        }

        echo '</option>';
    }
    ?>
			 <option></option>
                </select></td>
                <!-- 1. Wachgänger -->
                <td><select name="<?php echo $row['dayId']; ?>[wg1]">
	<?php
    foreach ($row['wg1'] as $person) {
        echo '<option value=';
        if ($person !== NULL) {
            echo $person['id_user'];
        }

        echo ' >';
        if ($person !== NULL) {
            echo $person['id_user'] . ': ' . $person['first_name'] . ' ' .
                         $person['last_name'];
        }

        echo '</option>';
    }
    ?>
			 <option></option>
                </select></td>
                <!-- 2. Wachgänger -->
                <td><select name="<?php echo $row['dayId']; ?>[wg2]">
			<?php
    foreach ($row['wg2'] as $person) {
        echo '<option value=';
        if ($person !== NULL) {
            echo $person['id_user'];
        }

        echo '>';
        if ($person !== NULL) {
            echo $person['id_user'] . ': ' . $person['first_name'] . ' ' .
                         $person['last_name'];
        }

        echo '</option>';
    }
    ?>
						<option></option>
                </select></td>
                <!-- 3. Wachgänger -->
                <td><select name="<?php echo $row['dayId']; ?>[wg3]">
	<?php
    foreach ($row['wg3'] as $person) {
        echo '<optionvalue=';
        if ($person !== NULL) {
            echo $person['id_user'];
        }

        echo '>';
        if ($person !== NULL) {
            echo $person['id_user'] . ':' . $person['first_name'] . ' ' .
                         $person['last_name'];
        }

        echo '</option>';
    }
    ?>
			 <option></option>
                </select></td>
            </tr>
            <!-- Ende Zeile -->

   <?php
}//end foreach
?>
	<!-- Ende Tabelle -->
        </tbody>
    </table>
    <input class="button big" type="submit">
</form>
