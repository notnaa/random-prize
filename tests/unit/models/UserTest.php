<?php

namespace tests\unit\models;

use app\models\User;
use app\tests\fixtures\UserFixture;

/**
 * Class UserTest
 * @package tests\unit\models
 */
class UserTest extends \Codeception\Test\Unit
{
    /** @var \UnitTester */
    protected $tester;

    protected function _before()
    {
        $this->tester->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php',
            ],
        ]);
    }

    public function testFindUserById()
    {
        expect_that($user = User::findIdentity(2));
        expect($user->email)->equals('admin@test.ru');

        expect_not(User::findIdentity(999));
    }

    public function testFindUserByEmail()
    {
        expect_that(User::findByEmail('admin@test.ru'));
        expect_not(User::findByEmail('not-admin'));
    }

    /**
     * @depends testFindUserByEmail
     */
    public function testValidateUser($user)
    {
        $user = User::findByEmail('admin@test.ru');
        expect_that($user->validatePassword('admin'));
        expect_not($user->validatePassword('123456'));        
    }

}
