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

$config = array(
    'loginMode'     => 'default', // possible values are 'ldap','default'
    //LDAP
    'ldapUser'      => 'cn=admin,dc=salem-dlrg,dc=de',
    'ldapPassword'  => 'wR{c|(LliFBK&Y0v',
    'ldapBaseDn'    => 'dc=salem-dlrg,dc=de',
    'ldapHost'      => 'swim-und-run-salem.de',
    // mySQL Database
    'dbhost'        => 'localhost',
    'dbuser'        => 'root',
    'dbpassword'    => 'root',
    'dbname'        => 'wachplan',
    //app infos
    'title'         => 'Wachplan 2016'
);
?>
