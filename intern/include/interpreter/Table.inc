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
  * Enthält die Klasse Table.
  *
  *  Diese stellt ein Interpreter für den View des MVP System dar.
  * @TODO    Möglichkeit zwei Spalten und Zeilen zusammen zu fassen.
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
            echo '<tr>';
            if (is_array($row) === TRUE) {
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

    /**
     * Get the Styles that are used for the tables.
     *
     * @return array List of styles that are used for tabels
     */
    public static function getStyles()
    {
        return array();

    }//end getStyles()

    /**
     * Get the Scripts that are used for the tables.
     *
     * @return array List of scripts that are used for tabels
     */
    public static function getScripts()
    {
        return array();

    }//end getScripts()

}//end class

?>