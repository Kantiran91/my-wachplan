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

$I = new AcceptanceTester($scenario);
$I->wantTo('login as a  User and see the wachplan');
$I->login('max.mustermann', 'test');
$I->seeTheMenu();
$I->see('Telefonliste','h1');
$I->see('Wachplan', 'h1');
$I->see('Meine persönlichen Daten','h1');
$I->canSeeLink('Meine Daten bearbeiten','change_data.php');
