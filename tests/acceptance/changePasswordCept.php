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
$userName = 'admin';
$pw = 'admin';
$I = new AcceptanceTester($scenario);
$I->wantTo('Change my user data and my password!');
$I->login($userName, $pw);
$I->amOnPage('/intern/change_data.php');
$I->seeInField('user_name', $userName);
$I->seeInField('first_name', 'Maxi');
$I->seeInField('last_name', 'Mustermann');
$I->seeInField('email', 'friedl.sebastian@web.de');
$I->seeInField('tele', '123456789');
$I->seeInField('gb', '12.02.1999');
$I->seeOptionIsSelected('abzeichen', 'DRSA Bronze');
$I->seeOptionIsSelected('med', 'san');
$I->see('Meine persönlichen Daten');
$I->fillField('oldPassword', 'jr4pU7');
$I->fillField('pass1', $pw);
$I->fillField('pass2', $pw);
$I->click('submitPassword');
$I->dontSeeElement('.meldung');