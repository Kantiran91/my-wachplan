<?php
 /**
  * Stellt das Menu des internen Bereichs dar.
  *
  * PHP versions 5
  *
  * LICENSE: This source file is subject CC BY 4.0 license
  *
  * @author  Sebastian Friedl <friedl.sebastian@web.de>
  * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
  * @version GIT: $Id$ $Date: Wed Apr 22 13:22:48 2015 +0200$
  * @link    http:/salem.dlrg.de
  **/
?>
<div id="menu">
    <ul>
        <li><a href="index.php#own_data">Eigene Daten </a></li>
        <li><a href="index.php#plan">Wachplan </a></li>
        <li><a href="index.php#tele_plan">Telefonliste </a></li>
<?php
if ($_SESSION['rights'] >= 2) {
    ?>
    <li><a href="feedback_show.php">Feedback anschauen</a></li>
    <li><a  href="test.php">Test</a></li>
    <li><a class="menupoint" href="system_settings.php"> Systemeinstellungen</a></li>
    <?php
}
?>
<li><a href="logout.php" class="menupoint">Logout </a></li>
    </ul>
</div>
