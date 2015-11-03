<?php
 /**
  * Boot-Loader für Unit-Tests
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
function loader($class)
{
    $file = $class . '.php';
    $file2 = $class . '.inc';
    if (file_exists($file)) {
        require $file;
    }
    else if (file_exists($file2)){
        require $file2;
    }
}
spl_autoload_register('loader');