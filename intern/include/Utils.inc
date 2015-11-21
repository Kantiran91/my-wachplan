<?php
 /**
  * Kurze Beschreibung des Datei-Inhalts
  *
  * Lange Beschreibung des Datei-Inhaltes, wenn erforderlich.
  *
  * PHP versions 5
  *
  * LICENSE: This source file is subject CC BY 4.0 license
  *
  * @author  Sebastian Friedl <friedl.sebastian@web.de>
  * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
  * @version GIT:  $Date$
  * @link    http:/salem.dlrg.de
  **/

 /**
  * Class Utils is a static Class that includes seversal methods.
  *
  * Class uitls is a static Class. It contains several methods like form
  * checking. All methods that are used in several other class. Should be saved
  * here. The init.php schould not be used therefore.
  */
class Utils
{


    /**
     * Check if all fields of a formular is filled.Then escape the string.
     *
     * @param array $fields Field from a formular.
     *
     * @return string[]|boolean True if all is okay else Error-Array
     */
    static function checkFilledFields(array $fields)
    {
        foreach ($fields as $value) {
            $value = (string) $value;
            if ($value === '') {
                return array('Fehler', 'Nicht alle Felder ausgefüllt');
            }

            $GLOBALS['database']->real_escape_string($value);
        }//end foreach

        return TRUE;

    }//end checkFilledFields()

}//end class

?>