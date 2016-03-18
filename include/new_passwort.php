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
 * Frontend zur Neuanforderung des Passwortes
 **/
require_once $_SERVER['DOCUMENT_ROOT'] .'/wachplan/init.php';
require_once ASERVICE.'UserSettings.inc';
require_once INCLUDEPATH .'LdapUser.inc';
require_once INCLUDEPATH .'DefaultUser.inc';


$user = generateUserObject();

$username = getGetParamOrNull('username');
if (!$user->checkUserExists($username)){
    header('Location: ../index.php?error=Username existiert nicht!');
    exit();
}

$newPasswort = UserSettings::randomstring(9);
$result = $user->setNewPassword($username,$newPasswort);

if ($result === TRUE) {
    var_dump($newPasswort);
    $attibute  = $user->getUserBaseAttributes($username);
    var_dump($attibute);
    $email = $attibute['email'];
    $mail = new mail($email, 'neue Passwort');
    $mail->setText("Dein neues Passwort lautet :\n" . $newPasswort . "\n");
    //$mail->sendMail();
}

?>
<div class="meldung">
    <p>Benutzer gefunden</p>
    <br> Dein neues Passwort wird dir zugeschickt.<br> <a class="button"
        onclick="hide_massage()"
    >schließen</a>
</div>
