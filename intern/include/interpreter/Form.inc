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
 * Enthält die Klasse FORM.
 *
 *  Diese stellt ein Interpreter für den View des MVP System dar. Mit diesem
 *  Interpreter werden Module als Formular dargestellt.
 **/

/**
 * Interpreterklasse Form.
 *
 * Im Konstruktor der Klasse wird der Inhalt des $content als Interpretiert und als
 * HTML-Formular wieder ausgegeben.
 */
class Form
{

    private $_name ="";

    /**
     * Konstuktor.
     *
     * Siehe Klassenbeschreibung!
     *
     * @param mixed $content Inhalt der von JSON_decode() zurückgeben wird.
     */
    public function __construct($content)
    {
        $this->_name = $content->name;
        $url = $_SERVER['REQUEST_URI'];
        echo "\n<!-- Formular " . $this->_name . " --> \n";
        echo '<form class="formular" ';
        echo 'id= "' . $this->_name . '" ';
        echo 'action= "' . $url . '&method=' . $content->target . '" ';
        echo 'method="POST" ';
        echo ">\n";
        echo "<table>\n";

        // Formularinhalt
        foreach ($content->inputs as $input) {
            $type = '_' . $input->type;
            $this->$type($input);
        }

        // Send Button
        echo '<td colspan="4">';
        echo '<input class=button type="submit" value="senden">';
        echo '</td>';
        echo "</table>\n";
        echo "</form>\n";

    }//end __construct()

    /**
     * Stellt die CSS-Dateien zur Verfügung die für Formulare notwendig sind.
     *
     * @return array: Mit dem Namen und dem Pfad
     */
    public static function getStyles()
    {
        return array();

    }//end getStyles()

    /**
     * Stellt die Java-Skript-Datein zur Verfügung.
     *
     * @return array: Mit dem Namen und dem Pfad
     */
    public static function getScripts()
    {
        return array('formulars.js' => '/wachplan/js/formulars.js');

    }//end getScripts()

    /**
     * Erzeugt aus den Input Daten ein Datums-Eingabefeld mit den jquery Datespicker.
     *
     * @param object $input Array mit den Informationen über das Eingabefeld.
     *
     * @return void
     * @TODO datepicker in Javaskript datei unterbringen.
     */
    private function _date($input)
    {
        echo "<tr>\n";
        echo '<script>$(function() {$( ".datepicker" ).datepicker();});</script>' .
                     "\n";
        echo '<td>';
        echo $input->display;
        echo '</td>' . "\n";
        echo '<td>';
        echo '<input class="datepicker" ';
        echo 'type="text" ';
        echo 'name="' . $input->name . '" ';
        echo 'id="' . $this->_name .'_'.$input->name . '" ';
        echo 'value=""';
        echo '>';
        echo "</td>\n";
        echo "</tr>\n";

    }//end _date()

    /**
     * Erzeugt aus den Input Daten ein Text-Eingabefeld.
     *
     * @param object $input Array mit den Informationen über das Eingabefeld.
     *
     * @return void
     */
    private function _text($input)
    {
        echo "<tr>\n";
        echo '<td>';
        echo $input->display;
        echo '</td>' . "\n";
        echo '<td>';
        echo '<input type="text" ';
        echo 'name="' . $input->name . '" ';
        echo 'id="' . $this->_name .'_'.$input->name . '" ';
        echo 'value=""';
        echo '>';
        echo "</td>\n";
        echo "</tr>\n";

    }//end _text()

    /**
     * Erzeugt aus den Input Daten ein Password-Eingabefeld.
     *
     * @param object $input Array mit den Informationen über das Eingabefeld.
     *
     * @return void
     */
    private function _password($input)
    {
        echo "<tr>\n";
        echo '<td>';
        echo $input->display;
        echo '</td>' . "\n";
        echo '<td>';
        echo '<input type="password"';
        echo 'name="' . $input->name . '" ';
        echo 'id="' . $this->_name .'_'.$input->name . '" ';
        echo 'value=""';
        echo '>';
        echo "</td>\n";
        echo "</tr>\n";

    }//end _password()

