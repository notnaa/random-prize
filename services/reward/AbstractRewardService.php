<?php

namespace app\services\reward;

use app\models\UserPrize;
use app\services\reward\connectors\RewardConnectorInterface;

/**
 * Class AbstractRewardService
 * @package app\services\reward
 */
abstract class AbstractRewardService
{
    /**
     * @return RewardConnectorInterface
     */
    abstract public function getConnector(): RewardConnectorInterface;

    /**
     * @param UserPrize $userPrize
     * @return bool
     */
    public function charge(UserPrize $userPrize): bool
    {
        $connector = $this->getConnector();
        return $connector->charge($userPrize);
    }
}
