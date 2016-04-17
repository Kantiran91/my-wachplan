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
 * Frontend der Anmeldung.
 **/
require_once '../init.php';
createHeader('Anmeldung');
$tage = getDays();
?>
<body>
    <div class="modul" id="Formular">
        <h1>Wachplan Registrierung</h1>
        <br>
        <form id="anmeldung" action="">
            Benutzername: <input type="hidden" name="username" value="k/a" /><input
                type="text" name="username"
            ><br> Vorname: <input type="hidden" name="vorname" value="k/a" /><input
                type="text" name="vorname"
            ><br> Nachname: <input type="hidden" name="nachname" value="k/a" /><input
                type="text" name="nachname"
            ><br> E-Mail-Adresse: <input type="hidden" name="email" value="k/a" /><input
                type="text" name="email"
            ><br> Telefonnummer: <input type="hidden" name="telefon" value="k/a" /><input
                type=text name="telefon"
            ><br> Geburtsdatum: <input type="hidden" name="Geburtsdatum"
                value="k/a"
            /><input type=text name="Geburtsdatum"><br> Abzeichen:<br> <input
                type="hidden" name="abzeichen" value="k/a"
            /> <select name="abzeichen" size="3">
                <option>DRSA Bronze</option>
                <option>DRSA Silber</option>
                <option>DRSA Gold</option>
            </select><br> Erste-Hilfe Kurs:<input type="hidden" name='eh'
                value="nein_eh"
            /><input type="checkbox" name='eh' value="eh_ja"><br> San-Kurs (auch
            Schulsani):<input type="hidden" name='san' value="nein_san" /><input
                type="checkbox" name='san' value="san_ja"
            ><br> An folgenden Tagen kann ich Wachdienst machen:
            <table>
	<?php
$i = 1;
foreach ($tage as $tag) {
    if ($i === 0) {
        echo '<tr>';
    }

    echo '<td>';
    echo '<input type="hidden" name="';
    echo $tag[0];
    echo '"value="nein';
    echo $tag[1];
    echo '" >';
    echo dateDe($tag[1]);
    echo '<input type="checkbox" name="';
    echo $tag[0];
    echo '"value="ja';
    echo '" ></td>';
    if ($i >= 5) {
        echo "</tr>\n";
        $i = 0;
    }

    $i ++;
}//end foreach
?>
	</table>
            Ein neuer Wunschpartner kann später im interen Bereich nachgetragen
            werden.<br> <input class="button big" type="submit" value="senden">
        </form>
    </div>
    <div id="foot"><?php echo VERSION; ?></div>
</body>
</html>
