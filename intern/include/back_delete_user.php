<?php
/**
 * lösscht einen Benutzer.
 *
 * PHP versions 5
 *
 * LICENSE: This source file is subject CC BY 4.0 license
 *
 * @author  Sebastian Friedl <friedl.sebastian@web.de>
 * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
 * @version GIT: $Date: Sun May 10 09:51:12 2015 +0200$
 * @link    http:/salem.dlrg.de
 **/
require_once '../../init.php';
checkSession();
checkRights(2);
$stmt = $database->prepare('DELETE FROM `wp_user` WHERE `id_user`=?');
$stmt->bind_param('i', $_POST['id_user']);
if ($stmt->execute() === TRUE) {
    ?>
    <div class=meldung>
        <br> Person wurde gelöscht! <br> <bR> <a class="button"
            onclick="hide_massage()"
        >schließen</a>
    </div>
    <?php
}
?>
