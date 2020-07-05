<?php

namespace tests\unit\services;

use app\models\UserPrize;
use app\services\PrizeService;
use app\tests\fixtures\UserFixture;
use app\tests\fixtures\UserPrizeFixture;

/**
 * Class PrizeServiceTest
 * @package tests\unit\services
 */
class PrizeServiceTest extends \Codeception\Test\Unit
{
    /** @var \app\services\PrizeService */
    private $service;

    protected function _before()
    {
        $this->service = \Yii::$container->get(PrizeService::class);
        $this->tester->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php',
            ],
            'user_prize' => [
                'class' => UserPrizeFixture::class,
                'dataFile' => codecept_data_dir() . 'user_prize.php',
            ],
        ]);
    }

    public function testConvert()
    {
        $userPrize = UserPrize::findOne(['id' => 1]);
        expect_that($userPrize);

        expect($this->service->convert($userPrize))->true();

        $userPrize = UserPrize::findOne(['id' => 1]);
        expect_that($userPrize);

        $data = json_decode($userPrize->data, true);
        expect($data)->hasKey('amount');
        expect($data)->hasKey('currency');

        expect($data['amount'])->equals(30);
        expect($data['currency'])->equals(2);
    }
}
