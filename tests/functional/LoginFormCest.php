<?php

use app\tests\fixtures\UserFixture;

/**
 * Class LoginFormCest
 */
class LoginFormCest
{
    /**
     * @return array
     */
    public function _fixtures() {
        return [
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php',
            ],
        ];
    }

    /**
     * @param FunctionalTester $I
     */
    public function _before(\FunctionalTester $I)
    {
        $I->amOnRoute('auth/login');
    }

    public function openLoginPage(\FunctionalTester $I)
    {
        $I->see('Login', 'h1');

    }

    // demonstrates `amLoggedInAs` method
    public function internalLoginById(\FunctionalTester $I)
    {
        $I->amLoggedInAs(2);
        $I->amOnPage('/');
        $I->see('Logout (admin@test.ru)');
    }

    // demonstrates `amLoggedInAs` method
    public function internalLoginByInstance(\FunctionalTester $I)
    {
        $I->amLoggedInAs(\app\models\User::findByEmail('admin@test.ru'));
        $I->amOnPage('/');
        $I->see('Logout (admin@test.ru)');
    }

    public function loginWithEmptyCredentials(\FunctionalTester $I)
    {
        $I->submitForm('#login-form', []);
        $I->expectTo('see validations errors');
        $I->see('Email cannot be blank.');
        $I->see('Password cannot be blank.');
    }

    public function loginWithWrongCredentials(\FunctionalTester $I)
    {
        $I->submitForm('#login-form', [
            'LoginForm[email]' => 'admin@test.ru',
            'LoginForm[password]' => 'wrong',
        ]);
        $I->expectTo('see validations errors');
        $I->see('Incorrect email or password.');
    }

    public function loginSuccessfully(\FunctionalTester $I)
    {
        $I->submitForm('#login-form', [
            'LoginForm[email]' => 'admin@test.ru',
            'LoginForm[password]' => 'admin',
        ]);
        $I->see('Logout (admin@test.ru)');
        $I->dontSeeElement('form#login-form');              
    }
}