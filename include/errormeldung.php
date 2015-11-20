<?php
/**
 * HTML Vorlage für Fehlermeldungen.
 *
 * Ausgabe ist eine Formatierte Fehlermeldung.
 *
 * PHP versions 5
 *
 * LICENSE: This source file is subject CC BY 4.0 license
 *
 * @author  Sebastian Friedl <friedl.sebastian@web.de>
 * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
 * @version GIT: $Date: Thu May 7 14:43:07 2015 +0200$
 * @link    http:/salem.dlrg.de
 **/
?>
<div class=meldung>
    <h4>Fehler!</h4>
	<?php
    echo get('error');
?>
	<br> <br> <a class="button" onclick="hide_massage()">schließen</a> <br>
</div>
