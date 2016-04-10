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
require_once INCLUDEPATH . 'UserContactData.inc';
require_once CORE . 'Ldap.inc';

class LdapUser implements IUser
{

    private $m_defaultUser;

    private $m_ldapObj;

    private $mUserName;

    private $m_AppRights = array (
        'settings' => array(10004,10007),
        'usersSettings' => array(10004,10007),
        'wachplanSettings' => array(10004,10007),
        'wachplanAdmin' => array(10004,10007,10002),
        'feedback' => array(10004,10007,10002),
    );

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
        $this->mUserName = $aName;
        if ($this->m_ldapObj->loginUser($aPassword)) {
            $_SESSION['userDatabase'] = 'ldap';
            return true;
        } else
            if ($this->m_defaultUser->loginUser($aName, $aPassword)) {
                $oldAtt = $this->m_defaultUser->getUserBaseAttributes($aName);
                $newAtt = $this->getUserBaseAttributes($aName);
                $this->changeDbUserId($oldAtt['id_user'], $newAtt['id_user']);
                $this->m_ldapObj->setNewUserPassword($aName, $aPassword);
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
        $this->m_ldapObj = new Ldap($this->mUserName);
        if ($this->m_ldapObj->loginUser($aOldPassword)){
            return $this->m_ldapObj->changePassword($aNewPassword);
        } else {
            return false;
        }
    }

    public function getUserContactData()
    {
        $this->m_ldapObj = new Ldap($this->mUserName);
        $result = $this->m_ldapObj->getContactData();
        $ContactData = new userContactData();
        $ContactData->username = $result['username'];
        $ContactData->firstName = $result['firstName'];
        $ContactData->lastName = $result['lastName'];
        $ContactData->eMail = $result['eMail'];
        $ContactData->phoneNumber = $result['phoneNumber'];
        return $ContactData;
    }

    public function saveUserContactData(UserContactData $data){
        $ldap = new Ldap($this->mUserName);
        $dataAsArray['givenName'] = $data->firstName;
        $dataAsArray['sn'] = $data->lastName;
        $dataAsArray['homePhone'] = $data->phoneNumber;
        $dataAsArray['mail'] = $data->eMail;
        return $ldap->setContactData($dataAsArray);
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

    public function checkUserHasAccess($app){
        $ldap = new Ldap($this->mUserName);
        foreach ($this->m_AppRights[$app] as $groupId) {
            $members = $ldap->getGroupMember($groupId);
            $key = array_search($this->mUserName,$members[0]['memberuid']);
            if (!is_bool($key)){
                return true;
            }
        }
    }

    private function changeDbUserId($oldId,$newId){
        $stmt = $GLOBALS['database']->prepare('UPDATE `wp_user` SET `id_user`=?  WHERE `id_user`=?');
        $stmt->bind_param('ii',$newId , $oldId);
        return $stmt->execute();
    }

}