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
 ?>
<h2>Benutzer bearbeiten</h2>
<select name="user_id" id="user_id">
	<option value="NULL"></option>
<?php
foreach ($users as $user) {
    echo '<option value="';
    echo $user[0] . '">';
    echo $user[1];
    echo '</option>' . "\n";
}
?>
		</select>
<form class="formular" id=change_user_data>
	<input type="hidden" name="id" id="c_id" value="k/a" />
	<table>
		<tr>
			<td>Benutzername:<input type="hidden" name="user_name" value="k/a" /></td>
			<td><input type="text" name="user_name" id="c_user"></td>
		</tr>
		<tr>
			<td>Vorname:<input type="hidden" name="first_name" value="k/a" /></td>
			<td><input type="text" name="first_name" id="c_first"></td>
		</tr>
		<tr>
			<td>Nachname:<input type="hidden" name="last_name" value="k/a" /></td>
			<td><input type="text" name="last_name" id="c_last"></td>
		</tr>
		<tr>
			<td>E-Mail:<input type="hidden" name="email" value="k/a" /></td>
			<td><input type="text" name="email" id="c_email"></td>
		</tr>
		<tr>
			<td>Telefonnummer:<input type="hidden" name="tele" value="k/a" /></td>
			<td><input type="text" name="tele" id="c_tele"></td>
		</tr>
		<tr>
			<td>Geburtsdatum:<input type="hidden" name="gb" value="k/a" /></td>
			<td><input type="date" name="gb" id="c_gb"></td>
		</tr>
		<tr>
			<td>Abzeichen:<input type="hidden" name="abzeichen" value="k/a" /></td>
			<td><select name="abzeichen" id="c_abzeichen">
					<option value="DRSA Bronze">DRSA Bronze</option>
					<option value="DRSA Silber">DRSA Silber</option>
					<option value="DRSA Gold">DRSA Gold</option>
			</select></td>
		</tr>
		<tr>
			<td>Rechte<input type="hidden" name="rights" value="0" /></td>
			<td><select name="rights" id="c_rights">
					<option value="0">Wachgänger</option>
					<option value="1">Wachleiter /stellv. Wachleiter</option>
					<option value="2">Admin</option>
			</select></td>
		</tr>
		<tr>
			<td>Erste-Hilfe Kurs:<input type="hidden" name='eh' value="FALSE" /></td>
			<td><input type="checkbox" name='eh' value="TRUE" id="c_eh"></td>
		</tr>
		<tr>
			<td>San-Kurs (auch Schulsani):
			<input type="hidden" name='san' value="FALSE" />
			</td>
			<td><input type="checkbox" name='san' value="TRUE" id="c_san"></td>
		</tr>
		<tr>
			<td colspan="2"><input class="button submit" type="submit" value="senden"></td>
		</tr>
		<tr>
			<td colspan="2"><a id=button_delete class=button onclick="delete_user()">Benutzer
					löschen</a></td>
		</tr>

	</table>
</form>
