<?php

namespace app\services\reward;

use app\services\reward\connectors\LoyaltyPointConnector;
use app\services\reward\connectors\RewardConnectorInterface;

/**
 * Class LoyaltyPointRewardService
 * @package app\services\reward
 */
class LoyaltyPointRewardService extends AbstractRewardService
{
    /**
     * @return RewardConnectorInterface
     */
    public function getConnector(): RewardConnectorInterface
    {
        return new LoyaltyPointConnector();
    }
}
