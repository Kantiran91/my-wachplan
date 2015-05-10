<?php
 /**
  * Zwischenskript beim Ausloggen.
  *
  * Das Skript löscht die Session und leitet den Benutzer wieder auf die
  * öffentliche Startseite
  *
  * PHP versions 5
  *
  * LICENSE: This source file is subject CC BY 4.0 license
  *
  * @author  Sebastian Friedl <friedl.sebastian@web.de>
  * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
  * @version GIT: $Id$ $Date: Wed Apr 22 13:22:48 2015 +0200$
  * @link    http:/salem.dlrg.de
  **/
require_once '../init.php';
// Löschen der Session und zurück zum index
session_destroy();
header('location: ../index.php');

