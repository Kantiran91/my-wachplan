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
 * Erstelle für die Seite eine Header
 * @see     createHeader
 **/

/**
 * Erzeug den HTML-Header.
 *
 * @param string $title Title der Seite.
 *
 * @return boolean  Ist immer TRUE
 */
function createHeader($title)
{
    // @TODO Stylesheet für den Kalender anpassen
    ?>
    <!DOCTYPE html>
    <html>
    <head>
    <title> <?php echo $title .'@' . $GLOBALS['config']['title']; ?></title>
    <meta charset="UTF-8" />
    <link href="<?php echo $GLOBALS['config']['base'];?>/style/style.css" type="text/css" rel="stylesheet" />
    <link href="<?php echo $GLOBALS['config']['base'];?>/style/icons.css" type="text/css" rel="stylesheet" />
    <script src="<?php echo $GLOBALS['config']['base'];?>/js/jquery.min.js"></script>
    <script src="<?php echo $GLOBALS['config']['base'];?>/js/intern_function.js"></script>
    <script src="<?php echo $GLOBALS['config']['base'];?>/js/buttons.js"></script>
    <script src="<?php echo $GLOBALS['config']['base'];?>/js/formulars.js"></script>
    </head>

    <?php
    return TRUE;

}//end createHeader()
?>
