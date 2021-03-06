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
  * This script is used to change the data and the password of the user
  * @TODO    Frontend und Backend Trennen
  **/
require_once '../init.php';
require_once 'aservice/UserSettings.inc';
require_once INCLUDEPATH .'UserContactData.inc';
require_once INCLUDEPATH .'WpUserData.inc';
require_once INCLUDEPATH .'LdapUser.inc';
require_once INCLUDEPATH .'DefaultUser.inc';
checkSession();
$user = unserialize($_SESSION['userInterface']);

/*
 * Benutzerdaten ändern
 */

if (isset($_GET['user_name']) === TRUE) {
    $_SESSION['user_name'] = get('user_name');
    $_SESSION['email'] = get('email');
    $ContactData = new UserContactData();
    $ContactData->username = get('user_name');
    $ContactData->firstName = get('first_name');
    $ContactData->lastName = get('last_name');
    $ContactData->eMail = get('email');
    $ContactData->phoneNumber = get('tele');
    $status = $user->saveUserContactData($ContactData);
    if ($status === false){
        echo '<div class=meldung>Fehler beim Eintragen der Kontakt-Daten.<br>
        <a class="button" onclick="hide_massage()" >schließen</a></div>';
    }

    $userData = new WpUserData();
    $userData->friend = get('friend');
    $userData->gb = get('gb');
    $userData->abzeichen = get('abzeichen');
    $userData->med = get('med');
    $status = $userData->saveInDatabase();
    if ($status === false){
        echo '<div class=meldung>Fehler beim Eintragen der Wachplan-Daten.<br>
        <a class="button" onclick="hide_massage()" >schließen</a></div>';
    };

}//end if

/*
 *  Hole die Liste von Wunschpartnern
 */

$users = UserSettings::getAllUser();

/*
 * Passwort ändern
 */
if (passwordFieldsAreSet() && passwordFieldsAreEqual()) {
    $newPassword = post('pass1');
    $oldPassword = post('oldPassword');
    $pwSave = $user->changePassword($oldPassword,$newPassword);
}//end if

$userData = $user->getUserContactData();

$userWpData = new WpUserData();
$userWpData->getFromDatabase($_SESSION['id']);

// FRONTEND
createHeader('Eigene Daten');
?>
<body>
	<?php require 'template/menu.php'; ?>
	<div class="modul" id="own_data">
        <h1>Meine persönlichen Daten</h1>
        <form class="formular" action="change_data.php" method="get">
            <table>
                <tr>
                    <td><label for="username">Benutzername</label></td>
                    <td><input type=text name=user_name readonly
                        value="<?php echo $userData->username; ?>"
                    ></td>
                </tr>
                <tr>
                    <td><label for="first_name">Vorname</label></td>
                    <td><input type=text name=first_name
                        value="<?php echo $userData->firstName; ?>"
                    ></td>
                </tr>
                <tr>
                    <td><label for="last_name">Nachname</label></td>
                    <td><input type=text name=last_name
                        value="<?php echo $userData->lastName; ?>"
                    ></td>
                </tr>
                <tr>
                    <td>E-Mail:</td>
                    <td><input type=text name=email
                        value="<?php echo $userData->eMail; ?>"
                    ></td>
                </tr>
                <tr>
                    <td>Telefonnummer:</td>
                    <td><input type=text name=tele
                        value="<?php echo $userData->phoneNumber; ?>"
                    ></td>
                </tr>
                <tr>
                    <td>Geburtsdatum:</td>
                    <td><input type=text name=gb value="<?php echo $userWpData->gb; ?>"></td>
                </tr>
                <tr>
                    <td>Abzeichen:</td>
                    <td><select id="abzeichen" name="abzeichen" >
                    <option value="<?php echo $userWpData->abzeichen; ?>" selected><?php
                    echo  $userWpData->abzeichen;?></option>
                    <option value="DRSA Bronze" >DRSA Bronze</option>
                    <option value="DRSA Silber" >DRSA Silber</option>
                    <option value="DRSA Gold" >DRSA Gold</option>
                    </select></td>
                </tr>
                <tr>
                    <td>Erste-Hilfe-Ausbildung:</td>
                    <td><select id="med"name="med" >
                    <option value="<?php echo  $userWpData->med; ?>" selected><?php
                    echo $userWpData->med;  ?></option>
                    <option value="" ></option>
                    <option value="eh" >EH Kurs</option>
                    <br><option value="san" >San A</option>
                    <option value="san" >San B</option>
                    <br><option value="san" >Schulsani</option>
                    <option value="san" >Rettungsdienst</option>
                    <br>
                    </select></td>
                </tr>
                <tr>
                    <td>Wunschpartner</td>
                    <td><select name="friend">
                            <option value="NULL"></option>
		<?php generateUserList($users, (int)$userWpData->friend); ?>
		</select></td>
                </tr>
                <tfoot>
                    <tr>
                        <td colspan="2"><input class="button submit" type="submit"
                            value="Senden"
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
                    <td>altes Passwort:</td>
                    <td><input type="password" name=oldPassword></td>

                <tr>
                <tr>
                    <td>Neues Passwort:</td>
                    <td><input type="password" name=pass1></td>


                <tr>
                    <td>Passwort wiederholen:</td>
                    <td><input type="password" name=pass2></td>
                </tr>
                <tr>
                    <td colspan="2"><input id="submitPassword" class="button submit" type="submit"></td>
                </tr>
            </table>
        </form>
    </div>

	<?php
if (passwordFieldsAreSet() && !passwordFieldsAreEqual()) {
    echo '<div class=meldung> Die Passworte stimmen nicht überein!<br>
          <a class="button" onclick="hide_massage()" >schließen</a></div>';
}
if (isset($pwSave) && $pwSave === FALSE) {
    echo '<div class=meldung> Password konnte nicht gespeichtert werden,
               melden sie sich beim Admin<br>
          <a class="button" onclick="hide_massage()" >schließen</a></div>';
}
?><div id="foot"><?php echo VERSION?>;
</div>
</body>
</html>
<?php
/**
 * check if the Passwordfields are set.
 * @return bool
 */
function passwordFieldsAreSet()
{
    return isset($_POST['pass1']) === TRUE && isset($_POST['pass2']) === TRUE;
}

function passwordFieldsAreEqual()
{
    return $_POST['pass1'] === $_POST['pass2'];
}

function generateUserList($users, $friend)
{
    foreach ($users as $user) {
        if ($user[0] === $friend) {
            echo '<option value="';
            echo $user[0] . '"selected >';
        } else {
            echo '<option value="';
            echo $user[0] . '">';
        }

        echo $user[1];
        echo '</option><br/>';
    }
}

?>
