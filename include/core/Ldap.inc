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
 * You should have received a copy of the GNU Affero General Public
 * License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */
class Ldap {

    private $connection;

    private $shortUid;

    private $_longUid;

    public function __construct($name) {
        $username = 'uid=' . $name . ',ou=Users,dc=salem-dlrg,dc=de';
        $this->_longUid = $username;
        $this->shortUid = 'uid=' .$name;

        if ($this->connectToLdap() == false) {
            header('Location: ../index.php?error=LDAP connnect failed');
        }

    }

    private function connectToLdap()
    {
        if ($this->connection == null) {
            $host = $GLOBALS['config']['ldapHost'];

            $this->connection = ldap_connect($host);

            ldap_set_option($this->connection, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($this->connection, LDAP_OPT_REFERRALS, 0);
            return $this->connection;
        } else
            return true;
    }


    public function  loginUser($password){
        if ($this->checkUserExist() === true){
            try {
                $res =  @ldap_bind($this->connection, $this->_longUid, $password);
            } catch (Exception $e) {
                header('Location: ../index.php?error=Unable to bind to LDAP server2');
            }
            return $res;
        }
        else {
            header('Location: ../index.php?error=Could not found user!');
            return false;
        }
    }

    public function getAttribute(){
        $ldap = $this->connection;
        $baseDn = 'dc=salem-dlrg,dc=de';
        $attributes = array( 'uidnumber','mail','uid');
        $filter = $this->shortUid;
        $res = ldap_search($ldap, $baseDn, $filter, $attributes);

        if ($res === false) {
            return "LDAP search failed\r\n";
        }

        if (ldap_count_entries($ldap, $res) === 0) {
            return "LDAP search failed, no entries found\r\n";
        }

        $entry = ldap_get_entries($ldap, $res);
        if ($entry === false) {
            return "LDAP get entry failed\r\n";
        }
        $ret = array(
            'id_user' => $entry[0]['uidnumber'][0],
            'user_name' => $entry[0]['uid'][0],
            'email' => $entry[0]['mail'][0],
        );
        return $ret;

    }

    public function changeEmail($email){


    }

    public function getContactData(){
        $ldap = $this->connection;
        $baseDn = 'dc=salem-dlrg,dc=de';
        $attributes = array( 'inetOrgPerson');
        $filter = $this->shortUid;
        $res = ldap_search($ldap, $baseDn, $filter, $attributes);

        if ($res === false) {
            return "LDAP search failed\r\n";
        }

        if (ldap_count_entries($ldap, $res) === 0) {
            return "LDAP search failed, no entries found\r\n";
        }

        $entry = ldap_get_entries($ldap, $res);
        if ($entry === false) {
            return "LDAP get entry failed\r\n";
        }
        $ret = array(
            'username' => $entry[0]['uid'][0],
            'lastName' => $entry[0]['sn'][0],
            'firstName' => $entry[0]['givenname'][0],
            'eMail' => $entry[0]['mail'][0],
            'phoneNumber' => $entry[0]['homephone'][0],
        );
        return $ret;

    }


    public function changePassword($password){
        $entry['userPassword'] = $password;
        return ldap_mod_replace($this->connection, $this->_longUid, $entry);
    }

    public function setNewUserPassword($user,$password){
        $adminDn =  $GLOBALS['config']['ldapUser'];
        $adminPassword = $GLOBALS['config']['ldapPassword'];
        $res =  @ldap_bind($this->connection, $adminDn, $adminPassword);
        $entry['userPassword'] = $password;
        return $result = ldap_mod_add($this->connection, $this->_longUid, $entry);
    }

    public function getGroupMember($group){
        $con = $this->connection;
        $adminDn =  $GLOBALS['config']['ldapUser'];
        $adminPassword = $GLOBALS['config']['ldapPassword'];
        $res =  @ldap_bind($con, $adminDn, $adminPassword);
        $baseDn = 'ou=Group,dc=salem-dlrg,dc=de';
        $attributes = array('gidNumber','memberUid');
        $filter = 'gidNumber='. $group ;
        $res = ldap_search($con, $baseDn, $filter, $attributes);
        $entry = ldap_get_entries($con, $res);
        if ($res === false) {
            return false;
        }

        if (ldap_count_entries($con, $res) === 0) {
            return false;
        }

        $entry = ldap_get_entries($con, $res);
        if ($entry === false) {
            return false;
        }

        return $entry;
    }

    public function checkUserExist(){
        $ldap = $this->connection;
        $baseDn = 'dc=salem-dlrg,dc=de';
        $attributes = array('userPassword');
        $filter = $this->shortUid;
        $res = ldap_search($ldap, $baseDn, $filter, $attributes);

        if ($res === false) {
            return false;
        }

        if (ldap_count_entries($ldap, $res) === 0) {
            return false;
        }

        $entry = ldap_get_entries($ldap, $res);
        if ($entry === false) {
            return false;
        }

        return true;

    }
}