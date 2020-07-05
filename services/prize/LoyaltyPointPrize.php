<?php

namespace app\services\prize;

use app\models\forms\prize\LoyaltyPointPrizeForm;
use app\services\reward\LoyaltyPointRewardService;

/**
 * Class LoyaltyPointPrize
 * @package app\services\prize
 */
class LoyaltyPointPrize extends AbstractPrize
{
    /** @var int */
    private const MIN_VALUE = 10;
    /** @var int */
    private const MAX_VALUE = 100;

    /** @var null */
    protected const LIMIT = null;

    /** @var int */
    protected $currency = self::CURRENCY_LOYALTY_POINT;

    /**
     * @return string|LoyaltyPointPrizeForm
     */
    public function getForm(): string
    {
        return LoyaltyPointPrizeForm::class;
    }

    /**
     * @return string|LoyaltyPointRewardService
     */
    protected function getRewardService(): string
    {
        return LoyaltyPointRewardService::class;
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
