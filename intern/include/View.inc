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

/**
 * Stell den View des MVP dar.
 *
 * Für die Beschreibung des MVP siehe:
 * https://github.com/Kantiran91/my-wachplan/issues/16. Dieses wird als Singleton
 * realisiert da es hier immer nur 1 view geben darf.
 */
class View
{

    /**
     *
     * @var String Inhalt der in der Webseite dargestellt werden soll.
     */
    private $_content = '';

    /**
     *
     * @var array  Sammlung aller Meldungen, die von den Methoden aufgerufen werden.
     */
    private $_meldung = array();

    /**
     *
     * @var String Inhalt der in der Webseite dargestellt werden soll.
     */
    private $_title = '';

    /**
     *
     * @var Array Liste mit css-Dateien die eingebunden werden.
     */
    private $_styles = array();

    /**
     *
     * @var Array Liste mit JavaSkript-Dateien die eingebunden werden sollen.
     */
    private $_scripts = array();

    /**
     *
     * @var Singleton Statische Variable um die einzige Instanz der Klasse zu halten.
     */
    protected static $instance = NULL;

    /**
     * Konstruktor.
     *
     * Externe Instanzierung verbieten
     */
    protected function __construct()
    {
        $this->_styles['style.css'] = '/wachplan/style/style.css';
        $this->_styles['menu.css'] = '/wachplan/style/menu.css';

        $this->_scripts['jquery.min.js'] = '/wachplan/js/jquery.min.js';
        $this->_scripts['jquery-ui.js'] = '//code.jquery.com/ui/1.11.4/jquery-ui.js';
        $this->_scripts['intern_function.js'] = '/wachplan/js/intern_function.js';
        $this->_scripts['buttons.js'] = '/wachplan/js/buttons.js';
    }//end __construct()

    /**
     * Clone.
     *
     * Kopieren der Instanz von aussen ebenfalls verbieten
     *
     * @return void
     */
    protected function __clone()
    {

    }//end __clone()

    /**
     * Gibt die einzige Instanz der Klasse zurück.
     *
     * @return Singleton einzige Instanz der Klasse
     */
    public static function getInstance()
    {
        if (self::$instance === NULL) {
            self::$instance = new self();
        }

        return self::$instance;

    }//end getInstance()

    /**
     * Fügt den Inhalt dem View hinzu.
     *
     * @param string $content Aufbau des Inhalt folgt den Regelen die für den
     * MVP beschrieben sind.
     *
     * @return void
     */
    public function setContent($content)
    {
        if (is_string($content) === TRUE) {
            $this->_content .= $content;
        } else {
            errorMessage(__FILE__, __LINE__);
        }

    }//end setContent()

    /**
     * Set den Titel des Seite.
     *
     * @param string $title Titel der Seite.
     *
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;

    }//end setTitle()

    /**
     * Add a Javascript file to the view. Can be used from the Services itselfs.
     *
     * @param string $filename Filenname of the Javascript File
     * @param string $path     Path of the Javascript File
     *
     * @return void
     */
    public function addScript($filename, $path)
    {
        $this->_scripts[$filename] = $path;

    }//end addScript()

    /**
     * Fügt dem View eine Meldung hinzu.
     *
     * @param string $meldung Meldung die Ausgegeben werden soll.
     *
     * @return void
     */
    public function addMeldung($meldung)
    {
        $this->_meldung[] = $meldung;

    }//end addMeldung()

    /**
     * Gibt den Inhalt des View aus. Und fügt Menü, Header und etc. hinzu.
     *
     * @return void
     */
    public function printPage()
    {
        var_dump($this->_content);
        $contentDecoded = json_decode($this->_content);
        //Binde Modulspezifsche HTML-Attribute in Header ein.
        foreach ($contentDecoded->moduls as $modul){
            include_once 'interpreter/' . $modul->type . '.inc';
            $className = $modul->type;
            //@todo prüfen ob man das nicht auch wie bei Script machen kann!
            foreach ($className::getStyles() as $style){
                $this->_styles[$style['name']] = $style['path'];
            }

            foreach ($className::getScripts() as $scriptName => $scripts){
                $this->_scripts[$scriptName] = $scripts;
            }

        }
        $this->_printHeader();
        $this->_printMenu();

        // Hauptfenster und beschreibung
        echo '<div class="modul">' . "\n";
        echo '<h1>' . $contentDecoded->header . '</h1>' . "\n";
        if (isset($contentDecoded->description) === TRUE) {
            echo $contentDecoded->description . '<br><br>';
        }

        echo '</div>' . "\n";

        //alle anderen Module
        foreach ($contentDecoded->moduls as $modul) {
            echo '<div class="modul">' . "\n";
            echo '<h2>' . $modul->header . '</h2>' . "\n";
            if (isset($modul->description) === TRUE) {
                echo $modul->description . '<br><br>';
            }
            $modulContent = new $modul->type($modul->content);
            echo '</div>' . "\n";
        }

        // alle Meldungen ausgeben
        foreach ($this->_meldung as $meldung) {
            echo '<div class=meldung>';
            echo '<h4> ' . $meldung[0] . '</h4>';
            echo $meldung[1];
            echo '<br><br><a class="button" onclick="hide_massage()">';
            echo 'schließen</a><br></div>';
        }

    }//end printPage()

