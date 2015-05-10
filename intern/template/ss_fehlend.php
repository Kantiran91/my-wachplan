<?php
 /**
  * Template system_settings: Zeigt an wer sich noch nicht in den akutellen
  * Wachplan eingetragen hat.
  *
  * PHP versions 5
  *
  * LICENSE: This source file is subject CC BY 4.0 license
  *
  * @author  Sebastian Friedl <friedl.sebastian@web.de>
  * @license http://creativecommons.org/licenses/by/4.0/deed.en CC BY 4.0
  * @version GIT:  $Date: Thu May 7 14:43:07 2015 +0200$
  * @link    http:/salem.dlrg.de
  **/
require_once '../init.php';
checkSession();

$sql = 'SELECT
             `id_user`,
             `email`,
             `user_name`,
             `first_name`,
             `last_name`
       FROM `wp_user`
       LEFT Join `wp_poss_acc`ON `wp_user`.`id_user` = `wp_poss_acc`.`user_id`
       WHERE `wp_poss_acc`.`id` IS NULL GROUP BY `user_name`';

unset($meta);
unset($params);
unset($row);

$stmtList = $database->prepare($sql);
if (is_bool($stmtList) === FALSE) {
    $stmtList->execute();
    $meta = $stmtList->result_metadata();
    while ($field = $meta->fetch_field()) {
        $params[] = &$row[$field->name];
    }

    call_user_func_array(
        array(
         $stmtList,
         'bind_result',
        ),
        $params);

    while ($stmtList->fetch()) {
        foreach ($row as $key => $val) {
            $c[$key] = $val;
        }

        $list[] = $c;
    }

    $stmtList->close();
}//end if

// FRONTEND
?>
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Vorname</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>
	<?php
foreach ($list as &$row) {
    ?>
		   <tr>
              <td><?php echo $row['first_name']; ?></td>
              <td><?php echo $row['last_name']; ?></td>
              <td><?php echo $row['email']; ?></td>
           </tr>
   <?php
}
?>
	</tbody>
</table>
