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