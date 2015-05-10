<?php
 /**
  * Fügt einen einzelen Tag dem Wachplan hinzu
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
require_once '../../init.php';
checkSession();

$stmt = $database->prepare('INSERT INTO `wp_days`( `date`) VALUES (?)');
$stmt->bind_param('s', $day);
$day = date('Y-m-d', strtotime($_POST['day']));
$stmt->execute();
?>
<!-- Anzeige das alles funktuniert -->
<div class="meldung">
	Der neue Wachtag wurde hinzugefügt. <br> <a class="button"
		onclick="hide_massage()">schließen</a>
</div>


