<?php
 /**
  * File zum erzeugen eines neuen Passworts.
  *
  * PHP versions 5
  *
  * LICENSE: This source file is subject CC BY 4.0 license
  *
  * @author  Sebastian Friedl <friedl.sebastian@web.de>
  * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
  * @version GIT:  $Date: Thu May 7 14:43:07 2015 +0200$
  * @link    http:/salem.dlrg.de
  **/
require_once 'init.php';
createHeader('Passwort vergessen');?>
<body>
	<div class="modul" id="Formular">
		<h1>Passwort vergessen</h1>
		<form id="pw_forgot" action="include/new_passwort.php" method="GET">
			<br> Bitte hier dein Benutzernamen eingeben.
               Ist dir der Benutzername nicht bekannt wende dich bitte an den Administrator! <br>
			<table>
				<tr>
					<td>Benutzername</td>
					<td><input type="text" id="username" name="username"></td>
				</tr>
				<tr>
					<td colspan="2"><input id="submitButton" class="button" type="submit"></td>
				</tr>
			</table>

		</form>
		<a href="index.php" class="button">zurück</a>
	</div>
	<div id="foot"><?php echo VERSION; ?></div>
</body>
</html>
