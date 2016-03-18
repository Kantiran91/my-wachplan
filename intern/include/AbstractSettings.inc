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
 * Stell das Interface für alle Administratoreneinstellungen dar.
 *
 * Alle Administratoreneinstellungen erben hiervon, damit sie die richtigen
 * Methoden für die Kommunikation mit dem View-Pattern haben.
 */
abstract class AbstractSettings
{

    const DISPLAYNAME = '';

    /**
     *
     * @var String Name für die Einstellung der Angezeigt werden soll.
     */
    public $displayname = '';

    /**
     *
     * @var String Name für die Einstellung in der Titlezeile angezeigt wird.
     */
    public $title = '';

    /**
     *
     * @var array Frontend
     */
    protected $frontend = array();


    /**
     * Konstruktor.
     */
    public function __construct()
    {
        checkSession();
        checkRights(2);
        $this->frontend['header'] = $this->displayname;
        $this->frontend['moduls'] = array();

    }//end __construct()

    /**
     * Erstellt das Frontend für die Einstellungen.
     *
     * @return string JSON codiertes frontend
     */
    abstract public function getFrontend();

    /**
     * Wird vom Cronjob aufgerufen.
     *
     * @param string $time Timestamp von auruf des Cronjob.
     *
     * @return boolean True wenn alles richtig durchgeführt wurde.
     */
    public function cronjob($time)
    {
        return TRUE;

    }//end cronjob()

    /**
     * Erzeugt ein Array, das einen valides Modul erzeugt.
     *
     * @param string $header      Header des Moduls.
     * @param string $type        Type des Moduls zurZeit Form & Table.
     * @param string $descriptons Beschreibung des Moduls.
     *
     * @return array Modul, das mit Content erweitert werden kann.
     */
    static function generateModule($header, $type, $descriptons = '')
    {
        return array(
                'header'      => $header,
                'type'        => $type,
                'descriptons' => $descriptons,
               );

    }//end generateModule()

}//end class

?>