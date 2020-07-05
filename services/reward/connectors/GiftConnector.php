<?php

namespace app\services\reward\connectors;

use app\models\UserPrize;
use yii\base\BaseObject;

/**
 * Class GiftConnector
 * @package app\services\reward\connectors
 */
class GiftConnector extends BaseObject implements RewardConnectorInterface
{
    /**
     * @param UserPrize $userPrize
     * @return bool
     */
    public function charge(UserPrize $userPrize): bool
    {
        $userPrize->status = UserPrize::STATUS_WAIT_DELIVERY;
        return true;
    }
}
