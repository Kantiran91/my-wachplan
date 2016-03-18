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

require_once '../init.php';
require_once '../intern/include/AbstractSettings.inc';
require_once '../intern/include/Utils.inc';

class PlanSettings extends AbstractSettings{
    const DISPLAYNAME = 'Wachplan verwalten';
    /**
     * @var String Displayname
     */
    public $displayname = self::DISPLAYNAME;

    /**
     * @var string Titel in der Titelleiste
     */
    public $title = self::DISPLAYNAME;

    function __construct() {
        ;
    }

    /**
     * Erstellt das Frontend für die Einstellungen.
     *
     * @return string JSON codiertes frontend
     */
    public function getFrontend(){
        $modulAddDay = static::generateModule('Neue Wachtage hinzufügen', 'Form');
        $modulAddDay['content'] = array(
                                   'name' => 'addDay',
                                   'target' => 'addDay'
                                   );
         $attributes = '[
          {
            "display": "Startdatum",
            "name": "start",
            "type": "date"
          },{
            "display": "Enddatum",
            "name": "end",
            "type": "date"
          },{
            "display": "Days",
            "name": "day",
            "type": "checkbox",
            "value": ["mo","di","mi"","do","fr","sa","so"],
            "valName": ["Montag","Dienstag","Mittwoch","Donnerstag","Freitag","Samstag","Sonntag"]
          }]';

        $modulAddDay['content']['inputs'] =json_decode($attributes, TRUE);
        $this->frontend['moduls'][] = $modulAddDay;

        return json_encode($this->frontend);
    }//end getFrontend()

}//end class
?>
