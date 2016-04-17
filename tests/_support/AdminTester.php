<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class AdminTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

   /**
    * Define custom actions here
    */
    public function login($name, $password)
    {
     $I = $this;
    $I->amOnPage('/');
    $I->fillField('username', $name);
    $I->fillField('pass', $password);
    $I->click('submitButton');
    }

    public function seeTheMenu()
    {
        $I = $this;
        $I->see('Wachplan','.menu');
        $I->see('Telefonliste','.menu');
        $I->see('Eigene Daten','.menu');
        $I->see('Feedback anschauen','.menu');
        $I->see('Einstellungen','.menu');
        $I->see('Logout','.menu');
    }
}
