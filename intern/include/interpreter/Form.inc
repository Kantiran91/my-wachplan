<?php

/**
 * Enthält die Klasse FORM.
 *
 *  Diese stellt ein Interpreter für den View des MVP System dar. Mit diesem
 *  Interpreter werden Module als Formular dargestellt.
 *
 * PHP versions 5
 *
 * LICENSE: This source file is subject CC BY 4.0 license
 *
 * @author  Sebastian Friedl <friedl.sebastian@web.de>
 * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
 * @version GIT:  $Date$
 * @link    http:/salem.dlrg.de
 * @todo Jede Interpreter Klasse soll die eigene CSS-Datei und ggf. auch Java-Skript
 * -Datei miteinbinden.
 * @todo required Attribute einbinden.
 **/

/**
 * Interpreterklasse FRom.
 *
 * Im Konstruktor der Klasse wird der Inhalt des $content als Interpretiert und als
 * HTML-Formular wieder ausgegeben.
 */
class Form
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
        $url = $_SERVER['REQUEST_URI'];
        echo "\n<!-- Formular " . $content->name . " --> \n";
        echo '<form class="formular" ';
        echo 'id= "' . $content->name . '" ';
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
     * Erzeugt aus den Input Daten ein Datums-Eingabefeld mit den jquery Datespicker.
     *
     * @param object $input Array mit den Informationen über das Eingabefeld.
     *
     * @return void
     * @todo schöner Lösung für den datepicker
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
        echo '<input type="text"';
        echo 'name="' . $input->name . '" ';
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
        echo 'name="' . $input->name . '" >';
        for ($i = 0; $i < $count; $i ++) {
            echo '<option value="' . $input->value[$i] . '" >';
            echo $input->valName[$i] . '</option>';
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

}//end class

?>