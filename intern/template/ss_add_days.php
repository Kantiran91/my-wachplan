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

<h3>Wachtage nach Wochentag hinzufügen:</h3>
<form class="formular" id=more_days >
	<script>
  	$(function() {
    	$( ".datepicker" ).datepicker();
  	});
  </script>

	<table>
		<tr>
			<td>Startdatum</td>
			<!-- //TODO Umwanden in html5 -->
			<td><input class="datepicker" type="date" name="start" value=""></td>
			<td>Enddatum</td> <!-- //TODO Umwanden in html5 -->

			<td><input class="datepicker" type="date" name="end" value=""></td>
		</tr>
		<tr>
			<td><input type="checkbox" name="mon" value="1">Montag</td>
			<td><input type="checkbox" name="di" value="2">Dienstag</td>
			<td><input type="checkbox" name="mi" value="3">Mittwoch</td>
			<td><input type="checkbox" name="do" value="4">Donnerstag</td>
		</tr>
		<tr>
			<td><input type="checkbox" name="fr" value="5">Freitag</td>
			<td><input type="checkbox" name="sa" value="6">Samstag</td>
			<td><input type="checkbox" name="so" value="7">Sonntag</td>
		</tr>
		<tr>
			<td colspan="4"><input class="button submit" type="submit" value="senden"></td>
		</tr>

	</table>
</form>

<h3>1 Tag hinzufügen</h3>
<form class="formular" id=new_day>
	<script>
  	$(function() {
    	$( ".datepicker" ).datepicker();
  	});
  </script>

	<table>
		<tr>
			<td>Tag</td>
			<!-- //TODO Umwanden in html5 -->
			<td><input class="datepicker" type="text" name="day" value=""></td>
		</tr>
		<tr>
			<td colspan="2"><input class="button submit" type="submit" value="senden"></td>
		</tr>

	</table>
</form>
