<?php

namespace app\services\reward\connectors;

use app\models\UserPrize;
use yii\base\BaseObject;

/**
 * Class PaymentApiConnector
 * @package app\services\reward\connectors
 */
class PaymentApiConnector extends BaseObject implements RewardConnectorInterface
{
    /** @var string */
    private $token;

    /**
     * BankApiConnector constructor.
     * @param string $token
     * @param array $config
     */
    public function __construct(string $token, $config = [])
    {
        parent::__construct($config);
        $this->token = $token;
    }

    /**
     * @param UserPrize $userPrize
     * @return bool
     */
    public function charge(UserPrize $userPrize): bool
    {
        //SEND API REQUEST TO PAYMENT SYSTEM
        $userPrize->status = UserPrize::STATUS_RECEIVED;
        return true;
    }
}
