<?php

namespace app\services\rewardConvert;

use app\models\forms\prize\MoneyPrizeForm;
use app\models\UserPrize;

/**
 * Class MoneyRewardConvertService
 * @package app\services\rewardConvert
 */
class MoneyRewardConvertService implements RewardConvertInterface
{
    /** @var int */
    private const RATIO = 10;

    /**
     * @param UserPrize $userPrize
     * @return int
     */
    public function toLoyaltyPoints(UserPrize $userPrize): int
    {
        $form = new MoneyPrizeForm();
        $form->loadUserPrize($userPrize);

        return (int)round($form->getPrize() * self::RATIO);
    }
}
