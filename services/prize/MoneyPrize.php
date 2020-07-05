<?php

namespace app\services\prize;

use app\models\forms\prize\MoneyPrizeForm;
use app\models\UserPrize;
use app\services\reward\MoneyRewardService;
use app\services\rewardConvert\MoneyRewardConvertService;

/**
 * Class MoneyPrize
 * @package app\services\prize
 */
class MoneyPrize extends AbstractPrize
{
    /** @var int */
    private const MIN_VALUE = 1;
    /** @var int */
    private const MAX_VALUE = 10;
    /** @var int */
    protected const LIMIT = 5;

    /** @var int */
    protected $currency = self::CURRENCY_MONEY;

    /**
     * @return string|MoneyPrizeForm
     */
    public function getForm(): string
    {
        return MoneyPrizeForm::class;
    }

    /**
     * @param UserPrize $userPrize
     * @return int
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function convert(UserPrize $userPrize): int
    {
        /** @var MoneyRewardConvertService $service */
        $service = \Yii::$container->get(MoneyRewardConvertService::class);
        return $service->toLoyaltyPoints($userPrize);
    }

    /**
     * @return string|MoneyRewardService
     */
    protected function getRewardService(): string
    {
        return MoneyRewardService::class;
    }

    /**
     * @return array
     */
    protected function getConfig(): array
    {
        return [
            'min' => self::MIN_VALUE,
            'max' => self::MAX_VALUE,
            self::LIMIT_ATTRIBUTE_NAME => self::LIMIT,
        ];
    }

    /**
     * @return array
     */
    protected function getData(): array
    {
        if ($this->data === null) {
            $prizeConfig = $this->getConfig();

            $this->data = [
                'amount' => mt_rand($prizeConfig['min'], $prizeConfig['max']),
                'currency' => $this->currency,
            ];
        }

        return $this->data;
    }
}
