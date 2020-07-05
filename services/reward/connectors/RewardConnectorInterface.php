<?php

namespace app\services\reward\connectors;

use app\models\UserPrize;

/**
 * Interface RewardConnectorInterface
 * @package app\services\reward\connectors
 */
interface RewardConnectorInterface
{
    /**
     * @param UserPrize $userPrize
     * @return bool
     */
    public function charge(UserPrize $userPrize): bool;
}
