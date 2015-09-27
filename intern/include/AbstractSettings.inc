<?php
 /**
  * Superklasse für alle Settings.
  *
  * PHP versions 5
  *
  * LICENSE: This source file is subject CC BY 4.0 license
  *
  * @category abstracte
  * @author  Sebastian Friedl <friedl.sebastian@web.de>
  * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
  * @version GIT:  $Date$
  * @link    http:/salem.dlrg.de
  **/

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
    protected  $frontend = array();

    /**
     * Konstruktor
     */
    public function __construct(){
        checkRights(2);
        $this->frontend['header'] = $this->displayname;
        $this->frontend['moduls'] = array();
    }

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
     public function cronjob(){
        return TRUE;
    }

}//end class

?>