<?php

namespace app\services\prize;

use app\models\forms\prize\PrizeFormInterface;
use app\models\UserPrize;
use yii\base\Model;

/**
 * Interface PrizeEntityInterface
 * @package app\services\prize
 */
interface PrizeFactoryInterface
{
    /**
     * @return PrizeFormInterface|Model|null
     */
    public function playOut(): ?PrizeFormInterface;

    /**
     * @param UserPrize $userPrize
     * @return bool
     */
    public function reward(UserPrize $userPrize): bool;

    /**
     * @param UserPrize $userPrize
     * @return int|null
     */
    public function convert(UserPrize $userPrize): ?int;
}
