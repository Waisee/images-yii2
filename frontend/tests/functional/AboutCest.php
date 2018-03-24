<?php
namespace frontend\tests;

use frontend\tests\FunctionalTester;

class AboutCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function checkAbout(FunctionalTester $I)
    {
        $I->amOnRoute('site/about');
        $I->see('About Images project', 'h1');
    }
}