    /**
     * Erzeugt aus den Input Daten ein Email-Eingabefeld.
     *
     * @param object $input Array mit den Informationen über das Eingabefeld.
     *
     * @return void
     */
    private function _email($input)
    {
        echo "<tr>\n";
        echo '<td>';
        echo $input->display;
        echo '</td>' . "\n";
        echo '<td>';
        echo '<input type="email"';
        echo 'name="' . $input->name . '" ';
        echo 'id="' . $this->_name .'_'.$input->name . '" ';
        echo 'value=""';
        echo '>';
        echo "</td>\n";
        echo "</tr>\n";

    }//end _email()

    /**
     * Erzeugt aus den Input Daten Checkbox-Eingabefelder.
     *
     * Die Besonderheit bei checkbox ist, das in eine Array mehrer
     * checkboxen sein können.
     * Genaueres siehe Dokumenation.
     *
     * @param object $input Array mit den Informationen über das Eingabefeld.
     *
     * @return void
     */
    private function _checkbox($input)
    {
        $numRow = 0;
        echo "<tr>\n";
        echo '<td>';
        echo $input->display;
        echo '</td>' . "\n";
        echo '<td width=200>';
        $count = count($input->values);
        for ($i = 0; $i < $count; $i ++) {
            echo '<input type="checkbox"';
            echo 'name="' . $input->name[$i] . '" ';
            echo 'value="' . $input->value[$i] . '" ';
            echo 'id="' . $this->_name .'_'.$input->name . '" ';
            echo '>' . $input->valName[$i];
            echo '</input>' . "\n";
            if ($numRow === 1) {
                echo '<br>';
                $numRow = 0;
            } else {
                $numRow++;
            }
        }

        echo "</td>\n";
        echo "</tr>\n";

    }//end _checkbox()

    /**
     * Erzeugt aus den Input Daten Select-Eingabefelder.
     *
     * Die Besonderheit bei Select ist, das in eine Array mehrer
     * Select sein können.
     * Genaueres siehe Dokumenation.
     *
     * @param object $input Array mit den Informationen über das Eingabefeld.
     *
     * @return void
     */
    private function _select($input)
    {
        $numRow = 0;
        echo "<tr>\n";
        echo '<td>';
        echo $input->display;
        echo '</td>' . "\n";
        echo '<td width=200>';
        $count = count($input->value);
        echo '<select ';
        echo 'id="' . $this->_name .'_'.$input->name . '" ';
        echo 'name="' . $input->name . '" >';
        for ($i = 0; $i < $count; $i ++) {
            echo '<option value="' . $input->value[$i] . '" >';
            echo $input->valName[$i] . '</option>' ."\n";
            if ($numRow === 1) {
                echo '<br>';
                $numRow = 0;
            } else {
                $numRow++;
            }
        }//end for

        echo '</select>';
        echo "</td>\n";
        echo "</tr>\n";

    }//end _select()

    /**
     * Erzeugt aus den Input Daten eine Formular Header.
     *
     * Ein Formularheader geht über beide Spalten und zeigtn den
     * Displayname an.
     *
     * @param object $input Array mit den Informationen über die Spalte.
     *
     * @return void
     */
    function _header($input)
    {
        echo "<tr>\n";
        echo '<td colspan=2 class="tableheader">';
        echo $input->display;
        echo '</td>' . "\n";
        echo "</td>\n";
        echo "</tr>\n";

    }//end _header()

    /**
     * Erzeugt aus den Input ein hidden Type
     *
     * @param object $input Array mit den Informationen über die Spalte.
     *
     * @return void
     */
    function _hidden($input)
    {
        echo '<input type="hidden" ';
        echo 'name="' . $input->name . '" ';
        echo 'id="' . $input->name . '" ';
        echo 'value="' . $input->value . '" ';
        echo '>';
    }//end _header()

}//end class

?>