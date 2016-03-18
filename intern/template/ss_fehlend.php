<?php /**
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
