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
