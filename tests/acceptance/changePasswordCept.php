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
$I->wantTo('Change my Password as a User');
$I->amOnPage('/');
$I->fillField('username', 'test');
$I->fillField('pass', 'jr4pU7');
$I->click('submitButton');
$I->amOnPage('/intern/change_data.php');
$I->seeInField('user_name', 'test');
$I->seeInField('first_name', 'test');
$I->seeInField('last_name', 'Mustermann');
$I->seeInField('email', 'friedl.sebastian@web.de');
$I->seeInField('tele', '123456789');
$I->see('Meine persönlichen Daten');
$I->fillField('oldPassword', 'jr4pU7');
$I->fillField('pass1', 'jr4pU7');
$I->fillField('pass2', 'jr4pU7');
$I->click('submitPassword');
$I->dontSeeElement('.meldung');