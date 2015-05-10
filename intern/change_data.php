<?php
 /**
  * In diesem File kann der Benutzer seine Daten ändern.
  *
  * PHP versions 5
  *
  * LICENSE: This source file is subject CC BY 4.0 license
  *
  * @author  Sebastian Friedl <friedl.sebastian@web.de>
  * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
  * @version GIT:  $Date: Sun May 10 09:51:12 2015 +0200$
  * @link    http:/salem.dlrg.de
  * @todo    Frontend und Backend Trennen
  **/
require_once '../init.php';
checkSession();

/*
 * Benutzerdaten ändern
 */

if (isset($_GET['user_name']) === TRUE) {
    $_SESSION['user_name'] = $_GET['user_name'];
    $_SESSION['email'] = $_GET['email'];
    $stmtChange = $database->prepare(
        'UPDATE `wp_user`
         SET `friend`=?,
             `email`=?,
             `user_name`=?,
             `telephone`=?,
             `geburtsdatum`=?,
             `abzeichen`=?,
             `med`=?,
             `first_name`=?,
             `last_name`=?
        WHERE `id_user`=' . $_SESSION['id']);
    $stmtChange->bind_param(
        'issssssss',
        $_GET['friend'],
        $_GET['email'],
        $_GET['user_name'],
        $_GET['tele'],
        $_GET['gb'],
        $_GET['abzeichen'],
        $_GET['med'],
        $_GET['first_name'],
        $_GET['last_name']);
    if ($stmtChange->execute() === FALSE) {
        echo '<div class=meldung>Fehler beim Eintragen.<br>
        <a class="button" onclick="hide_massage()" >schließen</a></div>';
    }
}//end if

/*
 *  Hole die Liste von Wunschpartnern
 */

//TODO in eine Funktion ausgliedern
$query = 'SELECT `id_user`, `user_name` FROM `wp_user` ORDER BY `user_name`';
$result = $database->query($query);
while ($users[] = mysqli_fetch_row($result)) {
    NULL;
}

array_pop($users);

/*
 * Passwort ändern
 */

$pwSame = TRUE;
if (isset($_POST['pass1']) === TRUE && isset($_POST['pass2']) === TRUE) {
    if ($_POST['pass1'] === $_POST['pass2']) {
        $pwSame = TRUE;
        // TODO Query durch prepare ersetzten
        $queryPw = 'UPDATE `wp_user` SET `hash`="' .
                     encrypt_hash($_POST['pass1']) . '" WHERE `user_name`=';
        $queryPw .= '"' . $_SESSION['user_name'] . '"';
        $result = $database->query($queryPw);
        // TODO Komisch wieso mach ich das und brauch ich das?
        if ($result === TRUE) {
            $pwCorrekt = TRUE;
        } else {
            $pwCorrekt = FALSE;
        }
    } else {
        $pwSame = FALSE;
    }//end if
}//end if

// Holt alle Daten des Benutzers
//TODO SQL befehl bearbeiten
$queryOwn = '
SELECT
    `user_name`,
    `email` ,
    `telephone`,
    `geburtsdatum`,
    `abzeichen`,
    `med`,
    `first_name`,
    `last_name`,
    `friend`
FROM  `wp_user`
WHERE `user_name` ="' . $_SESSION['user_name'] . '"';
$resultOwn = $database->query($queryOwn);
$row = $resultOwn->fetch_row();
// FRONTEND
createHeader('Eigene Daten');
//FIXME Die eingabe der Abzeichen sollte nicht mehr manuelle Erfolgen!
?>
<body>
	<?php require 'template/menu.php'; ?>
	<div class="modul" id="own_data">
        <h1>Meine persönlichen Daten</h1>
        <form class="formular" action="change_data.php" method="get">
            <table>
                <tr>
                    <td><label for="username">Benutzername</label></td>
                    <td><input type=text name=user_name
                        value="<?php echo $row[0]; ?>"
                    ></td>
                </tr>
                <tr>
                    <td><label for="first_name">Vorname</label></td>
                    <td><input type=text name=first_name
                        value="<?php echo $row[6]; ?>"
                    ></td>
                </tr>
                <tr>
                    <td><label for="last_name">Nachname</label></td>
                    <td><input type=text name=last_name
                        value="<?php echo $row[7]; ?>"
                    ></td>
                </tr>
                <tr>
                    <td>E-Mail:</td>
                    <td><input type=text name=email
                        value="<?php echo $row[1]; ?>"
                    ></td>
                </tr>
                <tr>
                    <td>Telefonnummer:</td>
                    <td><input type=text name=tele
                        value="<?php echo $row[2]; ?>"
                    ></td>
                </tr>
                <tr>
                    <td>Geburtsdatum:</td>
                    <td><input type=text name=gb value="<?php echo $row[3]; ?>"></td>
                </tr>
                <tr>
                    <td>Abzeichen:</td>
                    <td><input type=text name=abzeichen
                        value="<?php echo $row[4]; ?>"
                    ></td>
                </tr>
                <tr>
                    <td>Erste-Hilfe-Ausbildung:</td>
                    <td><input type=text name=med value="<?php echo $row[5]; ?>"></td>
                </tr>
                <tr>
                    <td>Wunschpartner</td>
                    <td><select name="friend">
                            <option value="NULL"></option>
		<?php
foreach ($users as $user) {
    if ($user[0] === $row[8]) {
        echo '<option value="';
        echo $user[0] . '"selected >';
    } else {
        echo '<option value="';
        echo $user[0] . '">';
    }

    echo $user[1];
    echo '</option><br/>';
}
?>
		</select></td>
                </tr>
                <tfoot>
                    <tr>
                        <td colspan="2"><input class="button" type="submit"
                            value="senden"
                        ></td>
                    </tr>
                </tfoot>
            </table>
        </form>
    </div>
    <div class="modul" id="own_data">
        <h1>Passwort neu setzen</h1>
        <form class="formular" action="change_data.php" method="post">
            <table>
                <tr>
                    <td>Neues Passwort:</td>
                    <td><input type="password" name=pass1></td>


                <tr>
                    <td>Passwort wiederholen:</td>
                    <td><input type="password" name=pass2></td>
                </tr>
                <tr>
                    <td colspan="2"><input class="button" type="submit"></td>
                </tr>
            </table>
        </form>
    </div>

	<?php
if ($pwSame === FALSE) {
    echo '<div class=meldung> Die Passworte stimmen nicht überein!<br>
          <a class="button" onclick="hide_massage()" >schließen</a></div>';
}
?><div id="foot"><?php echo VERSION; ?></div>
    </body>
</html>
