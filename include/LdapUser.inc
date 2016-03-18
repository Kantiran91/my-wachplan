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
require_once $_SERVER['DOCUMENT_ROOT'] . '/wachplan/init.php';
require_once INCLUDEPATH . 'UserContactData.inc';
require_once CORE . 'Ldap.inc';

class LdapUser implements IUser
{

    private $m_defaultUser;

    private $m_ldapObj;

    private $m_userName;

    public function __construct(IUser $defaultUser)
    {
        $this->m_defaultUser = $defaultUser;
    }

    /**
     *
     * @see IUser @notice LDAP switch from Database to LDAP
     */
    public function loginUser($aName, $aPassword)
    {
        $this->m_ldapObj = new Ldap($aName);
        if ($this->m_ldapObj->loginUser($aPassword)) {
            $_SESSION['userDatabase'] = 'ldap';
            $this->m_userName = $aName;
            return true;
        } else
            if ($this->m_defaultUser->loginUser($aName, $aPassword)) {
                $this->m_ldapObj . setNewUserPassword($aName, $aPassword);
                return true;
            } else {
                return false;
            }
    }

    public function getUserBaseAttributes($aName)
    {
        $this->m_ldapObj = new Ldap($aName);
        $result = $this->m_ldapObj->getAttribute();
        $result['rights'] = 0;
        return $result;
    }

    public function changePassword($aOldPassword, $aNewPassword)
    {
        $this->m_ldapObj = new Ldap($this->m_userName);
        $this->m_ldapObj->loginUser($aOldPassword);
        return $this->m_ldapObj->changePassword($aNewPassword);
    }

    public function getUserContactData()
    {
        $this->m_ldapObj = new Ldap($this->m_userName);
        $result = $this->m_ldapObj->getContactData();
        $ContactData = new userContactData();
        $ContactData->username = $result['username'];
        $ContactData->firstName = $result['firstName'];
        $ContactData->lastName = $result['lastName'];
        $ContactData->eMail = $result['eMail'];
        $ContactData->phoneNumber = $result['phoneNumber'];
        return $ContactData;
    }

    public function checkUserExists($aName)
    {
        $ldap = new Ldap($aName);
        return $ldap->checkUserExist();
    }

    public function setNewPassword($aName,$aNewPassword)
    {
        $ldap = new Ldap($aName);
        return $ldap->setNewUserPassword($aName, $aNewPassword);
    }
}