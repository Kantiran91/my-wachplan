<?php
 /**
  * Enthält die Klasse Table.
  *
  *  Diese stellt ein Interpreter für den View des MVP System dar.
  *
  * PHP versions 5
  *
  * LICENSE: This source file is subject CC BY 4.0 license
  *
  * @author  Sebastian Friedl <friedl.sebastian@web.de>
  * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
  * @version GIT:  $Date$
  * @link    http:/salem.dlrg.de
  * @todo    Möglichkeit zwei Spalten und Zeilen zusammen zu fassen.
  **/

/**
 * Interpreterklasse Table.
 *
 * Im Konstruktor der Klasse wird der Inhalt des $content als Interpretiert und als
 * HTML-Tabelle wieder ausgegeben.
 */
class Table
{


    /**
     * Konstuktor.
     *
     * Siehe Klassenbeschreibung!
     *
     * @param mixed $content Inhalt der von JSON_decode() zurückgeben wird.
     */
    public function __construct($content)
    {
        echo '<table>';
        if (isset($content->header) === TRUE) {
            echo '<thead><tr>';
            foreach ($content->header as $column) {
                echo '<th>';
                echo $column;
                echo '</th>';
            }

            echo '</tr></thead>';
        }

        echo '<tbody>';
        foreach ($content->table as $row) {
            //var_dump($row);
            echo '<tr>';
            if (is_array($row)) {
                foreach ($row as $column) {
                    echo '<td>';
                    echo $column;
                    echo '</td>';
                }
            } else {
                echo '<td>';
                echo $row;
                echo '</td>';
            }

            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';

    }//end __construct()

}//end class

?>