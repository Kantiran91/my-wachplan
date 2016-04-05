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
require_once  '../init.php';

class DefaultUser implements IUser
{

    private $m_userAtribute = '';

    private $m_userId;

    public function loginUser($aName, $aPassword)
    {
        $query = 'SELECT `id_user`, `email`,`user_name`, `hash`, `rights`
        FROM  `wp_user`
        WHERE `user_name`=';
        $query .= '"' . $_POST['username'] . '" AND `hash` =';
        $query .= '"' . encryptHash($_POST['pass']) . '"';
        $result = $GLOBALS['database']->query($query);

        $_SESSION['userDatabase'] = 'db';
        $this->m_userAtribute = mysqli_fetch_assoc($result);
        $this->m_userId = $this->m_userAtribute['id_user'];
        return $result->num_rows == 1;
    }

    public function getUserBaseAttributes($aName)
    {
            $query = 'SELECT `id_user`, `email`,`user_name`, `hash`, `rights`
        FROM  `wp_user`
        WHERE `user_name`=';
            $query .= '"' . $aName .'"';
            $result = $GLOBALS['database']->query($query);
            $temp = mysqli_fetch_assoc($result);
            return $temp;
    }

    public function changePassword($aOldPassword, $aNewPassword)
    {
        return $this->setNewPassword($this->m_userAtribute['user_name'],$aNewPassword);
    }

    public function getUserContactData(){
        $query = 'SELECT
        `user_name`,
		`email`,
		`telephone`,
		`first_name`,
		`last_name`
		FROM
		`wp_user`
		WHERE `id_user`=?';
        $stmt = $GLOBALS['database']->prepare($query);
        $stmt->bind_param('i', $this->m_userId);
        $stmt->execute();
        $result = getResultAsArray($stmt);
        $ContactData = new userContactData();
        $ContactData->username = $result[0]['user_name'];
        $ContactData->firstName = $result[0]['first_name'];
        $ContactData->lastName = $result[0]['last_name'];
        $ContactData->eMail = $result[0]['email'];
        $ContactData->phoneNumber = $result[0]['telephone'];
        $stmt->close();
        return $ContactData;
    }

    public function checkUserExists($aName)
    {
        $stmt = $GLOBALS['database']->prepare('SELECT `id_user`,`email`
    FROM `wp_user`
    WHERE `user_name`= ?');
        $stmt->bind_param('s',$aName);
        $stmt->bind_result($id, $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        $result = $stmt->num_rows == 1;
        $stmt->close();
        return $result;
    }

    public function setNewPassword($aName,$aNewPassword){
        $hashValue  = encryptHash($aNewPassword);
        $stmt = $GLOBALS['database']->prepare('UPDATE `wp_user` SET `hash`=?  WHERE `user_name`=?');
        $stmt->bind_param('ss',$hashValue , $aName);
        return $stmt->execute();
    }
}