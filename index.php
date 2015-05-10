<?php
/**
 * Startseite des öffentlichen Bereichs
 *
 * Von hier aus sollte man in folgende Bereiche kommen
 * - Registierung
 * - neues Passwort anfordern
 * - Login in dern Internen Bereich
 *
 * PHP versions 5
 *
 * LICENSE: This source file is subject CC BY 4.0 license
 *
 * @author  Sebastian Friedl <friedl.sebastian@web.de>
 * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
 * @version GIT: $Id$ $Date: Sun May 10 09:51:12 2015 +0200$
 * @link    http:/salem.dlrg.de
 **/

require_once __DIR__ . '/init.php';
createHeader('Login');
?>
<body>
    <div class="modul" id="Formular">
        <h1>Wachplan Login</h1>
        ACHTUNG! Auf Grund von Problemen ist zum Login jetzt der Benutzername
        und nicht mehr die eMail notwendig<br> Für Wachgänger von 2014 ist der
        Benutzername vorname.nachname und kann intern geändert werden. <br>
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
                    <td colspan="2"><input class="button big" type="submit"></td>
                </tr>
            </table>
        </form>
    </div>
    <div class="modul" id="sonstiges">
        <a href="anmeldung/index.php">Noch nicht angemeldet?</a> <br> <a
            href="pw_vergessen.php"
        >Passwort vergessen?</a>
    </div>
    <div class="modul" id="News">
        <h1>Aktuelles</h1>
	<?php require 'changelog.html'; ?>
	</div>
    <div id="foot"><?php echo VERSION; ?>
	<a rel="license" href="http://creativecommons.org/licenses/by/4.0/"> <img
            alt="Creative Commons Lizenzvertrag" style="border-width: 0"
            src="http://i.creativecommons.org/l/by/4.0/88x31.png"
        />
        </a>
    </div>
		<?php
if (isset($_GET['error']) === TRUE) {
    include __DIR__ . '/include/errormeldung.php';
}
?>
</body>
</html>
