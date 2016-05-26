<?php /**
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
 * Erstellt den vorläufigen Wachplan
 *
 * Über einen Algorithmus wird geschaut wer Zeit hat und dabei am
 * besten geeigntet ist. Dabei spielen folgende Fähigkeiten/Eigenschaften eine
 * Rolle:
 * - Alter (wird spezielle Verrechnet)
 * - Rettungsschwimmerabzeichen
 * - Medzinsche Abzeichen
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


$days = getDays();

// Erstelle eine Liste von Wachleitern und eine Liste von Wahgängern
// Wachleiter holen
$wl = getWgFromDbWhere('`rights` > 0');

$wg = getWgFromDbWhere('`rights` = 0');

foreach ($wl as &$person) {
    $person['prio'] = calculatePrioForMember($person);
}//end foreach

foreach ($wg as &$person) {
    $person['prio'] = calculatePrioForMember($person);
}//end foreach

$daysContent = getPossAccessFromDb($days);

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


function getWGFromDbWhere($statment)
{
    $stmtWl = $GLOBALS['database']->prepare( '
		SELECT
            `id_user`,
            `first_name`,
            `last_name`,
              `abzeichen`,
            `rights`,`med`,
            `geburtsdatum`
        FROM  `wp_user`
        WHERE'. $statment);
    $stmtWl->execute();
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
    return $wl;
}

function getAge($birthday)
{
    $gb = new DateTime(date('Y-m-d', strtotime($birthday)));
    $today = new DateTime(date('Y-m-d'));
    return $today->diff($gb);
}

function getPossAccessFromDb($days){
    $stmt = $GLOBALS['database']->prepare('
		SELECT `user_id`
		FROM `wp_poss_acc`
		JOIN `wp_days`
		JOIN `wp_user`
		ON `user_id` =`id_user` AND `day_id` =`id_day`
		WHERE `day_id` = ?');
    $stmt->bind_param('i', $dayId);
    $stmt->bind_result($userId);
    $returnArray = array();
    foreach ($days as $day) {
        $dayId = $day[0];
        $stmt->execute();
        while ($stmt->fetch()) {
            $day[] = $userId;
        }

        $returnArray[] = $day;
    }
    $stmt->close();
    return $returnArray;
}

function calculatePrioForMember($person)
{
    // Priobrechnen berechnung
    $paramAbzeichen['DRSA Bronze'] = 1;
    $paramAbzeichen['DRSA Silber'] = 3.5;
    $paramAbzeichen['DRSA Gold'] = 3.7;
    $paramMed['EH'] = 2.5;
    $paramMed['san'] = 3.5;
    // Berechnung des Alterswert (a-(b/X))=Y
    $paramAlter['a'] = 80;
    $paramAlter['b'] = 20;

    $interval =  getAge($person['geburtsdatum']);

    $monate = $interval->format('%m');
    $age = ($interval->format('%Y') + ($interval->format('%m') / 12));
    $age += 13;

    $prio = 0;
    if (array_key_exists($person['abzeichen'], $paramAbzeichen) === TRUE) {
        $prio += $paramAbzeichen[$person['abzeichen']];
    }

    if (array_key_exists($person['med'], $paramAbzeichen) === TRUE) {
        $prio += $paramAbzeichen[$person['med']];
    }

    $prio += ($paramAlter['b'] - ($paramAlter['a'] / $age));
    return (($prio * 10) + 60);
}


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
