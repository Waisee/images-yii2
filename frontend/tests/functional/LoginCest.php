<?php
namespace frontend\tests;

use frontend\tests\fixtures\UserFixture;
use frontend\tests\FunctionalTester;

class LoginCest
{
    public function _before(FunctionalTester $I)
    {
        $I->haveFixtures([
            'user' => [
                'class' => UserFixture::className(), 
            ]
        ]);
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function checkLoginWorking(FunctionalTester $I)
    {
        $I->amOnRoute('user/default/login');

        $formParams = [
            'LoginForm[email]' => '1@got.com',
            'LoginForm[password]' => '111111',
        ];

        $I->submitForm('#login-form', $formParams);

        $I->see('Eddard "Ned" Stark', 'form button[type=submit]');
    }

    public function checkLoginWrongPassword(FunctionalTester $I)
    {
        $I->amOnRoute('user/default/login');

        $formParams = [
            'LoginForm[email]' => '1@got.com',
            'LoginForm[password]' => 'wrong',
        ];

        $I->submitForm('#login-form', $formParams);

        $I->seeValidationError('Incorrect username or password.');
    }
}
