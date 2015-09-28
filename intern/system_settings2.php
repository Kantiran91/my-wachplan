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
 * @category CategoryName
 * @package  my-wachplan
 * @author  Sebastian Friedl <friedl.sebastian@web.de>
 * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
 * @version GIT:$Date $
 * @link    http:/salem.dlrg.de
 **/
require_once '../init.php';
require_once 'include/View.inc';
checkSession();
checkRights(2);

$view = View::getInstance();

if (isset($_GET['service']) === TRUE) {
    include_once 'aservice/' . get('service') . '.inc';
    $serviceName = get('service');
    $service = new $serviceName();

    // Backend
    if (isset($_GET['method']) === TRUE) {
        $methode = get('method');
        $meldung = $service->$methode($_POST);
        if (is_array($meldung) === TRUE) {
            $view->addMeldung($meldung);
        }
    }

    //Frontend
    $view->setTitle($service->title);
    $view->setContent($service->getFrontend());
} else {
    $view->setTitle('Systemeinstellungen');
    $menupoints = View::getAServices();
    $menuArray = array();
    foreach ($menupoints as $point) {
        $menuArray[] = '<a href="' . $point[1] . '">' . $point[0] . '</a>';
    }

    $menu = '{
  "header": "Einstellungen",
  "moduls": [
    {
      "header": " ",
      "description": "Folgende Einstellungen stehen zur Auswahl:",
      "type": "Table",
      "content": {
        "table":';
    $menu .= json_encode($menuArray);
    $menu .= '}}]}';
    $view->setContent($menu);
}//end if

$view->printPage();
?>