    /**
     * Erstellt eine vollständige Liste aller A-Services.
     *
     * Dabei stellen A-Services, Services da die für Admins gedacht sind.
     *
     * @return array Liste von Services die zur Verfügung stehen.
     */
    static public function getAServices()
    {
        $submenu = array();
        foreach (glob('aservice/*.inc') as $datei) {
            include_once $datei;
            $dateiName = explode('aservice/', $datei)[1];
            $className = explode('.', $dateiName)[0];
            //!< @TODO Wenn MVP impementiert auf system_settings.php um ändern.
            $submenu[] = array(
                          $className::DISPLAYNAME,
                          'system_settings2.php?service=' . $className,
                         );
        }

        return $submenu;

    }//end getAServices()

    /**
     * Gibt den HTML-Header aus.
     *
     * @return void
     * @TODO   Stylesheet für den Kalender anpassen
     * @TODO   Wenn der MVP-Pattern ankommt, doppelung mit header.php vermeiden
     */
    private function _printHeader()
    {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
        <title> <?php echo $this->title; ?>@Wachplan Salem</title>
        <meta charset="UTF-8" />
        <?php
        foreach ($this->_styles as $style){
            echo '<link href="' . $style . '" type="text/css" rel="stylesheet" />' . "\n";
        }?>
        <link rel="stylesheet"
            href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css"
        >
        <?php
        foreach ($this->_scripts as $scripts){
            echo '<script src="' . $scripts . '" /></script>'. "\n";
        }?>

        </head>
        <?php
        NULL;

    }//end _printHeader()

    /**
     * Erzeugt das Menü für den Wachplan.
     *
     * @return void
     * @TODO Einstellungseite erzeugen
     */
    private function _printMenu()
    {
        $menu = array();
        $menu[] = array(
                   'Eigene Daten',
                   'index.php#own_data',
                  );
        $menu[] = array(
                   'Wachplan',
                   'index.php#plan',
                  );
        $menu[] = array(
                   'Telefonliste',
                   'index.php#tele_plan',
                  );

        if (checkRights('feedback') === TRUE) {
            $menu[] = array(
                       'Feedback anschauen',
                       'feedback_show.php',
                      );
            $submenu = self::getAServices();
            $menu[] = array(
                       'Einstellungen',
                       $submenu,
                      );
        }

        $menu[] = array(
                   'Logout',
                   'logout.php',
                  );

        // Menüdarstellung für normale Displays
        echo '<div class="menu">' . "\n";
        echo '<ul>' . "\n";
        foreach ($menu as $menupoint) {
            if (is_array($menupoint[1]) === FALSE) {
                self::_printMenupoint($menupoint[0], $menupoint[1]);
            } else {
                echo '<li><a href=#>' . $menupoint[0] . '</a><ul>';
                foreach ($menupoint[1] as $submenupoint) {
                    self::_printMenupoint($submenupoint[0], $submenupoint[1]);
                }//end foreach

                echo '</ul></li>' . "\n";
            }//end if
        }//end foreach

        echo '</ul>' . "\n";

        // Menüdarstellung für kleine Displays
        // TODO Auslagern in eigene JavaSkript datei
        echo '<script type="text/javascript">
                 function OnSelectionChange(select){
            	 document.location= select.options[select.selectedIndex].value;}
                 </script>';
        echo '<select onChange="OnSelectionChange (this)">' . "\n";
        foreach ($menu as $menupoint) {
            if (is_array($menupoint[1]) === FALSE) {
                self::_printMenupoint($menupoint[0], $menupoint[1], TRUE);
            } else {
                echo ' <optgroup label="' . $menupoint[0] . '">';
                foreach ($menupoint[1] as $submenupoint) {
                    self::_printMenupoint(
                        $submenupoint[0],
                        $submenupoint[1],
                        TRUE);
                }//end foreach

                echo '</optgroup>' . "\n";
            }//end if
        }//end foreach

        echo '</select>';
        echo '</div>' . "\n\n";

    }//end _printMenu()

    /**
     * Erzeugen eines Menuepunkts.
     *
     * @param string  $name   Name des Menuepunkts.
     * @param string  $link   Link zu der Seite.
     * @param boolean $option Wenn True dann nicht als li sonder option Element.
     *
     * @return void
     */
    private function _printMenupoint($name, $link, $option = FALSE)
    {
        if ($option === FALSE) {
            echo '<li><a href=" ';
            echo $link;
            echo ' ">';
            echo $name;
            echo '</a></li>' . "\n";
        } else {
            echo '<option value="';
            echo $link;
            echo ' ">';
            echo $name;
            echo '</option>' . "\n";
        }

    }//end _printMenupoint()

}//end class

?>