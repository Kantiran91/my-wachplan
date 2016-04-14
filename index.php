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
 * Startseite des öffentlichen Bereichs
 *
 * Von hier aus sollte man in folgende Bereiche kommen
 * - Registierung
 * - neues Passwort anfordern
 * - Login in dern Internen Bereich
 **/

require_once __DIR__ . '/init.php';

createHeader('Login');
?>
<body>
    <div class="modul" id="Formular">
        <h1>Wachplan Login</h1>
        <form id="login" action="intern/login.php" method="POST">
            <table>
                <tr>
                    <td>Benutzername</td>
                    <td><input type="text" id="name" name="username"></td>
                </tr>
                <tr>
                    <td>Passwort</td>
                    <td><input type="password" id="pass" name="pass"></td>
                </tr>
                <tr>
                    <td colspan="2"><input id="submitButton" class="button big" type="submit"></td>
                </tr>
            </table>
        </form>
    </div>
    <div class="modul" id="sonstiges">
        <a href="anmeldung/index.php">Noch nicht angemeldet?</a> <br> <a
            href="pw_vergessen.php"
        >Passwort vergessen?</a>
    </div>
    <div id="foot"><?php echo VERSION; ?>
    </div>
		<?php
if (isset($_GET['error']) === TRUE) {
    include ROOT . '/include/errormeldung.php';
}
?>
</body>
</html>
