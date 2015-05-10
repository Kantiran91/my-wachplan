<?php
/**
 * Erstelle für die Seite eine Header
 *
 * Dieses Datei enhält die Funktion Header. Mit der ein HTML Header erzeugt
 *  werden kann, der Tilte und Metadaten enthält.
 *
 * PHP versions 5
 *
 * LICENSE: This source file is subject CC BY 4.0 license
 *
 * @author  Sebastian Friedl <friedl.sebastian@web.de>
 * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
 * @version GIT: $Date: Thu May 7 14:43:07 2015 +0200$
 * @link    http:/salem.dlrg.de
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
    // TODO Stylesheet für den Kalender anpassen
    ?>
    <!DOCTYPE html>
    <html>
    <head>
    <title> <?php echo $title; ?>@Wachplan 2014</title>
    <meta charset="UTF-8" />
    <link href="/wachplan/style/style.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet"
        href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css"
    >
    <script src="/wachplan/js/jquery.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script src="/wachplan/js/intern_function.js"></script>
    <script src="/wachplan/js/buttons.js"></script>
    <script src="/wachplan/js/formulars.js"></script>
    </head>

    <?php
    return TRUE;

}//end createHeader()

?>
