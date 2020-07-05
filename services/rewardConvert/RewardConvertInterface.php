<?php

namespace app\services\rewardConvert;

use app\models\UserPrize;

/**
 * Class RewardConvertInterface
 * @package app\services\rewardConvert
 */
interface RewardConvertInterface
{
    /**
     * @param UserPrize $userPrize
     * @return int
     */
    public function toLoyaltyPoints(UserPrize $userPrize): int;
}
