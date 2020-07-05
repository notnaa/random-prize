<?php

namespace app\services\reward;

use app\services\reward\connectors\PaymentApiConnector;
use app\services\reward\connectors\RewardConnectorInterface;

/**
 * Class MoneyRewardService
 * @package app\services\reward
 */
class MoneyRewardService extends AbstractRewardService
{
    /** @var string */
    private $token = 'some-token';

    /**
     * @return RewardConnectorInterface
     */
    public function getConnector(): RewardConnectorInterface
    {
        return new PaymentApiConnector($this->token);
    }
}
