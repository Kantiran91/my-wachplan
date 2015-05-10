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
			<td><input class="datepicker" type="text" name="start" value=""></td>
			<td>Enddatum</td> <!-- //TODO Umwanden in html5 -->
			
			<td><input class="datepicker" type="text" name="end" value=""></td>
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
			<td colspan="4"><input class=button type="submit" value="senden"></td>
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
			<td colspan="2"><input class=button type="submit" value="senden"></td>
		</tr>

	</table>
</form>
