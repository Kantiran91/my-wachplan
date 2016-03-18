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
  * Stellt das Menu des internen Bereichs dar.
  **/
?>
<div class="menu">
    <ul>
        <li><a href="index.php#own_data">Eigene Daten </a></li>
        <li><a href="index.php#plan">Wachplan </a></li>
        <li><a href="index.php#tele_plan">Telefonliste </a></li>
<?php
if ($_SESSION['rights'] >= 2) {
    ?>
    <li><a href="feedback_show.php">Feedback anschauen</a></li>
    <li><a  href="system_settings2.php">Test</a></li>
    <li><a class="menupoint" href="system_settings.php"> Systemeinstellungen</a></li>
    <?php
}
?>
<li><a href="logout.php" class="menupoint">Logout </a></li>
    </ul>
</div>
