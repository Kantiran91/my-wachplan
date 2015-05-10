<h2>Neuen Benutzer anlegen</h2>
<form class="formular" id=new_user action="include/back_add_user.php"
	method="post">
	<table>
		<tr>
			<td>Benutzername:<input type="hidden" name="username" value="k/a" /></td>
			<td><input type="text" name="username"></td>
		</tr>
		<tr>
			<td>Vorname:<input type="hidden" name="first_name" value="k/a" /></td>
			<td><input type="text" name="first_name"></td>
		</tr>
		<tr>
			<td>Nachname:<input type="hidden" name="last_name" value="k/a" /></td>
			<td><input type="text" name="last_name"></td>
		</tr>
		<tr>
			<td>E-Mail:<input type="hidden" name="email" value="k/a" /></td>
			<td><input type="text" name="email"></td>
		</tr>
		<tr>
			<td>Telefonnummer:<input type="hidden" name="tele" value="k/a" /></td>
			<td><input type="text" name="tele"></td>
		</tr>
		<tr>
			<td>Geburtsdatum:<input type="hidden" name="gb" value="k/a" /></td>
			<td><input type="date" name="gb"></td>
		</tr>
		<tr>
			<td>Abzeichen:<input type="hidden" name="abzeichen" value="k/a" /></td>
			<td><select name="abzeichen">
					<option>DRSA Bronze</option>
					<option>DRSA Silber</option>
					<option>DRSA Gold</option>
			</select></td>
		</tr>
		<tr>
			<td>Rechte:<input type="hidden" name="rights" value="0" /></td>
			<td><select name="rights">
					<option value="0">Wachgänger</option>
					<option value="1">Wachleiter /stellv. Wachleiter</option>
					<option value="2">Admin</option>
			</select></td>
		</tr>
		<tr>
			<td>Erste-Hilfe Kurs:<input type="hidden" name='eh' value="FALSE" /></td>
			<td><input type="checkbox" name='eh' value="TRUE"></td>
		</tr>
		<tr>
			<td>San-Kurs (auch Schulsani):<input type="hidden" name='san'
				value="FALSE" /></td>
			<td><input type="checkbox" name='san' value="TRUE"></td>
		</tr>
		<tr>
			<td colspan="2"><input class=button type="submit" value="senden"></td>
		</tr>

	</table>
</form>
