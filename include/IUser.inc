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



/**
 * This class provides access to the user management. You can get information
 * about the currently logged in user and the permissions for example
 */
interface IUser
{
    /**
     * method is called a the begin of a session
     *
     * @param   string $aName      loginName of the user
     * @param   string $aPassword  password of the user
     * @return  bool
     */
    public function loginUser($aName, $aPassword);

    /**
     * get the attribute of a user like
     * - userid
     * - email
     * - username
     * - rights
     * @return  array of the attibutes
     */
    public function getUserBaseAttributes($aName);

    /**
     * change the password of the user
     * @param   unknown $aOldPassword   old password that was used before
     * @param   unknown $aNewPassword   new Password
     * @return  bool
     */
    public function changePassword($aOldPassword,$aNewPassword);

    /**
     * Get the contact Data from the user
     *
     * @param UserContactData The hole ContactData
     */
    public function getUserContactData();

    /**
     * This method check if the user is in the persistent storage
     *
     * @param string $aName Identifiere of the User.
     * @return boolean true if the user exist
     */
    public function checkUserExists($aName);

    /**
     * create Password for user if he lost his.
     * @param string $aName Identifiere of the User.
     * @param   unknown $aNewPassword   new Password
     *
     * @return  bool
     */
    public function setNewPassword($aName,$aNewPassword);

    public function checkUserHasAccess($app);

}