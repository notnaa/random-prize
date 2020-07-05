<?php

namespace app\services\reward;

use app\services\reward\connectors\GiftConnector;
use app\services\reward\connectors\RewardConnectorInterface;

/**
 * Class GiftRewardService
 * @package app\services\reward
 */
class GiftRewardService extends AbstractRewardService
{
    /**
     * @return RewardConnectorInterface
     */
    public function getConnector(): RewardConnectorInterface
    {
        return new GiftConnector();
    }
}
