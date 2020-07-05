<?php

namespace app\services\reward\connectors;

use app\models\forms\prize\LoyaltyPointPrizeForm;
use app\models\User;
use app\models\UserPrize;
use yii\base\BaseObject;

/**
 * Class LoyaltyPointConnector
 * @package app\services\reward\connectors
 */
class LoyaltyPointConnector extends BaseObject implements RewardConnectorInterface
{
    /**
     * @param UserPrize $userPrize
     * @return bool
     */
    public function charge(UserPrize $userPrize): bool
    {
        $form = new LoyaltyPointPrizeForm();
        $form->loadUserPrize($userPrize);
        $result = User::updateAllCounters(
            ['loyalty_point' => $form->getPrize()],
            ['id' => (int)$userPrize->user->getId()]
        );

        if ($result !== 0) {
            $userPrize->status = UserPrize::STATUS_RECEIVED;
        }

        return $result !== 0 ? true : false;
    }
}
