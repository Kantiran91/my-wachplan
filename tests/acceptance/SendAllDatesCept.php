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

$I = new AdminTester($scenario);
$I->resetEmails();
$I->wantTo('send all user theirs dates');
$I->login('admin', 'admin');
$I->seeTheMenu();
$I->amOnPage('/intern/include/b_send_all_date.php');
$I->seeNumberOfElements('h1', 1);
$countOfUser = $I->grabTextFrom('h1');
$I->seeEmailCount($countOfUser);
$I->dontSeeInLastEmail('$');
$I->seeInLastEmailSubject('Einteilung zum Wachdienst');
$I->seeInLastEmail('Deine Wachdienstleitung');
